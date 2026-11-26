<?php

namespace Tests\Feature;

use App\Data\SeoCities;
use App\Models\User;
use App\Services\SafetyModerationService;
use App\Support\SqlSafe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LiveAndSeoFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_over_200_indian_and_global_seo_cities_are_registered_and_render(): void
    {
        $cities = SeoCities::all();
        $this->assertGreaterThanOrEqual(200, count($cities));

        $this->get('/')->assertStatus(200)->assertSee('Free Forever Access')->assertSee('Explore Cities');
        $this->get('/cities')->assertStatus(200)->assertSee('Free Dating in New Delhi')->assertSee('Free Dating in McLeod Ganj');
        $this->get('/dating-in/shimla')->assertStatus(200)->assertSee('Shimla');
        $this->get('/dating-in/mcleod-ganj')->assertStatus(200)->assertSee('McLeod Ganj');
        $this->get('/dating-in/bengaluru')->assertStatus(200)->assertSee('Bengaluru');
        $this->get('/dating-in/india')->assertStatus(200)->assertSee('India');
        $this->get('/sitemap.xml')->assertStatus(200)->assertSee('/dating-in/mumbai')->assertSee('/cafes');
    }

    public function test_cuptv_live_video_moments_and_real_live_stats_endpoints_work(): void
    {
        $this->get('/live-video')->assertStatus(200)->assertSee('CupTV')->assertSee('Start Live Match');
        $this->get('/moments')->assertStatus(200)->assertSee('Share Coffee Moments');
        $this->get('/cafes')->assertStatus(200)->assertSee('Women-Friendly Café Reviews');

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

    public function test_messages_cafe_reviews_and_safety_moderation_work_end_to_end(): void
    {
        $safety = app(SafetyModerationService::class);
        $this->assertFalse($safety->screenText('send nude pics now http://a.com http://b.com')['allowed']);
        $this->assertTrue($safety->screenText('Enjoying an iced latte at Mall Road in Shimla!')['allowed']);

        $sender = User::create([
            'email' => 'aarav@cupdate.in',
            'password_hash' => Hash::make('Secret123!'),
            'full_name' => 'Aarav Sharma',
            'dob' => '1999-05-10',
            'gender' => 'male',
            'preference' => 'everyone',
            'city' => 'Shimla',
            'state' => 'Himachal Pradesh',
            'status' => 'active',
        ]);

        $partner = User::create([
            'email' => 'meera@cupdate.in',
            'password_hash' => Hash::make('Secret123!'),
            'full_name' => 'Meera Thakur',
            'dob' => '2000-08-14',
            'gender' => 'female',
            'preference' => 'everyone',
            'city' => 'Dharamshala',
            'state' => 'Himachal Pradesh',
            'status' => 'active',
        ]);

        // Messaging UI opens, sends JSON message, and polls cleanly
        $this->actingAs($sender)
            ->get('/app/messages?with=' . $partner->id)
            ->assertStatus(200)
            ->assertSee('Meera');

        $sendRes = $this->actingAs($sender)
            ->postJson('/app/messages/' . $partner->id, [
                'message' => 'Hi Meera, want to grab coffee in Dharamshala?',
            ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($sendRes['ok']);
        $this->assertSame('Hi Meera, want to grab coffee in Dharamshala?', $sendRes['message']['body']);

        $pollRes = $this->actingAs($partner)
            ->getJson('/app/messages/' . $sender->id . '/poll?after=0')
            ->assertStatus(200)
            ->json();

        $this->assertCount(1, $pollRes['messages']);

        // Café review submission & spam rejection
        $this->actingAs($partner)
            ->post('/cafes', [
                'cafe_name' => 'Illiterati Books & Cafe',
                'city' => 'Dharamshala',
                'rating' => 5,
                'safety_tag' => 'Women-Friendly & Safe',
                'review_text' => 'Bright sunlit balcony, kind staff, and peaceful vibe for a daytime coffee.',
            ])
            ->assertRedirect('/cafes');

        $this->get('/cafes')->assertStatus(200)->assertSee('Illiterati Books & Cafe');

        // Moments moderation
        $this->actingAs($partner)
            ->post('/moments', [
                'content' => 'Send nude pics on onlyfans right now',
            ])
            ->assertSessionHasErrors('content');

        $this->actingAs($partner)
            ->post('/moments', [
                'content' => 'Afternoon cappuccino overlooking Dharamshala hills!',
                'city' => 'Dharamshala',
                'mood' => 'Coffee Vibes',
            ])
            ->assertRedirect('/moments');

        $this->get('/moments')->assertStatus(200)->assertSee('Dharamshala hills!');
    }

    public function test_admin_dashboard_tracks_and_displays_real_visitor_locations(): void
    {
        $this->withHeaders([
            'CF-IPCountry' => 'IN',
            'X-Geo-City' => 'Dharamshala',
            'X-Geo-Region' => 'Himachal Pradesh',
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) Mobile',
        ])->get('/')->assertStatus(200);

        $admin = User::create([
            'email' => 'admin-analytics@cupdate.in',
            'password_hash' => Hash::make('Secret123!'),
            'full_name' => 'CupDate Super Admin',
            'dob' => '1995-05-10',
            'gender' => 'other',
            'preference' => 'everyone',
            'city' => 'Shimla',
            'state' => 'Himachal Pradesh',
            'is_admin' => true,
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertStatus(200)
            ->assertSee('Where Visitors Come From')
            ->assertSee('Dharamshala')
            ->assertSee('Himachal Pradesh')
            ->assertSee('Mobile');
    }

    public function test_search_console_legacy_php_city_blog_and_ai_crawler_urls_succeed(): void
    {
        $okRoutes = [
            '/community/lgbtq',
            '/city/patiala',
            '/rishta',
            '/city/delhi-ncr',
            '/blog/dating-pune',
            '/blog/best-himachal-dating-website',
            '/blog/best-delhi-dating-website',
            '/city/toronto',
            '/faq.php',
            '/city/chandigarh',
            '/dates',
            '/disclaimer.php',
            '/blog/haryana-dating-guide',
            '/terms',
            '/blog/women-dating-safety-guide-india',
            '/coffee-date-ideas',
            '/privacy',
            '/city/manali',
            '/community-guidelines.php',
            '/dating-navi-mumbai',
            '/dating-in-bathinda',
            '/blog-how-to-report-suspicious-profile.php',
            '/blog-mbti-personality-dating-compatibility.php',
            '/blog/how-to-impress-on-a-first-date',
            '/dating-karachi',
            '/tips-red-flags.php',
            '/dating-up',
        ];

        foreach ($okRoutes as $route) {
            $this->get($route)->assertStatus(200);
        }

        $this->get('/city.php?city=jamshedpur')->assertRedirect('/dating-in/jamshedpur');
        $this->get('/city.php?city=peshawar')->assertRedirect('/dating-in/peshawar');

        $this->get('/robots.txt')
            ->assertStatus(200)
            ->assertSee('ClaudeBot')
            ->assertSee('GPTBot')
            ->assertSee('PerplexityBot');

        $this->assertFileExists(public_path('llms.txt'));
    }

    public function test_security_headers_online_users_by_city_favicon_and_design_rules(): void
    {
        $this->assertFileExists(public_path('favicon.ico'));
        $this->assertGreaterThan(100, filesize(public_path('favicon.ico')));

        $this->assertSame('abc\\%\\_def\\\\', SqlSafe::escapeLike('  abc%_def\\  '));

        User::create([
            'email' => 'online-shimla@cupdate.in',
            'password_hash' => Hash::make('Secret123!'),
            'full_name' => 'Kavita Thakur',
            'dob' => '1999-03-15',
            'gender' => 'female',
            'preference' => 'everyone',
            'city' => 'Shimla',
            'state' => 'Himachal Pradesh',
            'country' => 'India',
            'is_online' => true,
            'last_active' => now(),
            'status' => 'active',
        ]);

        $admin = User::create([
            'email' => 'admin-security@cupdate.in',
            'password_hash' => Hash::make('Secret123!'),
            'full_name' => 'Security Admin',
            'dob' => '1994-01-10',
            'gender' => 'other',
            'preference' => 'everyone',
            'city' => 'Shimla',
            'state' => 'Himachal Pradesh',
            'is_admin' => true,
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        foreach (['/', '/privacy-policy', '/terms-and-conditions', '/discover', '/live-video'] as $path) {
            $resp = $this->get($path);
            $resp->assertStatus(200)
                ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
                ->assertHeader('X-Content-Type-Options', 'nosniff')
                ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

            if ($path === '/') {
                $resp->assertSee('data-tinder-deck', false)
                    ->assertSee('data-hero-bg-slider', false)
                    ->assertSee('couple-cafe-1.jpg')
                    ->assertSee('hero-coffee-couple.jpg');
            }

            $html = $resp->getContent();
            $this->assertStringNotContainsString("\u{2014}", $html);
            $this->assertStringNotContainsString('Made with AI', $html);
        }

        $this->actingAs($admin)
            ->get('/admin')
            ->assertStatus(200)
            ->assertSee('Online Users Right Now by City')
            ->assertSee('Shimla')
            ->assertSee('1 online');
    }
}
