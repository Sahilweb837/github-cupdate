<?php

namespace App\Http\Controllers;

use App\Data\SeoCities;
use App\Data\SeoGuides;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\ContactMessage;
use App\Models\DatePlace;
use App\Models\InterestCategory;
use App\Models\Moment;
use App\Models\SiteSetting;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\LiveStatsService;
use App\Services\SafetyModerationService;
use App\Support\SqlSafe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(Request $request, LiveStatsService $liveStats): View|RedirectResponse
    {
        $rawUri = (string) ($request->server('REQUEST_URI') ?: $request->getRequestUri());
        if (str_starts_with($rawUri, '/index.php') && ($legacyCity = $request->query('city'))) {
            return redirect()->route('seo.city', ['slug' => \Illuminate\Support\Str::slug((string) $legacyCity)], 301);
        }

        $filter = $request->query('filter', 'nearby');
        $cityFilter = $request->query('city');
        $interestFilter = $request->query('interest');

        $query = User::query()
            ->where('status', 'active')
            ->where('profile_visible', true)
            ->where('is_admin', false)
            ->where('email', 'not like', '%@cupdate.in')
            ->whereNotIn('role', User::ADMIN_ROLES);

        if ($cityFilter) {
            $query->where('city', $cityFilter);
        }

        if ($interestFilter) {
            $query->where('interests', 'like', '%' . SqlSafe::escapeLike((string) $interestFilter) . '%');
        }

        match ($filter) {
            'new' => $query->latest('created_at'),
            'popular' => $query->orderByDesc('is_verified')->orderByDesc('is_online')->latest('last_active'),
            default => $query->orderByDesc('is_online')->orderByRaw("CASE WHEN state = 'Himachal Pradesh' THEN 0 ELSE 1 END")->latest('last_active'),
        };

        $featuredUsers = $query->take(6)->get();
        $cities = City::where('is_active', true)->orderBy('sort_order')->get();
        $interests = InterestCategory::where('is_active', true)->orderBy('name')->get();
        $datePlaces = DatePlace::orderByDesc('rating')->take(4)->get();

        $recentMoments = collect();
        if (Schema::hasTable('posts')) {
            $momentsQuery = Moment::with('user')->latest('created_at');
            if (Schema::hasColumn('posts', 'is_approved')) {
                $momentsQuery->where('is_approved', true)->where('is_flagged', false);
            }
            $recentMoments = $momentsQuery->take(4)->get();
        }

        $faqs = [
            ['q' => 'Is CupDate a 100% free coffee and CupTV live video dating website?', 'a' => 'Yes. CupDate (cupdate.in) is free to join and use - creating a profile, discovering members across 200+ Indian & global cities, messaging, sharing Coffee Moments, and joining CupTV live video chats cost nothing.'],
            ['q' => 'How does CupTV Live Video work?', 'a' => 'Open CupTV Live Video, choose your preference and region, and connect face-to-face via encrypted WebRTC with real online members. Tap Next anytime or use one-click safety reporting.'],
            ['q' => 'Can girls and members share coffee moments on social media?', 'a' => 'Yes. Members can post verified Coffee Moments and share them cleanly to WhatsApp, Instagram, X, or Facebook with built-in anti-nudity and anti-spam safety screening.'],
            ['q' => 'How can I find people nearby across India and the world?', 'a' => 'Use the Discover filters or explore our 200+ city & country pages from Himachal Pradesh, Delhi, Mumbai, Bengaluru, and Pune to global hubs without exposing your exact street location.'],
            ['q' => 'How does CupDate prevent nudity, spam, and fake profiles?', 'a' => 'Every message, photo upload, and Coffee Moment passes through automated anti-spam and anti-nudity screening plus human moderator review, verification badges, and instant block/report tools.'],
        ];

        return view('pages.home', [
            'featuredUsers' => $featuredUsers,
            'cities' => $cities,
            'interests' => $interests,
            'datePlaces' => $datePlaces,
            'recentMoments' => $recentMoments,
            'liveStats' => $liveStats->snapshot(),
            'seoCitiesPreview' => array_slice(array_values(SeoCities::all()), 0, 24),
            'totalSeoCities' => count(SeoCities::all()),
            'activeFilter' => $filter,
            'activeCity' => $cityFilter,
            'activeInterest' => $interestFilter,
            'faqs' => $faqs,
            'headline' => SiteSetting::getValue('homepage_headline', 'Meet Local People Over Coffee and Free Live Video.'),
            'subheadline' => SiteSetting::getValue('homepage_subheadline', 'Verified profiles across India and global cities, free 1-to-1 messaging, CupTV live video chat, and community cafe reviews without paywalls.'),
            'coffeeHeading' => SiteSetting::getValue('coffee_section_heading', 'Less Swiping. More Real Coffee Dates - 100% Free.'),
        ]);
    }

    public function liveStats(LiveStatsService $liveStats): JsonResponse
    {
        return response()->json($liveStats->snapshot());
    }

    public function cities(): View
    {
        return view('pages.cities', [
            'groups' => SeoCities::grouped(),
            'totalCities' => count(SeoCities::all()),
        ]);
    }

    public function cityLanding(string $slug): View
    {
        $city = SeoCities::findOrBuild($slug);
        abort_unless($city !== null, 404);

        $cityName = $city['name'];
        $stateName = $city['state'];
        $countryName = $city['country'];

        $membersQuery = User::query()
            ->where('status', 'active')
            ->where('profile_visible', true)
            ->where('is_admin', false)
            ->where('email', 'not like', '%@cupdate.in');

        if ($countryName === 'India' && $slug !== 'india') {
            $safeCity = SqlSafe::escapeLike((string) $cityName);
            $safeState = SqlSafe::escapeLike((string) $stateName);
            $membersQuery->where(function ($q) use ($safeCity, $safeState) {
                $q->where('city', 'like', "%{$safeCity}%")
                    ->orWhere('state', 'like', "%{$safeState}%");
            });
        }

        $allCities = SeoCities::all();
        $relatedCities = collect($allCities)
            ->reject(fn ($c) => $c['slug'] === $slug)
            ->sortByDesc(fn ($c) => $c['state'] === $stateName ? 2 : ($c['country'] === $countryName ? 1 : 0))
            ->take(12)
            ->values()
            ->all();

        $page = [
            'title' => "Free Coffee Dating, CupTV Live Video & Singles in {$cityName}, {$countryName} | CupDate",
            'h1' => "Free Coffee Dating & CupTV Video in {$cityName}",
            'description' => "Meet verified singles in {$cityName} ({$stateName}, {$countryName}) for free coffee dates, respectful chat, and CupTV live video on CupDate.",
            'city' => $city['slug'] === 'india' ? null : $cityName,
            'state' => $stateName,
            'country' => $countryName,
            'intro' => "{$city['vibe']} across {$city['spot']}. CupDate connects genuine people in {$cityName} with 100% free messaging, free CupTV live video chat, and strict anti-nudity & anti-spam moderation.",
            'highlights' => [
                "100% free dating & social discovery in {$cityName} - no paid subscription required",
                'Real-time CupTV live video & 1-to-1 encrypted calls',
                'Share Coffee Moments on WhatsApp, Instagram, and X with safety screening',
                'Strict anti-nudity, anti-spam & profile verification protection',
            ],
            'ideas' => [
                "Daytime coffee meetup around {$city['spot']}",
                "Start with a 5-minute CupTV Live Video hello in {$cityName}",
                'Share your favorite local café moment to spark real conversations',
            ],
        ];

        return view('pages.seo-landing', [
            'slug' => $slug,
            'page' => $page,
            'localMembers' => $membersQuery->take(4)->get(),
            'datePlaces' => DatePlace::orderByDesc('rating')->take(4)->get(),
            'relatedCities' => $relatedCities,
        ]);
    }

    public function discover(Request $request): View
    {
        $query = User::query()
            ->where('status', 'active')
            ->where('profile_visible', true)
            ->where('is_admin', false)
            ->where('email', 'not like', '%@cupdate.in')
            ->whereNotIn('role', User::ADMIN_ROLES);

        if ($city = $request->query('city')) {
            $query->where('city', $city);
        }
        if ($interest = $request->query('interest')) {
            $query->where('interests', 'like', '%' . SqlSafe::escapeLike((string) $interest) . '%');
        }
        if ($search = $request->query('q')) {
            $safe = SqlSafe::escapeLike((string) $search);
            $query->where(function ($q) use ($safe) {
                $q->where('full_name', 'like', "%{$safe}%")
                    ->orWhere('city', 'like', "%{$safe}%")
                    ->orWhere('bio', 'like', "%{$safe}%");
            });
        }

        $sort = $request->query('sort', 'nearby');
        match ($sort) {
            'new' => $query->latest('created_at'),
            'popular' => $query->orderByDesc('is_verified')->orderByDesc('is_online'),
            'random' => $query->inRandomOrder(),
            default => $query->orderByDesc('is_online')->latest('last_active'),
        };

        return view('pages.discover', [
            'profiles' => $query->paginate(12)->withQueryString(),
            'cities' => City::where('is_active', true)->orderBy('sort_order')->get(),
            'interests' => InterestCategory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function howItWorks(): View
    {
        return view('pages.how-it-works', [
            'datePlaces' => DatePlace::orderByDesc('rating')->take(4)->get(),
        ]);
    }

    public function safety(): View
    {
        return view('pages.safety');
    }

    public function about(): View
    {
        return view('pages.about', [
            'cities' => City::where('is_active', true)->orderBy('sort_order')->take(6)->get(),
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request, SafetyModerationService $safety): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:80'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        $check = $safety->screenText($validated['subject'] . ' ' . $validated['message']);
        if (! $check['allowed']) {
            return back()->withInput()->withErrors(['message' => $check['reason']]);
        }

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'message' => $validated['message'],
            'status' => 'new',
            'ip_address' => 'h_' . substr(hash_hmac('sha256', (string) ($request->ip() ?? ''), (string) config('app.key')), 0, 16),
        ]);

        SupportTicket::create([
            'user_id' => $request->user()?->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'open',
        ]);

        return back()->with('success', 'Thank you for reaching out. Our CupDate support team will review your message shortly.');
    }

    public function truthDare(): View
    {
        return view('pages.truth-dare');
    }

    public function travelGroups(Request $request): View
    {
        $allTrips = collect([
            [
                'title' => 'Dharamshala & Bir Billing Cafe Hopping',
                'region' => 'Himachal & Spiti',
                'style' => 'Cafe & Workation',
                'duration' => '3 Days · Weekend',
                'city_match' => 'Dharamshala',
                'meetup_spot' => 'McLeod Ganj & Bir',
                'image' => 'images/couple-cafe-1.jpg',
                'summary' => 'Relaxed mountain coffee meetup, bookstore evenings in McLeod Ganj, and scenic sunset views around Bir.',
            ],
            [
                'title' => 'Manali, Old Manali & Kasol Valley Trip',
                'region' => 'Himachal & Spiti',
                'style' => 'Trekking & Hills',
                'duration' => '4 Days · Group',
                'city_match' => 'Manali',
                'meetup_spot' => 'Old Manali Cafes',
                'image' => 'images/hero-coffee-couple.jpg',
                'summary' => 'Meet fellow remote workers and travelers for daytime riverside cafes, cedar forest walks, and group dinners.',
            ],
            [
                'title' => 'Shimla Heritage & Mall Road Walk',
                'region' => 'Himachal & Spiti',
                'style' => 'Cafe & Workation',
                'duration' => '2 Days · Weekend',
                'city_match' => 'Shimla',
                'meetup_spot' => 'The Ridge & Mall Road',
                'image' => 'images/cafe-blue-bottle.jpg',
                'summary' => 'Colonial architecture strolls, warm bakery mornings, and peaceful photography meetups in Shimla.',
            ],
            [
                'title' => 'Rishikesh Riverside & Mussoorie Drive',
                'region' => 'Uttarakhand',
                'style' => 'Weekend Road Trip',
                'duration' => '3 Days · Road Trip',
                'city_match' => 'Dehradun',
                'meetup_spot' => 'Tapovan & Landour',
                'image' => 'images/member-diego.jpg',
                'summary' => 'Early morning Ganges riverside coffee, scenic Landour bakehouse stop, and sunset viewpoints.',
            ],
            [
                'title' => 'Jaipur & Udaipur Heritage Cafe Route',
                'region' => 'Rajasthan Heritage',
                'style' => 'Heritage & Food',
                'duration' => '4 Days · Group',
                'city_match' => 'Jaipur',
                'meetup_spot' => 'C-Scheme & Lake Pichola',
                'image' => 'images/member-sofia.jpg',
                'summary' => 'Courtyard roasteries, historic forts, and sunset terrace meetups across Rajasthan.',
            ],
            [
                'title' => 'North Goa, Assagao & Coorg Road Group',
                'region' => 'Coastal & South India',
                'style' => 'Weekend Road Trip',
                'duration' => '4 Days · Coastal',
                'city_match' => 'Bengaluru',
                'meetup_spot' => 'Assagao & Indiranagar',
                'image' => 'images/member-amara.jpg',
                'summary' => 'Coastal specialty coffee spots, sunlit heritage lanes in Fontainhas, and weekend road-trip crews.',
            ],
        ]);

        $search = trim((string) $request->query('q', ''));
        $region = (string) $request->query('region', '');
        $style = (string) $request->query('style', '');

        $trips = $allTrips->filter(function (array $trip) use ($search, $region, $style) {
            if ($region !== '' && $trip['region'] !== $region) {
                return false;
            }
            if ($style !== '' && $trip['style'] !== $style) {
                return false;
            }
            if ($search !== '') {
                $haystack = strtolower($trip['title'] . ' ' . $trip['region'] . ' ' . $trip['summary'] . ' ' . $trip['meetup_spot']);
                if (! str_contains($haystack, strtolower($search))) {
                    return false;
                }
            }

            return true;
        })->values();

        return view('pages.travel-groups', [
            'trips' => $trips,
            'searchQuery' => $search,
            'activeRegion' => $region,
            'activeStyle' => $style,
        ]);
    }

    public function blog(): View
    {
        return view('pages.blog', [
            'posts' => BlogPost::where('is_published', true)->latest('published_at')->paginate(12),
            'seoGuides' => SeoGuides::all(),
        ]);
    }

    public function blogShow(string $slug): View
    {
        if ($guide = SeoGuides::findBySlug($slug)) {
            $relatedGuides = collect(SeoGuides::all())
                ->reject(fn ($g) => $g['slug'] === $slug)
                ->take(4)
                ->values()
                ->all();

            return view('pages.guide-show', [
                'guide' => $guide,
                'relatedGuides' => $relatedGuides,
            ]);
        }

        $post = BlogPost::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('pages.blog-show', ['post' => $post]);
    }

    public function guide(string $slug): View
    {
        $guide = SeoGuides::findBySlug($slug);
        abort_unless($guide !== null, 404);

        $relatedGuides = collect(SeoGuides::all())
            ->reject(fn ($g) => $g['slug'] === $slug)
            ->take(4)
            ->values()
            ->all();

        return view('pages.guide-show', [
            'guide' => $guide,
            'relatedGuides' => $relatedGuides,
        ]);
    }

    public function legacyCityQuery(Request $request): RedirectResponse
    {
        $city = trim((string) $request->query('city', ''));
        if ($city !== '') {
            return redirect()->route('seo.city', ['slug' => \Illuminate\Support\Str::slug($city)], 301);
        }

        return redirect()->route('cities', [], 301);
    }

    public function legal(string $page): View
    {
        $titles = [
            'privacy-policy' => 'Privacy Policy',
            'terms-and-conditions' => 'Terms & Conditions',
            'community-guidelines' => 'Community Guidelines',
            'safety-center' => 'Safety Center',
        ];

        abort_unless(isset($titles[$page]), 404);

        return view('pages.legal', [
            'page' => $page,
            'title' => $titles[$page],
        ]);
    }

    public function seoLanding(string $slug): View
    {
        $pages = [
            'dating-in-himachal-pradesh' => [
                'title' => 'Dating in Himachal Pradesh | Genuine Connections & Coffee Dates | CupDate',
                'h1' => 'Dating in Himachal Pradesh Built on Real Conversations',
                'description' => 'Meet verified singles and make genuine friends across Shimla, Dharamshala, Kangra, Mandi, Palampur, and Manali on CupDate.',
                'city' => null,
                'intro' => 'From quiet mountain cafés in McLeod Ganj to riverside evenings in Mandi and heritage walks in Shimla, CupDate brings thoughtful people across Himachal Pradesh together without endless random swiping.',
                'highlights' => ['Verified profiles across 10+ Himachal towns', 'Free messaging and encrypted WebRTC video calls', 'Daytime coffee-date planning in public cafés'],
                'ideas' => ['Afternoon pour-over along Mall Road, Shimla', 'Bookshop coffee in McLeod Ganj, Dharamshala', 'Tea-garden bakery meetup in Palampur'],
            ],
            'meet-singles-in-kangra' => [
                'title' => 'Meet Singles in Kangra | Safe Friendship & Coffee Dates | CupDate',
                'h1' => 'Meet Genuine Singles in Kangra Valley',
                'description' => 'Connect with respectful, verified people in Kangra, Baijnath, and Palampur for friendship, meaningful relationships, and coffee dates.',
                'city' => 'Kangra',
                'intro' => 'Kangra blends peaceful valley mornings, Dhauladhar views, and close-knit communities. CupDate helps you meet people nearby who value sincerity, conversation, and unhurried coffee meetups.',
                'highlights' => ['Local Kangra & Baijnath discovery filters', 'Strict zero-fake-profile community standards', 'Block, report, and privacy visibility controls'],
                'ideas' => ['Sunset tea or espresso near Bir Billing landing site', 'Weekend café meetup overlooking Kangra Fort', 'Quiet bookstore conversation in nearby Palampur'],
            ],
            'meet-singles-in-dharamshala' => [
                'title' => 'Meet Singles in Dharamshala & McLeod Ganj | CupDate',
                'h1' => 'Meet Singles in Dharamshala Over Great Coffee',
                'description' => 'Discover genuine people in Dharamshala and McLeod Ganj for coffee dates, mountain walks, and lasting connections on CupDate.',
                'city' => 'Dharamshala',
                'intro' => 'With its vibrant café culture, mountain trails, and creative community, Dharamshala is made for meaningful conversation. Meet locals and long-term residents who prefer real connection over superficial swiping.',
                'highlights' => ['Curated Dharamshala & McLeod Ganj café suggestions', 'Video call before meeting in person', 'Interest matching for trekking, books, and coffee'],
                'ideas' => ['Open-air balcony coffee at Illiterati Books & Cafe', 'Morning cappuccino in Naddi with Dhauladhar views', 'Midday bakery catch-up in Dharamshala town'],
            ],
            'meet-singles-in-mandi' => [
                'title' => 'Meet Singles in Mandi | Genuine Local Connections | CupDate',
                'h1' => 'Meet Genuine People in Mandi',
                'description' => 'Find verified local singles and friends in Mandi, Himachal Pradesh for respectful chat, video calls, and coffee dates.',
                'city' => 'Mandi',
                'intro' => 'Known for its Beas riverside charm, historic temples, and growing campus community, Mandi has plenty of warm places for a relaxed first coffee. CupDate keeps discovery local, safe, and authentic.',
                'highlights' => ['Mandi city & nearby valley filters', 'Free 1-to-1 chat and browser video calling', 'Safe daytime meetup guidance'],
                'ideas' => ['Evening coffee along Mandi Riverfront Road', 'Quiet café meetup near Seri Manch', 'Weekend coffee & photography walk by the Beas'],
            ],
            'coffee-date-ideas-in-himachal-pradesh' => [
                'title' => 'Coffee Date Ideas in Himachal Pradesh | CupDate Guide',
                'h1' => 'Thoughtful Coffee Date Ideas Across Himachal Pradesh',
                'description' => 'Explore safe, memorable first coffee date ideas in Shimla, Dharamshala, Kangra, Mandi, and Palampur with CupDate.',
                'city' => null,
                'intro' => 'A good first date should feel comfortable, safe, and unhurried. Here is how CupDate members turn an online match into a warm, respectful coffee meetup in Himachal.',
                'highlights' => ['Pick sunlit public cafés with comfortable seating', 'Start with a quick 10-minute video call first', 'Share your meetup city and preferred time cleanly inside CupDate'],
                'ideas' => ['Illiterati Books & Cafe - Jogibara Rd, Dharamshala', 'The Wake & Bake Cafe - The Mall, Shimla', 'Mandi Riverfront Cafe - Riverfront Rd, Mandi', 'Bir Billing Paragliding Cafe - Baijnath, Kangra'],
            ],
        ];

        abort_unless(isset($pages[$slug]), 404);
        $pageData = $pages[$slug];

        $membersQuery = User::query()
            ->where('status', 'active')
            ->where('profile_visible', true)
            ->where('is_admin', false)
            ->where('email', 'not like', '%@cupdate.in');

        if ($pageData['city']) {
            $membersQuery->where('city', $pageData['city']);
        } else {
            $membersQuery->where('state', 'Himachal Pradesh');
        }

        return view('pages.seo-landing', [
            'slug' => $slug,
            'page' => $pageData,
            'localMembers' => $membersQuery->take(4)->get(),
            'datePlaces' => DatePlace::orderByDesc('rating')->take(4)->get(),
        ]);
    }

    public function sitemap(\App\Services\SeoService $seo): Response
    {
        return response($seo->renderSitemapXml(), 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(\App\Services\SeoService $seo): Response
    {
        return response($seo->renderRobotsTxt(), 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
