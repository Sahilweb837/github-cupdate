<?php

namespace Tests\Feature;

use Tests\TestCase;

class CupdatePagesTest extends TestCase
{
    public function test_public_and_app_pages_render_successfully(): void
    {
        $routes = [
            '/',
            '/how-it-works',
            '/about',
            '/contact',
            '/login',
            '/join',
            '/privacy',
            '/terms',
            '/safety',
            '/app/discover',
            '/app/radar',
            '/app/messages',
            '/app/dates',
            '/app/profile',
        ];

        foreach ($routes as $uri) {
            $response = $this->get($uri);
            $response->assertStatus(200);
        }
    }

    public function test_home_page_displays_cupdate_content(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Coffee first, chemistry next')
            ->assertSee('Less swiping. More meeting.')
            ->assertSee('Love stories, updated');
    }

    public function test_contact_form_submits_with_confirmation(): void
    {
        $this->post('/contact', [
            'name' => 'Sofia',
            'email' => 'sofia@cupdate.app',
            'topic' => 'General question',
            'message' => 'Loving the coffee-first approach to dating!',
        ])->assertRedirect()->assertSessionHas('contact_success');
    }

    public function test_join_form_authenticates_into_discover(): void
    {
        $this->post('/auth', [
            'mode' => 'join',
            'name' => 'Sofia',
            'city' => 'Portland, OR',
            'email' => 'sofia@cupdate.app',
            'password' => 'secret123',
        ])->assertRedirect('/app/discover');
    }
}
