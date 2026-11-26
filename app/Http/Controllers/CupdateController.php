<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CupdateController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'stats' => [
                ['value' => '2.4M', 'label' => 'Active members'],
                ['value' => '310k', 'label' => 'Matches made'],
                ['value' => '92%', 'label' => 'Verified profiles'],
                ['value' => '4.8', 'label' => 'App rating'],
            ],
            'features' => [
                [
                    'icon' => 'radar',
                    'title' => 'Live Radar',
                    'description' => 'See compatible people nearby right now and say hi before the coffee gets cold.',
                ],
                [
                    'icon' => 'spark',
                    'title' => 'Smart updates',
                    'description' => 'Your match score refreshes as your interests, mood and habits change.',
                ],
                [
                    'icon' => 'shield',
                    'title' => 'Safe by design',
                    'description' => 'Photo verification, date check-ins and one-tap reporting keep things respectful.',
                ],
            ],
            'steps' => [
                ['number' => '1', 'title' => 'Create your profile', 'description' => "Add photos, prompts and what you're looking for."],
                ['number' => '2', 'title' => 'Meet your matches', 'description' => 'Daily picks and live radar curated for you.'],
                ['number' => '3', 'title' => 'Book the date', 'description' => 'Pick a spot, send an invite, show up.'],
            ],
            'stories' => [
                [
                    'quote' => '“We matched on a Tuesday, had coffee Thursday. Eight months later we’re moving in together.”',
                    'names' => 'Mei & Jonah',
                    'location' => 'Matched in Chicago',
                    'avatar' => 'images/member-yuki.jpg',
                ],
                [
                    'quote' => '“The radar feature is wild. Met my girlfriend three tables away at a bookstore café.”',
                    'names' => 'Omar & Lena',
                    'location' => 'Matched in Berlin',
                    'avatar' => 'images/member-diego.jpg',
                ],
            ],
        ]);
    }

    public function howItWorks(): View
    {
        return view('pages.how-it-works', [
            'faqs' => [
                [
                    'q' => '1. Is Cupdate free?',
                    'a' => 'Yes. Joining, matching and messaging are free. Premium adds unlimited radar checks and profile boosts.',
                ],
                [
                    'q' => '2. How does Radar work?',
                    'a' => 'Radar shows opt-in members within your chosen café radius. Your exact location is never shared-only approximate neighborhood proximity.',
                ],
                [
                    'q' => '3. How do you keep me safe?',
                    'a' => 'Every profile goes through selfie verification, optional date check-ins with trusted contacts, and 24/7 human moderation.',
                ],
            ],
        ]);
    }

    public function about(): View
    {
        return view('pages.about', [
            'metrics' => [
                ['value' => '2021', 'label' => 'Founded in Portland'],
                ['value' => '40+', 'label' => 'Countries with real dates'],
                ['value' => '1.1M', 'label' => 'First dates booked'],
            ],
            'values' => [
                ['icon' => 'user', 'title' => 'Real people', 'description' => 'Every profile is verified. No bots, no catfish.'],
                ['icon' => 'heart', 'title' => 'Kindness first', 'description' => 'We design for respect, and we act on reports fast.'],
                ['icon' => 'coffee', 'title' => 'Meet in person', 'description' => 'Success is a great date, not screen time.'],
            ],
            'team' => [
                ['name' => 'Amara Okafor', 'role' => 'CEO & Co-founder', 'photo' => 'images/member-amara.jpg'],
                ['name' => 'Ben Carter', 'role' => 'CTO & Co-founder', 'photo' => 'images/member-ben.jpg'],
                ['name' => 'Yuki Tanaka', 'role' => 'Head of Design', 'photo' => 'images/member-yuki.jpg'],
                ['name' => 'Diego Ramos', 'role' => 'Head of Safety', 'photo' => 'images/member-diego.jpg'],
            ],
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'topic' => ['required', 'string', 'max:80'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        return back()->with('contact_success', "Thanks {$validated['name']}! Your message about \"{$validated['topic']}\" is on its way to our team.");
    }

    public function login(): View
    {
        return view('pages.auth', ['mode' => 'login']);
    }

    public function join(): View
    {
        return view('pages.auth', ['mode' => 'join']);
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $mode = $request->input('mode', 'login');

        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:4'],
        ];

        if ($mode === 'join') {
            $rules['name'] = ['required', 'string', 'max:80'];
            $rules['city'] = ['nullable', 'string', 'max:80'];
        }

        $data = $request->validate($rules);

        $request->session()->put('cupdate_user', [
            'name' => $data['name'] ?? 'Sofia',
            'email' => $data['email'],
            'city' => $data['city'] ?? 'Portland, OR',
            'avatar' => 'images/member-amara.jpg',
        ]);

        return redirect()->route('app.discover')->with('welcome', 'Welcome back to Cupdate!');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('cupdate_user');
        return redirect()->route('home');
    }

    public function legal(string $section): View
    {
        $titles = [
            'privacy' => 'Privacy Policy',
            'terms' => 'Terms of Service',
            'safety' => 'Safety Center',
        ];

        abort_unless(isset($titles[$section]), 404);

        return view('pages.legal', [
            'section' => $section,
            'title' => $titles[$section],
        ]);
    }

    public function discover(Request $request): View
    {
        $user = $this->currentUser($request);

        return view('app.discover', [
            'user' => $user,
            'picks' => [
                [
                    'id' => 1,
                    'name' => 'Diego, 29',
                    'city' => '0.4 mi away · Pearl District',
                    'match' => 94,
                    'prompt' => 'Best Sunday morning: pour-over at Heart & a long bookstore wander.',
                    'tags' => ['Specialty coffee', 'Indie films', 'Hiking'],
                    'photo' => 'images/member-diego.jpg',
                ],
                [
                    'id' => 2,
                    'name' => 'Ben, 31',
                    'city' => '0.8 mi away · Nob Hill',
                    'match' => 91,
                    'prompt' => 'Looking for someone who debates croissant flakiness seriously.',
                    'tags' => ['Architecture', 'Cycling', 'Oat cortado'],
                    'photo' => 'images/member-ben.jpg',
                ],
                [
                    'id' => 3,
                    'name' => 'Yuki, 28',
                    'city' => '1.1 mi away · Hawthorne',
                    'match' => 89,
                    'prompt' => 'Currently reading essays over iced matcha on rainy afternoons.',
                    'tags' => ['Ceramics', 'Design', 'Matcha'],
                    'photo' => 'images/member-yuki.jpg',
                ],
            ],
        ]);
    }

    public function radar(Request $request): View
    {
        return view('app.radar', [
            'user' => $this->currentUser($request),
            'nearby' => [
                ['name' => 'Diego, 29', 'spot' => 'Blue Bottle Coffee', 'distance' => '180m away', 'match' => 94, 'avatar' => 'images/member-diego.jpg', 'x' => 64, 'y' => 30],
                ['name' => 'Yuki, 28', 'spot' => "Powell's City of Books", 'distance' => '320m away', 'match' => 89, 'avatar' => 'images/member-yuki.jpg', 'x' => 32, 'y' => 42],
                ['name' => 'Ben, 31', 'spot' => 'Coava Coffee Roasters', 'distance' => '540m away', 'match' => 91, 'avatar' => 'images/member-ben.jpg', 'x' => 70, 'y' => 66],
                ['name' => 'Amara, 27', 'spot' => 'Stumptown Roasters', 'distance' => '680m away', 'match' => 87, 'avatar' => 'images/member-amara.jpg', 'x' => 28, 'y' => 72],
            ],
        ]);
    }

    public function messages(Request $request): View
    {
        return view('app.messages', [
            'user' => $this->currentUser($request),
            'threads' => [
                [
                    'id' => 'diego',
                    'name' => 'Diego',
                    'status' => '94% match · Online now',
                    'avatar' => 'images/member-diego.jpg',
                    'preview' => 'Saturday at 4 works! Blue Bottle?',
                    'messages' => [
                        ['from' => 'them', 'text' => 'Hey Sofia! Saw you also love pour-over mornings.', 'time' => '10:14 AM'],
                        ['from' => 'me', 'text' => 'Always! Have you tried the new single-origin at Blue Bottle?', 'time' => '10:18 AM'],
                        ['from' => 'them', 'text' => 'Not yet-coffee this weekend?', 'time' => '10:21 AM'],
                        ['from' => 'me', 'text' => 'Saturday at 4 works!', 'time' => '10:24 AM'],
                    ],
                ],
                [
                    'id' => 'ben',
                    'name' => 'Ben',
                    'status' => '91% match · Active 12m ago',
                    'avatar' => 'images/member-ben.jpg',
                    'preview' => 'That croissant spot on 23rd is elite.',
                    'messages' => [
                        ['from' => 'them', 'text' => 'That croissant spot on 23rd is elite.', 'time' => 'Yesterday'],
                        ['from' => 'me', 'text' => 'Added to my Saturday list immediately!', 'time' => 'Yesterday'],
                    ],
                ],
                [
                    'id' => 'yuki',
                    'name' => 'Yuki',
                    'status' => '89% match · Active 1h ago',
                    'avatar' => 'images/member-yuki.jpg',
                    'preview' => 'How was the ceramics market?',
                    'messages' => [
                        ['from' => 'them', 'text' => 'How was the ceramics market?', 'time' => 'Tue'],
                    ],
                ],
            ],
        ]);
    }

    public function dates(Request $request): View
    {
        return view('app.dates', [
            'user' => $this->currentUser($request),
            'upcoming' => [
                [
                    'with' => 'Diego, 29',
                    'venue' => 'Blue Bottle Coffee · Pearl District',
                    'when' => 'Sat, Nov 28 · 4:00 PM',
                    'status' => 'Confirmed',
                    'avatar' => 'images/member-diego.jpg',
                ],
                [
                    'with' => 'Ben, 31',
                    'venue' => 'Coava Coffee Roasters',
                    'when' => 'Wed, Dec 2 · 5:30 PM',
                    'status' => 'Invite sent',
                    'avatar' => 'images/member-ben.jpg',
                ],
            ],
        ]);
    }

    public function profile(Request $request): View
    {
        return view('app.profile', [
            'user' => $this->currentUser($request),
        ]);
    }

    private function currentUser(Request $request): array
    {
        return $request->session()->get('cupdate_user', [
            'name' => 'Sofia',
            'email' => 'sofia@cupdate.app',
            'city' => 'Portland, OR',
            'avatar' => 'images/member-amara.jpg',
        ]);
    }
}
