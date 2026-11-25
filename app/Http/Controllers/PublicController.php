<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\City;
use App\Models\ContactMessage;
use App\Models\DatePlace;
use App\Models\InterestCategory;
use App\Models\SiteSetting;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(Request $request): View
    {
        $filter = $request->query('filter', 'nearby');
        $cityFilter = $request->query('city');
        $interestFilter = $request->query('interest');

        $query = User::query()
            ->where('status', 'active')
            ->where('profile_visible', true)
            ->where('is_admin', false)
            ->whereNotIn('role', User::ADMIN_ROLES);

        if ($cityFilter) {
            $query->where('city', $cityFilter);
        }

        if ($interestFilter) {
            $query->where('interests', 'like', '%' . $interestFilter . '%');
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

        $faqs = [
            ['q' => 'What is CupDate?', 'a' => 'CupDate (cupdate.in) is a genuine dating, friendship, and social connection platform built around real conversations and comfortable coffee dates across Himachal Pradesh and beyond.'],
            ['q' => 'Is CupDate free?', 'a' => 'Yes. Creating a profile, discovering people, sending chat messages, and making one-to-one video calls are completely free. Optional premium tools are never required to connect.'],
            ['q' => 'How can I find people nearby?', 'a' => 'Use the Discover filters by city (such as Dharamshala, Kangra, Mandi, or Shimla), shared interests, or online status without exposing your exact street location.'],
            ['q' => 'Can I video call someone?', 'a' => 'Yes. Once you connect with someone in Messages, you can start a secure, encrypted WebRTC video call directly in your browser with full mute and camera controls.'],
            ['q' => 'How does profile verification work?', 'a' => 'Verified members confirm their email and submit a profile verification request reviewed by our moderation team so you know you are talking to a real person.'],
            ['q' => 'Is CupDate safe?', 'a' => 'Safety comes first. CupDate includes profile verification, one-tap block and report tools, image moderation, strict anti-fake-profile policies, and human moderation.'],
            ['q' => 'How do I delete my account?', 'a' => 'You can permanently delete your account anytime from your Profile Settings page, which removes your profile from discovery immediately.'],
        ];

        return view('pages.home', [
            'featuredUsers' => $featuredUsers,
            'cities' => $cities,
            'interests' => $interests,
            'datePlaces' => $datePlaces,
            'activeFilter' => $filter,
            'activeCity' => $cityFilter,
            'activeInterest' => $interestFilter,
            'faqs' => $faqs,
            'headline' => SiteSetting::getValue('homepage_headline', 'Your Next Connection Starts With a Coffee.'),
            'subheadline' => SiteSetting::getValue('homepage_subheadline', 'Meet genuine people around you, discover new connections and turn simple conversations into beautiful memories.'),
            'coffeeHeading' => SiteSetting::getValue('coffee_section_heading', 'Less Swiping. More Meaningful Meetings.'),
        ]);
    }

    public function discover(Request $request): View
    {
        $query = User::query()
            ->where('status', 'active')
            ->where('profile_visible', true)
            ->where('is_admin', false)
            ->whereNotIn('role', User::ADMIN_ROLES);

        if ($city = $request->query('city')) {
            $query->where('city', $city);
        }
        if ($interest = $request->query('interest')) {
            $query->where('interests', 'like', '%' . $interest . '%');
        }
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%");
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

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:80'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'message' => $validated['message'],
            'status' => 'new',
            'ip_address' => $request->ip(),
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

    public function blog(): View
    {
        return view('pages.blog', [
            'posts' => BlogPost::where('is_published', true)->latest('published_at')->paginate(9),
        ]);
    }

    public function blogShow(string $slug): View
    {
        $post = BlogPost::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('pages.blog-show', ['post' => $post]);
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
                'title' => 'Dating in Himachal Pradesh — Genuine Connections & Coffee Dates | CupDate',
                'h1' => 'Dating in Himachal Pradesh Built on Real Conversations',
                'description' => 'Meet verified singles and make genuine friends across Shimla, Dharamshala, Kangra, Mandi, Palampur, and Manali on CupDate.',
                'city' => null,
                'intro' => 'From quiet mountain cafés in McLeod Ganj to riverside evenings in Mandi and heritage walks in Shimla, CupDate brings thoughtful people across Himachal Pradesh together without endless random swiping.',
                'highlights' => ['Verified profiles across 10+ Himachal towns', 'Free messaging and encrypted WebRTC video calls', 'Daytime coffee-date planning in public cafés'],
                'ideas' => ['Afternoon pour-over along Mall Road, Shimla', 'Bookshop coffee in McLeod Ganj, Dharamshala', 'Tea-garden bakery meetup in Palampur'],
            ],
            'meet-singles-in-kangra' => [
                'title' => 'Meet Singles in Kangra — Safe Friendship & Coffee Dates | CupDate',
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
                'title' => 'Meet Singles in Mandi — Genuine Local Connections | CupDate',
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
                'ideas' => ['Illiterati Books & Cafe — Jogibara Rd, Dharamshala', 'The Wake & Bake Cafe — The Mall, Shimla', 'Mandi Riverfront Cafe — Riverfront Rd, Mandi', 'Bir Billing Paragliding Cafe — Baijnath, Kangra'],
            ],
        ];

        abort_unless(isset($pages[$slug]), 404);
        $pageData = $pages[$slug];

        $membersQuery = User::query()
            ->where('status', 'active')
            ->where('profile_visible', true)
            ->where('is_admin', false);

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

    public function sitemap(): Response
    {
        $urls = [
            url('/'),
            url('/discover'),
            url('/how-it-works'),
            url('/safety'),
            url('/about-us'),
            url('/contact'),
            url('/blog'),
            url('/dating-in-himachal-pradesh'),
            url('/meet-singles-in-kangra'),
            url('/meet-singles-in-dharamshala'),
            url('/meet-singles-in-mandi'),
            url('/coffee-date-ideas-in-himachal-pradesh'),
        ];

        foreach (BlogPost::where('is_published', true)->pluck('slug') as $slug) {
            $urls[] = url('/blog/' . $slug);
        }

        $xml = view('pages.sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /app\nSitemap: " . url('/sitemap.xml') . "\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
