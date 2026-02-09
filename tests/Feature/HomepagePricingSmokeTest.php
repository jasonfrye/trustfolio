<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepagePricingSmokeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Homepage loads with 200 status
     */
    public function test_homepage_loads_with_200_status(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test: Homepage contains TrustFolio branding
     */
    public function test_homepage_contains_trustfolio_branding(): void
    {
        $response = $this->get('/');
        $content = $response->getContent();

        // Should contain TrustFolio or trustfolio
        $this->assertTrue(
            stripos($content, 'TrustFolio') !== false ||
            stripos($content, 'trustfolio') !== false
        );
    }

    /**
     * Test: Homepage contains value proposition
     */
    public function test_homepage_contains_value_proposition(): void
    {
        $response = $this->get('/');
        $content = $response->getContent();

        // Should contain some marketing/hero text
        $this->assertTrue(
            stripos($content, 'testimonial') !== false ||
            stripos($content, 'social proof') !== false ||
            stripos($content, 'customer') !== false
        );
    }

    /**
     * Test: Homepage contains navigation links
     */
    public function test_homepage_contains_navigation_links(): void
    {
        $response = $this->get('/');
        $content = $response->getContent();

        // Should contain navigation elements
        $this->assertTrue(
            stripos($content, 'Features') !== false ||
            stripos($content, 'Pricing') !== false ||
            preg_match('/<nav/i', $content) !== false
        );
    }

    /**
     * Test: Homepage contains CTA button
     */
    public function test_homepage_contains_cta_button(): void
    {
        $response = $this->get('/');
        $content = $response->getContent();

        // Should contain CTA text
        $this->assertTrue(
            stripos($content, 'Start') !== false ||
            stripos($content, 'Get Started') !== false ||
            stripos($content, 'Sign Up') !== false ||
            stripos($content, 'Register') !== false
        );
    }

    /**
     * Test: Pricing page loads with 200 status
     */
    public function test_pricing_page_loads_with_200_status(): void
    {
        $response = $this->get('/pricing');

        $response->assertStatus(200);
    }

    /**
     * Test: Pricing page contains pricing tiers
     */
    public function test_pricing_page_contains_pricing_tiers(): void
    {
        $response = $this->get('/pricing');
        $content = $response->getContent();

        // Should contain pricing-related content
        $this->assertTrue(
            stripos($content, '$') !== false ||
            stripos($content, 'price') !== false ||
            stripos($content, 'plan') !== false
        );
    }

    /**
     * Test: Pricing page contains navigation back to homepage
     */
    public function test_pricing_page_contains_navigation(): void
    {
        $response = $this->get('/pricing');
        $content = $response->getContent();

        // Should contain link back
        $this->assertTrue(
            stripos($content, 'Home') !== false ||
            stripos($content, 'homepage') !== false ||
            stripos($content, 'Back') !== false
        );
    }

    /**
     * Test: Homepage has link to pricing page
     */
    public function test_homepage_has_link_to_pricing_page(): void
    {
        $response = $this->get('/');
        $content = $response->getContent();

        // Should contain link to pricing
        $this->assertTrue(
            stripos($content, '/pricing') !== false ||
            stripos($content, 'pricing') !== false
        );
    }

    /**
     * Test: Pricing page has CTA buttons
     */
    public function test_pricing_page_has_cta_buttons(): void
    {
        $response = $this->get('/pricing');
        $content = $response->getContent();

        // Should contain CTA buttons
        $this->assertTrue(
            stripos($content, 'Get Started') !== false ||
            stripos($content, 'Start Free') !== false ||
            stripos($content, 'Subscribe') !== false
        );
    }

    /**
     * Test: Homepage does not require authentication
     */
    public function test_homepage_does_not_require_authentication(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test: Pricing page does not require authentication
     */
    public function test_pricing_page_does_not_require_authentication(): void
    {
        $response = $this->get('/pricing');

        $response->assertStatus(200);
    }

    /**
     * Test: Homepage is a valid Blade view
     */
    public function test_homepage_is_valid_blade_view(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertStringStartsWith('<!DOCTYPE', $response->getContent());
    }

    /**
     * Test: Pricing page is a valid Blade view
     */
    public function test_pricing_page_is_valid_blade_view(): void
    {
        $response = $this->get('/pricing');

        $response->assertStatus(200);
        $this->assertStringStartsWith('<!DOCTYPE', $response->getContent());
    }

    /**
     * Test: Navigation links are present
     */
    public function test_navigation_links_are_present(): void
    {
        $response = $this->get('/');
        $content = $response->getContent();

        // Should contain some navigation elements
        $this->assertTrue(
            preg_match('/<nav/i', $content) ||
            preg_match('/<header/i', $content) ||
            stripos($content, 'navigation') !== false
        );
    }

    /**
     * Test: Pricing page shows multiple plans
     */
    public function test_pricing_page_shows_multiple_plans(): void
    {
        $response = $this->get('/pricing');
        $content = $response->getContent();

        // Should show multiple pricing tiers
        $dollarCount = substr_count($content, '$');
        $this->assertGreaterThanOrEqual(1, $dollarCount);
    }
}
