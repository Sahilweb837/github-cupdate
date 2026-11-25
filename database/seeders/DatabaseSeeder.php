<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\City;
use App\Models\InterestCategory;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@cupdate.in'],
            [
                'full_name' => 'CupDate Super Admin',
                'password_hash' => Hash::make('Admin@2026'),
                'email_verified_at' => now(),
                'dob' => '1995-05-15',
                'gender' => 'other',
                'preference' => 'everyone',
                'is_admin' => true,
                'role' => 'super_admin',
                'status' => 'active',
                'city' => 'Dharamshala',
                'state' => 'Himachal Pradesh',
                'coffee_preference' => 'Filter Coffee',
                'bio' => 'Platform administrator overseeing safety, moderation, and community trust.',
                'profile_visible' => false,
                'is_verified' => true,
                'verification_status' => 'verified',
                'is_online' => false,
            ]
        );

        $cities = [
            ['name' => 'Dharamshala', 'slug' => 'dharamshala', 'description' => 'Scenic mountain cafés in McLeod Ganj and Dharamshala valley.', 'sort_order' => 1],
            ['name' => 'Kangra', 'slug' => 'kangra', 'description' => 'Peaceful valley town with historic charm and warm local cafés.', 'sort_order' => 2],
            ['name' => 'Mandi', 'slug' => 'mandi', 'description' => 'Riverside meeting spots and vibrant student & professional community.', 'sort_order' => 3],
            ['name' => 'Shimla', 'slug' => 'shimla', 'description' => 'Classic Mall Road coffee houses and heritage hillside walks.', 'sort_order' => 4],
            ['name' => 'Palampur', 'slug' => 'palampur', 'description' => 'Tea gardens, quiet bakeries, and Dhauladhar views.', 'sort_order' => 5],
            ['name' => 'Manali', 'slug' => 'manali', 'description' => 'Old Manali riverfront cafés and mountain lifestyle connections.', 'sort_order' => 6],
            ['name' => 'Solan', 'slug' => 'solan', 'description' => 'Growing university hub and relaxed hillside coffee hangouts.', 'sort_order' => 7],
            ['name' => 'Kullu', 'slug' => 'kullu', 'description' => 'Valley connections and cozy local tea & coffee stops.', 'sort_order' => 8],
            ['name' => 'Hamirpur', 'slug' => 'hamirpur', 'description' => 'Friendly campus & town community in central Himachal.', 'sort_order' => 9],
            ['name' => 'Una', 'slug' => 'una', 'description' => 'Warm foothill community for genuine friendships and dates.', 'sort_order' => 10],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(
                ['slug' => $city['slug']],
                array_merge($city, ['state' => 'Himachal Pradesh', 'is_active' => true])
            );
        }

        $interests = [
            ['name' => 'Coffee Dates', 'slug' => 'coffee-dates'],
            ['name' => 'Trekking & Mountains', 'slug' => 'trekking-mountains'],
            ['name' => 'Books & Cafés', 'slug' => 'books-cafes'],
            ['name' => 'Photography', 'slug' => 'photography'],
            ['name' => 'Music & Acoustic', 'slug' => 'music-acoustic'],
            ['name' => 'Food & Baking', 'slug' => 'food-baking'],
            ['name' => 'Fitness & Wellness', 'slug' => 'fitness-wellness'],
            ['name' => 'Travel', 'slug' => 'travel'],
            ['name' => 'Art & Design', 'slug' => 'art-design'],
            ['name' => 'Technology', 'slug' => 'technology'],
        ];

        foreach ($interests as $interest) {
            InterestCategory::updateOrCreate(
                ['slug' => $interest['slug']],
                ['name' => $interest['name'], 'is_active' => true]
            );
        }

        $settings = [
            ['key' => 'homepage_headline', 'value' => 'Your Next Connection Starts With a Coffee.', 'group' => 'homepage'],
            ['key' => 'homepage_subheadline', 'value' => 'Meet genuine people around you, discover new connections and turn simple conversations into beautiful memories.', 'group' => 'homepage'],
            ['key' => 'coffee_section_heading', 'value' => 'Less Swiping. More Meaningful Meetings.', 'group' => 'homepage'],
            ['key' => 'seo_default_title', 'value' => 'CupDate — Meet Someone. Share a Coffee. Create a Connection.', 'group' => 'seo'],
            ['key' => 'seo_default_description', 'value' => 'CupDate (cupdate.in) is a safe, genuine dating and social connection platform in Himachal Pradesh for meaningful friendships, free chat, video calls, and coffee dates.', 'group' => 'seo'],
            ['key' => 'ads_enabled', 'value' => '0', 'group' => 'ads'],
            ['key' => 'ads_slot_html', 'value' => '', 'group' => 'ads'],
            ['key' => 'platform_guest_mode', 'value' => '1', 'group' => 'platform'],
            ['key' => 'platform_video_calls', 'value' => '1', 'group' => 'platform'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }

        BlogPost::updateOrCreate(
            ['slug' => 'best-first-coffee-date-spots-in-dharamshala-and-kangra'],
            [
                'title' => 'Best First Coffee Date Spots in Dharamshala & Kangra',
                'excerpt' => 'Quiet, sunlit cafés across Dharamshala, McLeod Ganj, and Kangra where conversations flow naturally over fresh coffee.',
                'body' => "A great first meeting does not need loud music or awkward formality. Across Dharamshala and Kangra, cozy hillside cafés offer open seating, warm mountain air, and relaxed daytime pacing.\n\nChoose a well-lit public café during late morning or afternoon hours, let a friend know your plans, and keep the focus on getting to know each other comfortably.",
                'cover_image' => 'images/cafe-blue-bottle.jpg',
                'author_id' => $admin->id,
                'meta_title' => 'Best First Coffee Date Spots in Dharamshala & Kangra | CupDate',
                'meta_description' => 'Discover safe, cozy café ideas in Dharamshala and Kangra for a meaningful first coffee date.',
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        BlogPost::updateOrCreate(
            ['slug' => 'how-to-build-a-genuine-cupdate-profile'],
            [
                'title' => 'How to Build a Genuine CupDate Profile That Sparks Real Conversation',
                'excerpt' => 'Simple tips on choosing natural daylight photos, sharing real interests, and starting respectful conversations.',
                'body' => "Genuine profiles attract genuine people. Start with a clear daylight photograph, write two honest sentences about what a relaxing weekend looks like for you, and select the Himachal cities and hobbies you truly enjoy.\n\nWhen you message someone new, reference a shared interest rather than a generic greeting—and when you both feel ready, suggest a casual daytime coffee or short introductory video call.",
                'cover_image' => 'images/hero-coffee-couple.jpg',
                'author_id' => $admin->id,
                'meta_title' => 'How to Build a Genuine CupDate Profile | CupDate',
                'meta_description' => 'Practical guide to creating an authentic, verified dating and friendship profile on CupDate.',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ]
        );
    }
}
