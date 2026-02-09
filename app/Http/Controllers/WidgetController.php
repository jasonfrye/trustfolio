<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Testimonial;
use App\Models\WidgetSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class WidgetController extends Controller
{
    /**
     * Generate the widget JavaScript for a creator.
     */
    public function script(string $collectionUrl)
    {
        $creator = Creator::where('collection_url', $collectionUrl)->firstOrFail();

        $settings = WidgetSetting::where('creator_id', $creator->id)->first() ?? new \App\Models\WidgetSetting([
            'theme' => 'light',
            'primary_color' => '#4f46e5',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'border_radius' => '8',
            'layout' => 'cards',
            'limit' => 10,
            'show_ratings' => true,
            'show_avatars' => true,
            'show_dates' => true,
            'minimum_rating' => 1,
            'sort_order' => 'recent',
            'show_branding' => true,
        ]);

        $testimonials = Testimonial::where('creator_id', $creator->id)
            ->approved()
            ->orderBy('created_at', 'desc')
            ->limit($settings->limit ?? 10)
            ->get();

        $starFilled = '★';
        $starEmpty = '☆';

        // Build CSS variables based on theme
        if ($settings->theme === 'dark') {
            $cssVars = <<<CSS
--tf-primary: #818cf8;
--tf-bg: #1f2937;
--tf-text: #f3f4f6;
CSS;
        } elseif ($settings->theme === 'custom') {
            $cssVars = <<<CSS
--tf-primary: {$settings->primary_color};
--tf-bg: {$settings->background_color};
--tf-text: {$settings->text_color};
CSS;
        } else {
            // Light theme defaults
            $cssVars = <<<CSS
--tf-primary: #4f46e5;
--tf-bg: #ffffff;
--tf-text: #1f2937;
CSS;
        }

        $js = <<<JS
(function() {
    const containerId = 'trustfolio-widget-' + Math.random().toString(36).substr(2, 9);
    const collectionUrl = '{$collectionUrl}';
    const starFilled = '{$starFilled}';
    const starEmpty = '{$starEmpty}';
    const cssVars = `{$cssVars}`;
    const borderRadius = '{$settings->border_radius}px';

    // Inject widget styles
    function injectStyles() {
        if (document.getElementById('trustfolio-styles')) return;
        const style = document.createElement('style');
        style.id = 'trustfolio-styles';
        style.textContent = \`
            .trustfolio-widget {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                --tf-primary: \${cssVars.split(':')[1].split(';')[0]};
                --tf-bg: \${cssVars.split('--tf-bg:')[1].split(';')[0]};
                --tf-text: \${cssVars.split('--tf-text:')[1].split(';')[0]};
                font-size: 14px;
                line-height: 1.5;
            }
            .trustfolio-widget * {
                box-sizing: border-box;
            }
            .trustfolio-cards {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            .trustfolio-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 16px;
            }
            .trustfolio-list {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .trustfolio-card {
                background: var(--tf-bg);
                color: var(--tf-text);
                border-radius: \${borderRadius};
                padding: 16px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                border: 1px solid rgba(0,0,0,0.05);
            }
            .trustfolio-dark .trustfolio-card {
                background: #374151;
                border-color: #4b5563;
            }
            .trustfolio-rating {
                color: var(--tf-primary);
                font-size: 16px;
                margin-bottom: 8px;
                letter-spacing: 1px;
            }
            .trustfolio-content {
                margin: 0 0 12px 0;
                font-style: italic;
                color: var(--tf-text);
            }
            .trustfolio-author {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .trustfolio-avatar {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                object-fit: cover;
            }
            .trustfolio-meta {
                display: flex;
                flex-direction: column;
            }
            .trustfolio-meta strong {
                font-weight: 600;
                color: var(--tf-text);
            }
            .trustfolio-title {
                font-size: 12px;
                opacity: 0.7;
                color: var(--tf-text);
            }
            .trustfolio-date {
                font-size: 12px;
                opacity: 0.6;
                color: var(--tf-text);
            }
            .trustfolio-empty {
                text-align: center;
                padding: 20px;
                color: var(--tf-text);
                opacity: 0.6;
            }
            .trustfolio-branding {
                margin-top: 16px;
                text-align: center;
                font-size: 11px;
                opacity: 0.5;
            }
            .trustfolio-branding a {
                color: var(--tf-primary);
                text-decoration: none;
            }
        \`;
        document.head.appendChild(style);
    }

    // Find all widget containers
    const containers = document.querySelectorAll('[data-collection="' + collectionUrl + '"]');
    containers.forEach((container, index) => {
        injectStyles();
        container.id = containerId + '-' + index;
        renderWidget(container, {$testimonials->toJson()}, {$settings->toJson()});
    });

    function renderWidget(container, testimonials, settings) {
        const theme = settings.theme || 'light';
        const layout = settings.layout || 'cards';

        let html = '<div class="trustfolio-widget trustfolio-' + theme + ' trustfolio-' + layout + '">';

        if (testimonials.length === 0) {
            html += '<p class="trustfolio-empty">No testimonials yet.</p>';
        } else {
            testimonials.forEach(t => {
                html += renderTestimonial(t, settings);
            });
        }

        html += '</div>';

        if (settings.show_branding !== false) {
            html += '<div class="trustfolio-branding">Powered by <a href="https://trustfolio.app" target="_blank">TrustFolio</a></div>';
        }

        container.innerHTML = html;
    }

    function renderTestimonial(t, settings) {
        const showRatings = settings.show_ratings !== false;
        const showDates = settings.show_dates !== false;
        const stars = showRatings ? starFilled.repeat(t.rating) + starEmpty.repeat(5 - t.rating) : '';
        const date = showDates && t.created_at ? new Date(t.created_at).toLocaleDateString() : '';

        return \`
            <div class="trustfolio-card">
                \${stars ? '<div class="trustfolio-rating">' + stars + '</div>' : ''}
                <blockquote class="trustfolio-content">"\${escapeHtml(t.content)}"</blockquote>
                <div class="trustfolio-author">
                    \${t.author_avatar_url ? '<img src="' + escapeHtml(t.author_avatar_url) + '" alt="' + escapeHtml(t.author_name) + '" class="trustfolio-avatar">' : ''}
                    <div class="trustfolio-meta">
                        <strong>\${escapeHtml(t.author_name)}</strong>
                        \${t.author_title ? '<span class="trustfolio-title">' + escapeHtml(t.author_title) + '</span>' : ''}
                        \${date ? '<span class="trustfolio-date">' + date + '</span>' : ''}
                    </div>
                </div>
            </div>
        \`;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
})();
JS;

        return Response::make($js, 200, [
            'Content-Type' => 'application/javascript',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
