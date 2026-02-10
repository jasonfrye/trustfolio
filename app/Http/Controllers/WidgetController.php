<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Review;
use App\Models\WidgetSetting;
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

        $query = Review::where('creator_id', $creator->id)
            ->approved()
            ->where('is_private_feedback', false)
            ->where('rating', '>=', $settings->minimum_rating ?? 1);

        match ($settings->sort_order) {
            'highest_rated' => $query->orderBy('rating', 'desc')->orderBy('created_at', 'desc'),
            'random' => $query->inRandomOrder(),
            default => $query->orderBy('created_at', 'desc'),
        };

        $reviews = $query->limit($settings->limit ?? 10)->get();

        // Generate ETag based on settings and reviews for cache busting
        $settingsUpdated = $settings->updated_at ?? now();
        $lastReviewUpdated = $reviews->max('updated_at') ?? now();
        $etag = md5($settingsUpdated.$lastReviewUpdated.$reviews->count());

        $starFilled = '★';
        $starEmpty = '☆';

        // Build CSS variables based on theme
        if ($settings->theme === 'dark') {
            $primaryColor = '#818cf8';
            $bgColor = '#1f2937';
            $textColor = '#f3f4f6';
        } elseif ($settings->theme === 'custom') {
            $primaryColor = $settings->primary_color;
            $bgColor = $settings->background_color;
            $textColor = $settings->text_color;
        } else {
            // Light theme defaults
            $primaryColor = '#4f46e5';
            $bgColor = '#ffffff';
            $textColor = '#1f2937';
        }

        $borderRadius = $settings->border_radius.'px';

        // Build complete CSS
        $css = <<<CSS
.trustfolio-widget {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    --tf-primary: {$primaryColor};
    --tf-bg: {$bgColor};
    --tf-text: {$textColor};
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
.trustfolio-carousel {
    position: relative;
    overflow: hidden;
    min-height: 200px;
    width: 100%;
}
.trustfolio-carousel-track {
    display: flex;
    transition: transform 0.5s ease;
    gap: 0;
    width: 100%;
}
.trustfolio-carousel-nav {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 10;
}
.trustfolio-carousel-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(0,0,0,0.3);
    cursor: pointer;
    transition: all 0.3s;
}
.trustfolio-carousel-dot.active {
    background: var(--tf-primary);
    width: 24px;
    border-radius: 4px;
}
.trustfolio-carousel .trustfolio-card {
    min-width: 100%;
    width: 100%;
    flex-shrink: 0;
    box-sizing: border-box;
}
.trustfolio-masonry {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    grid-auto-rows: 10px;
    gap: 16px;
}
.trustfolio-wall {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
}
.trustfolio-wall .trustfolio-card {
    padding: 12px;
    text-align: center;
}
.trustfolio-wall .trustfolio-content {
    font-size: 12px;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.trustfolio-single {
    position: relative;
    min-height: 200px;
}
.trustfolio-single .trustfolio-card {
    position: absolute;
    width: 100%;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
    pointer-events: none;
}
.trustfolio-single .trustfolio-card.active {
    opacity: 1;
    pointer-events: auto;
}
.trustfolio-card {
    background: var(--tf-bg);
    color: var(--tf-text);
    border-radius: {$borderRadius};
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
CSS;

        // Encode CSS for JavaScript (json_encode handles all escaping)
        $cssJson = json_encode($css);

        $js = <<<JS
(function() {
    const containerId = 'reviewbridge-widget-' + Math.random().toString(36).substr(2, 9);
    const collectionUrl = '{$collectionUrl}';
    const starFilled = '{$starFilled}';
    const starEmpty = '{$starEmpty}';

    // Inject widget styles
    function injectStyles() {
        if (document.getElementById('reviewbridge-styles')) return;
        const style = document.createElement('style');
        style.id = 'reviewbridge-styles';
        style.textContent = {$cssJson};
        document.head.appendChild(style);
    }

    // Find all widget containers
    const containers = document.querySelectorAll('[data-collection="' + collectionUrl + '"]');
    containers.forEach((container, index) => {
        injectStyles();
        container.id = containerId + '-' + index;
        renderWidget(container, {$reviews->toJson()}, {$settings->toJson()});
    });

    function renderWidget(container, reviews, settings) {
        const theme = settings.theme || 'light';
        const layout = settings.layout || 'cards';

        let html = '<div class="trustfolio-widget trustfolio-' + theme + ' trustfolio-' + layout + '">';

        if (reviews.length === 0) {
            html += '<p class="trustfolio-empty">No reviews yet.</p>';
        } else {
            if (layout === 'carousel') {
                html += '<div class="trustfolio-carousel-track">';
                reviews.forEach(t => {
                    html += renderReview(t, settings);
                });
                html += '</div>';
                html += '<div class="trustfolio-carousel-nav">';
                reviews.forEach((_, i) => {
                    html += '<div class="trustfolio-carousel-dot' + (i === 0 ? ' active' : '') + '" data-index="' + i + '"></div>';
                });
                html += '</div>';
            } else if (layout === 'single') {
                reviews.forEach((t, i) => {
                    html += renderReview(t, settings, i === 0 ? 'active' : '');
                });
            } else {
                reviews.forEach(t => {
                    html += renderReview(t, settings);
                });
            }
        }

        html += '</div>';

        if (settings.show_branding !== false) {
            html += '<div class="trustfolio-branding">Powered by <a href="https://reviewbridge.app" target="_blank">ReviewBridge</a></div>';
        }

        container.innerHTML = html;

        // Initialize carousel
        if (layout === 'carousel' && reviews.length > 0) {
            initCarousel(container, reviews.length);
        }

        // Initialize single rotating
        if (layout === 'single' && reviews.length > 0) {
            initSingleRotating(container, reviews.length);
        }

        // Initialize masonry
        if (layout === 'masonry' && reviews.length > 0) {
            initMasonry(container);
        }
    }

    function renderReview(t, settings, className = '') {
        const showRatings = settings.show_ratings !== false;
        const showDates = settings.show_dates !== false;
        const stars = showRatings ? starFilled.repeat(t.rating) + starEmpty.repeat(5 - t.rating) : '';
        const date = showDates && t.created_at ? new Date(t.created_at).toLocaleDateString() : '';

        return '<div class="trustfolio-card ' + className + '">' +
            (stars ? '<div class="trustfolio-rating">' + stars + '</div>' : '') +
            '<blockquote class="trustfolio-content">"' + escapeHtml(t.content) + '"</blockquote>' +
            '<div class="trustfolio-author">' +
            (t.author_avatar_url ? '<img src="' + escapeHtml(t.author_avatar_url) + '" alt="' + escapeHtml(t.author_name) + '" class="trustfolio-avatar">' : '') +
            '<div class="trustfolio-meta">' +
            '<strong>' + escapeHtml(t.author_name) + '</strong>' +
            (t.author_title ? '<span class="trustfolio-title">' + escapeHtml(t.author_title) + '</span>' : '') +
            (date ? '<span class="trustfolio-date">' + date + '</span>' : '') +
            '</div>' +
            '</div>' +
            '</div>';
    }

    function initCarousel(container, reviewCount) {
        let currentIndex = 0;
        const track = container.querySelector('.trustfolio-carousel-track');
        const dots = container.querySelectorAll('.trustfolio-carousel-dot');

        function goToSlide(index) {
            currentIndex = index;
            track.style.transform = 'translateX(-' + (index * 100) + '%)';
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
        }

        // Auto-rotate every 5 seconds
        setInterval(() => {
            currentIndex = (currentIndex + 1) % reviewCount;
            goToSlide(currentIndex);
        }, 5000);

        // Click handlers for dots
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => goToSlide(i));
        });
    }

    function initSingleRotating(container, reviewCount) {
        let currentIndex = 0;
        const cards = container.querySelectorAll('.trustfolio-card');

        setInterval(() => {
            cards[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % reviewCount;
            cards[currentIndex].classList.add('active');
        }, 8000);
    }

    function initMasonry(container) {
        const masonryContainer = container.querySelector('.trustfolio-masonry');
        if (!masonryContainer) return;

        function layoutMasonry() {
            const cards = masonryContainer.querySelectorAll('.trustfolio-card');
            const rowHeight = 10; // matches grid-auto-rows
            const gap = 16; // matches gap in CSS

            cards.forEach(card => {
                // Use scrollHeight to get the natural content height
                const cardHeight = card.scrollHeight;
                const rowSpan = Math.ceil((cardHeight + gap) / (rowHeight + gap));
                card.style.gridRowEnd = 'span ' + rowSpan;
            });
        }

        // Wait for images to load before calculating layout
        const images = masonryContainer.querySelectorAll('img');
        let imagesLoaded = 0;
        const totalImages = images.length;

        function checkAndLayout() {
            imagesLoaded++;
            if (imagesLoaded >= totalImages) {
                setTimeout(layoutMasonry, 50);
            }
        }

        if (totalImages === 0) {
            // No images, layout immediately
            setTimeout(layoutMasonry, 50);
        } else {
            // Wait for each image to load
            images.forEach(img => {
                if (img.complete) {
                    checkAndLayout();
                } else {
                    img.addEventListener('load', checkAndLayout);
                    img.addEventListener('error', checkAndLayout); // Handle errors too
                }
            });
        }

        // Recalculate on window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(layoutMasonry, 250);
        });
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
            'Cache-Control' => 'public, max-age=300', // 5 minutes instead of 1 hour
            'ETag' => $etag,
        ]);
    }
}
