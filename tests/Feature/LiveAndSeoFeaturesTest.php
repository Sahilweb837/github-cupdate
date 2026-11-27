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
            ->get('/app/profile')
            ->assertStatus(200)
            ->assertDontSee('MySQL')
            ->assertSee('Edit Your CupDate Profile');

        $this->actingAs($sender)
            ->get('/app/messages?with=' . $partner->id)
            ->assertStatus(200)
            ->assertSee('Meera')
            ->assertSee('data-skeleton-loader', false)
            ->assertSee('enterkeyhint="send"', false);

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
            '/rishta',
            '/blog/dating-pune',
            '/blog/best-himachal-dating-website',
            '/blog/best-delhi-dating-website',
            '/blog/haryana-dating-guide',
            '/blog/women-dating-safety-guide-india',
            '/blog/how-to-impress-on-a-first-date',
            '/dating-in/patiala',
            '/dating-in/chandigarh',
            '/dating-in/manali',
        ];

        foreach ($okRoutes as $route) {
            $this->get($route)->assertStatus(200);
        }

        $redirects = [
            '/city/patiala' => '/dating-in/patiala',
            '/city/delhi-ncr' => '/dating-in/delhi-ncr',
            '/city/toronto' => '/dating-in/toronto',
            '/city/chandigarh' => '/dating-in/chandigarh',
            '/city/manali' => '/dating-in/manali',
            '/faq.php' => '/blog/faq',
            '/dates' => '/how-it-works',
            '/disclaimer.php' => '/blog/disclaimer',
            '/terms' => '/terms-and-conditions',
            '/privacy' => '/privacy-policy',
            '/coffee-date-ideas' => '/coffee-date-ideas-in-himachal-pradesh',
            '/community-guidelines.php' => '/community-guidelines',
            '/dating-navi-mumbai' => '/dating-in/navi-mumbai',
            '/dating-in-bathinda' => '/dating-in/bathinda',
            '/blog-how-to-report-suspicious-profile.php' => '/blog/how-to-report-suspicious-profile',
            '/blog-mbti-personality-dating-compatibility.php' => '/blog/mbti-personality-dating-compatibility',
            '/dating-karachi' => '/dating-in/karachi',
            '/tips-red-flags.php' => '/blog/tips-red-flags',
            '/dating-up' => '/dating-in/up',
            '/city.php?city=jamshedpur' => '/dating-in/jamshedpur',
            '/city.php?city=peshawar' => '/dating-in/peshawar',
        ];

        foreach ($redirects as $legacy => $target) {
            $this->get($legacy)->assertRedirect($target);
        }

        $this->get('/robots.txt')
            ->assertStatus(200)
            ->assertSee('ClaudeBot')
            ->assertSee('GPTBot')
            ->assertSee('PerplexityBot');

        $this->assertFileExists(public_path('llms.txt'));
    }

    public function test_security_headers_online_users_by_city_favicon_and_design_rules(): void
    {
        $this->assertFileExists(public_path('images/cupdate-logo.svg'));
        $this->assertFileExists(public_path('images/cupdate-logo.jpg'));

        $this->assertSame('abc\\%\\_def\\\\', SqlSafe::escapeLike('  abc%_def\\  '));

        User::create([
            'email' => 'kavita.shimla@example.com',
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
                $resp->assertSee('real-couple-shimla.jpg')
                    ->assertSee('real-couple-delhi.jpg')
                    ->assertSee('couple-cafe-1.jpg')
                    ->assertSee('Mandatory 18+ Age Check');
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

    public function test_google_auth_removed_hashed_visitor_logs_truth_dare_travel_groups_and_skeletons(): void
    {
        // Google auth removed from login UI; legacy /auth/google redirects cleanly to /login without 404
        $this->get('/login')
            ->assertStatus(200)
            ->assertDontSee('Continue with Google');
        $this->get('/auth/google')->assertRedirect('/login');

        // Legacy & alias redirects and canonicals prevent duplicate canonicals and 404s
        $this->get('/dating-in/patiala')
            ->assertStatus(200)
            ->assertSee('<link rel="canonical" href="https://cupdate.in/dating-in/patiala">', false);
        $this->get('/about.php')->assertRedirect('/about-us');
        $this->get('/meet-singles-in-jaipur')->assertRedirect('/dating-in/jaipur');
        $this->get('/truth-or-dare')->assertRedirect('/truth-and-dare');
        $this->get('/video-chat')->assertRedirect('/live-video');
        $this->get('/robots.txt')
            ->assertStatus(200)
            ->assertDontSee('Disallow: /*?filter=');

        // Plain IP is hashed before storing in visitor_logs
        $this->withHeaders(['REMOTE_ADDR' => '198.51.100.77'])
            ->get('/truth-and-dare')
            ->assertStatus(200)
            ->assertSee('data-truth-dare-app', false)
            ->assertSee('Truth or Dare');

        $this->assertDatabaseMissing('visitor_logs', ['ip_address' => '198.51.100.77']);

        // New SEO ranking pages, user search, travel groups & skeleton loaders
        $seoRoutes = [
            '/truth-and-dare',
            '/travel-groups',
            '/travel-groups?q=Spiti',
            '/icebreaker-questions',
            '/weekend-meetups',
            '/speed-dating',
            '/couple-activities',
            '/college-dating-india',
            '/solo-travel-companions',
            '/dating-etiquette-india',
        ];
        foreach ($seoRoutes as $route) {
            $this->get($route)->assertStatus(200);
        }

        foreach (['/', '/discover', '/cafes', '/cities', '/moments', '/travel-groups'] as $pageWithSkeleton) {
            $this->get($pageWithSkeleton)
                ->assertStatus(200)
                ->assertSee('data-skeleton-loader', false);
        }

        $this->get('/sitemap.xml')
            ->assertStatus(200)
            ->assertSee('/truth-and-dare')
            ->assertSee('/travel-groups')
            ->assertSee('/speed-dating')
            ->assertSee('/icebreaker-questions');
    }

    public function test_search_console_drilldown_623_404_patterns_are_resolved(): void
    {
        $redirectExpectations = [
            '/hyderabad' => '/dating-in/hyderabad',
            '/haryana' => '/dating-in/gurugram',
            '/sanjauli' => '/dating-in/shimla',
            '/summer-hill-hpu' => '/dating-in/shimla',
            '/bhuntar' => '/dating-in/kullu',
            '/city.php?name=Kasol' => '/dating-in/kasol',
            '/city.php?name=New%20Shimla' => '/dating-in/shimla',
            '/city.php?city=rawalpindi' => '/dating-in/rawalpindi',
            '/dating-dating-delhi' => '/dating-in/new-delhi',
            '/dating/dating-kullu' => '/dating-in/kullu',
            '/dating-islamabad' => '/dating-in/islamabad',
            '/mcleodganj.php' => '/dating-in/mcleod-ganj',
            '/blog-online-rishta-india-guide' => '/rishta',
            '/blog-introvert-dating.php' => '/blog/how-to-impress-on-a-first-date',
            '/blog-online-dating-safety-tips.php' => '/blog/women-dating-safety-guide-india',
            '/blog/kullu.php' => '/dating-in/kullu',
            '/blog/sitemap.xml' => '/sitemap.xml',
            '/sitemap-post.xml' => '/sitemap.xml',
            '/scam-awareness.php' => '/safety',
            '/freevideocall.php' => '/live-video',
            '/online-dating-punjab-girls.php' => '/dating-in/chandigarh',
            '/india-rishta.php' => '/rishta',
            '/search_term_string' => '/discover',
        ];

        foreach ($redirectExpectations as $legacy => $target) {
            $this->get($legacy)->assertRedirect($target);
        }

        // Direct modern hubs for GSC cities work 200 OK
        foreach (['/up', '/dating-in/up', '/dating-in/miami', '/dating-in/paris', '/dating-in/tokyo', '/dating-in/lahore', '/dating-in/islamabad'] as $cityPath) {
            $this->get($cityPath)->assertStatus(200);
        }

        // Retired profile & message URLs return 410 Gone
        $this->get('/user/rahul.kumar')->assertStatus(410);
        $this->get('/messages.php?receiver_id=60')->assertStatus(410);
    }
}
