<?php

namespace Tests\Feature;

use App\Data\SeoCities;
use App\Models\User;
use App\Services\SafetyModerationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LiveAndSeoFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_over_100_indian_and_global_seo_cities_are_registered_and_render(): void
    {
        $cities = SeoCities::all();
        $this->assertGreaterThanOrEqual(100, count($cities));

        $this->get('/')->assertStatus(200)->assertSee('Free Forever Access');
        $this->get('/cities')->assertStatus(200)->assertSee('Free Dating in New Delhi');
        $this->get('/dating-in/shimla')->assertStatus(200)->assertSee('Shimla');
        $this->get('/dating-in/bengaluru')->assertStatus(200)->assertSee('Bengaluru');
        $this->get('/dating-in/india')->assertStatus(200)->assertSee('India');
        $this->get('/sitemap.xml')->assertStatus(200)->assertSee('/dating-in/mumbai');
    }

    public function test_live_video_moments_and_real_live_stats_endpoints_work(): void
    {
        $this->get('/live-video')->assertStatus(200)->assertSee('Start Live Match');
        $this->get('/moments')->assertStatus(200)->assertSee('Share Coffee Moments');

        $stats = $this->getJson('/live-stats')->assertStatus(200)->json();
        $this->assertArrayHasKey('active_visitors', $stats);
        $this->assertArrayHasKey('online_members', $stats);
        $this->assertArrayHasKey('live_video_active', $stats);

        $join = $this->postJson('/live-video/join', [
            'target_gender' => 'everyone',
            'country' => 'India',
        ])->assertStatus(200)->json();

        $this->assertTrue($join['ok']);
        $this->assertNotEmpty($join['queue_id']);
    }

    public function test_safety_moderation_blocks_nudity_and_spam_and_allows_clean_moments(): void
    {
        $safety = app(SafetyModerationService::class);
        $blocked = $safety->screenText('send nude pics now http://a.com http://b.com');
        $this->assertFalse($blocked['allowed']);

        $clean = $safety->screenText('Enjoying an iced latte at Mall Road in Shimla!');
        $this->assertTrue($clean['allowed']);
        $this->assertFalse($clean['flagged']);

        $user = User::create([
            'email' => 'member@cupdate.in',
            'password_hash' => Hash::make('Secret123!'),
            'full_name' => 'Aarav Sharma',
            'dob' => '1999-05-10',
            'gender' => 'male',
            'preference' => 'everyone',
            'city' => 'Shimla',
            'state' => 'Himachal Pradesh',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->post('/moments', [
                'content' => 'Send nude pics on onlyfans right now',
            ])
            ->assertSessionHasErrors('content');

        $this->actingAs($user)
            ->post('/moments', [
                'content' => 'Afternoon cappuccino overlooking Dharamshala hills!',
                'city' => 'Dharamshala',
                'mood' => 'Coffee Vibes',
            ])
            ->assertRedirect('/moments');

        $this->get('/moments')->assertStatus(200)->assertSee('Dharamshala hills!');
    }
}
