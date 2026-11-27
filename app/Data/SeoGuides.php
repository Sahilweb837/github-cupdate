<?php

namespace App\Data;

class SeoGuides
{
    /**
     * Rich, schema-ready SEO guides and legacy .php / blog pages (eliminates 404 & Soft 404s).
     *
     * @return array<string, array{
     *   slug: string,
     *   title: string,
     *   h1: string,
     *   description: string,
     *   category: string,
     *   image: string,
     *   paragraphs: array<int, string>,
     *   checklist: array<int, string>,
     *   faqs: array<int, array{q: string, a: string}>
     * }>
     */
    public static function all(): array
    {
        $items = [
            'best-himachal-dating-website' => [
                'slug' => 'best-himachal-dating-website',
                'title' => 'Best Himachal Dating Website 2026 - Shimla, Dharamshala, Kangra & Mandi | CupDate',
                'h1' => 'Best Free Himachal Pradesh Dating & Coffee Meetup Website',
                'description' => 'Discover why CupDate (cupdate.in) is Himachal Pradesh’s best 100% free dating, friendship, and CupTV live video website across Shimla, Dharamshala, Kangra, Mandi, Manali, and Palampur.',
                'category' => 'Himachal Pradesh SEO Guide',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Finding genuine people in Himachal Pradesh shouldn’t require expensive subscriptions or fake swipe decks. CupDate connects verified locals, students, remote creators, and travelers across Shimla, Dharamshala, McLeod Ganj, Kangra, Mandi, Manali, Palampur, Solan, Kullu, Hamirpur, and Una - completely free.',
                    'Unlike generic apps, CupDate focuses on real conversations first: free 1-to-1 encrypted chat, instant CupTV live video matching, women-friendly café reviews, and shareable Coffee Moments protected by automated anti-nudity and anti-spam moderation.',
                    'Whether you want a quiet afternoon cappuccino along Shimla’s Mall Road, a bookshop meetup in McLeod Ganj, or a riverside coffee in Mandi, CupDate helps you connect safely before meeting in person.',
                ],
                'checklist' => [
                    '100% free chat, CupTV live video & coffee date planning - zero paid paywalls',
                    'Dedicated local guides for 15+ Himachal towns including Shimla, Kangra, Dharamshala, Mandi & Manali',
                    'Verified member badges, anti-nudity screening & instant block/report tools',
                    'Women-reviewed safe daytime cafés across Himachal Pradesh',
                ],
                'faqs' => [
                    ['q' => 'Which is the best free dating website in Himachal Pradesh?', 'a' => 'CupDate (cupdate.in) is built specifically for genuine, safe, 100% free coffee dating and live video conversations across Shimla, Dharamshala, Kangra, Mandi, Manali, and all Himachal districts.'],
                    ['q' => 'Do I need a paid plan to message people in Himachal?', 'a' => 'No. Messaging, CupTV live video, profile discovery, and café reviews are 100% free forever on CupDate.'],
                ],
            ],
            'best-delhi-dating-website' => [
                'slug' => 'best-delhi-dating-website',
                'title' => 'Best Free Delhi & NCR Dating Website - New Delhi, Noida & Gurugram | CupDate',
                'h1' => 'Best Free Dating & Coffee Meetup Website in Delhi NCR',
                'description' => 'Meet verified singles in New Delhi, Noida, Gurugram, Faridabad, and Ghaziabad for free coffee dates, respectful chat, and CupTV live video on CupDate.',
                'category' => 'Delhi NCR Guide',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Delhi NCR is one of India’s liveliest cities for specialty coffee, book cafés, and creative meetups - from Connaught Place, Khan Market, and Hauz Khas Village to CyberHub in Gurugram and Sector 18 in Noida.',
                    'CupDate gives Delhi NCR singles a refreshing alternative to paywalled dating apps: every member gets free messaging, CupTV live video face-to-face intros, and access to women-reviewed safe cafés before stepping out.',
                    'Every profile, chat message, and Coffee Moment goes through automated anti-spam and anti-nudity screening so your Delhi dating experience stays respectful and authentic.',
                ],
                'checklist' => [
                    'Free local discovery across New Delhi, Gurugram, Noida, Faridabad & Ghaziabad',
                    'Screen connections with a quick 5-minute CupTV live video call first',
                    'Pick bright daytime cafés reviewed for women’s safety and comfort',
                    'Zero hidden fees or pay-to-reply locks',
                ],
                'faqs' => [
                    ['q' => 'Is CupDate free for Delhi NCR users?', 'a' => 'Yes. Creating a profile, chatting, using CupTV live video, and planning coffee dates in Delhi, Noida, and Gurugram is 100% free.'],
                    ['q' => 'What are safe first coffee date spots in Delhi?', 'a' => 'Daytime specialty cafés in Khan Market, Connaught Place, Majnu Ka Tila, CyberHub, and Galleria Market are popular public spots.'],
                ],
            ],
            'dating-pune' => [
                'slug' => 'dating-pune',
                'title' => 'Dating in Pune - Best Free Coffee Dates & Local Singles | CupDate',
                'h1' => 'Dating in Pune: Cozy Cafés, Real Conversations & Free Live Video',
                'description' => 'Meet genuine singles in Pune across Koregaon Park, FC Road, Baner, Viman Nagar, and Kothrud for free coffee dates and safe CupTV video chats.',
                'category' => 'City Dating Guide',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Pune’s leafy lanes in Koregaon Park, bustling student cafés on FC Road, and modern roasteries in Baner and Viman Nagar make it one of India’s best cities for relaxed coffee dating.',
                    'On CupDate, Pune members connect around shared hobbies - trekking to Sinhagad, live acoustic nights, books, tech, and specialty brews - with 100% free chat and CupTV video calls.',
                ],
                'checklist' => [
                    'Browse verified Pune profiles by neighborhood and shared interests',
                    'Read women-friendly Pune café reviews before planning a meetup',
                    'Free messaging and video intros without paid subscriptions',
                ],
                'faqs' => [
                    ['q' => 'How can I meet singles in Pune for free?', 'a' => 'Join CupDate free, filter Discover by Pune, share a Coffee Moment, or hop on CupTV live video to meet verified people nearby.'],
                ],
            ],
            'haryana-dating-guide' => [
                'slug' => 'haryana-dating-guide',
                'title' => 'Haryana Dating Guide - Gurugram, Chandigarh, Panchkula, Rohtak & Karnal | CupDate',
                'h1' => 'Complete Haryana Dating & Safe Coffee Meetup Guide',
                'description' => 'Connect safely with verified singles across Gurugram, Faridabad, Panchkula, Ambala, Karnal, Panipat, Rohtak, Hisar, and Sonipat on CupDate.',
                'category' => 'State Dating Guide',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Across Gurugram’s modern cafés, Panchkula’s quiet parks, and university hubs like Kurukshetra, Rohtak, and Sonipat, young professionals and students in Haryana are choosing intentional, respectful conversations over noisy apps.',
                    'CupDate offers dedicated city guides across Haryana with zero paid paywalls, verified profiles, and strict anti-spam and anti-nudity protection.',
                ],
                'checklist' => [
                    'Covers Gurugram, Faridabad, Panchkula, Ambala, Karnal, Panipat, Rohtak, Hisar & Kurukshetra',
                    '100% free chat and encrypted 1-to-1 video calling',
                    'One-tap block, report, and privacy visibility controls',
                ],
                'faqs' => [
                    ['q' => 'Which Haryana cities are supported on CupDate?', 'a' => 'All major Haryana cities including Gurugram, Faridabad, Panchkula, Ambala, Karnal, Panipat, Rohtak, Hisar, Sonipat, Kurukshetra, Rewari, Bhiwani, and Sirsa.'],
                ],
            ],
            'women-dating-safety-guide-india' => [
                'slug' => 'women-dating-safety-guide-india',
                'title' => 'Women’s Dating Safety Guide in India - Safe First Dates & Online Privacy | CupDate',
                'h1' => 'Women’s Dating Safety Guide in India',
                'description' => 'Essential online and in-person safety tips for women dating in India: verifying profiles, avoiding scams, picking safe cafés, and protecting personal privacy.',
                'category' => 'Safety Guide',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Your comfort and safety always come first. Before sharing your phone number or meeting someone new in India, keep all early chats and video calls inside CupDate so you remain protected by our moderation, block, and report systems.',
                    'Always choose well-lit public cafés during daytime hours, share your live location with a trusted friend, arrange your own transport, and check our community Café Reviews written by other women.',
                ],
                'checklist' => [
                    'Do a quick CupTV or 1-to-1 video call before meeting in person',
                    'Never share OTPs, banking details, or private home addresses',
                    'Meet in busy, daylight cafés and read women-friendly Café Reviews',
                    'Report any spam, solicitation, or fake profile immediately',
                ],
                'faqs' => [
                    ['q' => 'How does CupDate protect women from nudity and spam?', 'a' => 'CupDate screens messages, photos, and moments automatically for nudity solicitation and spam links, backed by human moderators and one-tap blocking.'],
                ],
            ],
            'how-to-impress-on-a-first-date' => [
                'slug' => 'how-to-impress-on-a-first-date',
                'title' => 'How to Impress on a First Coffee Date - Thoughtful Conversation Tips | CupDate',
                'h1' => 'How to Make a Great Impression on Your First Coffee Date',
                'description' => 'Practical, authentic tips for a relaxed and memorable first coffee date: listening well, picking the right café, keeping conversation natural, and being respectful.',
                'category' => 'Dating Tips',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Great first dates aren’t about flashy gestures - they’re about showing up on time, choosing a comfortable café where you can actually hear each other, and being genuinely curious about the other person.',
                    'Put your phone face-down, ask open-ended questions about their favorite travel memory or weekend routine, and keep the first coffee date relaxed at 45–60 minutes so there’s zero pressure.',
                ],
                'checklist' => [
                    'Arrive 5 minutes early and confirm the café table is comfortable',
                    'Listen actively and ask thoughtful follow-up questions',
                    'Respect boundaries and let the conversation flow naturally',
                ],
                'faqs' => [
                    ['q' => 'Why is a coffee date ideal for a first meetup?', 'a' => 'Coffee dates are daytime, public, affordable, and low-pressure - making it easy for both people to relax and talk openly.'],
                ],
            ],
            'how-to-report-suspicious-profile' => [
                'slug' => 'how-to-report-suspicious-profile',
                'title' => 'How to Spot & Report a Suspicious Profile Safely | CupDate Safety',
                'h1' => 'How to Spot and Report a Suspicious Profile',
                'description' => 'Learn the warning signs of fake or spam profiles and how to block and report suspicious accounts in one tap on CupDate.',
                'category' => 'Safety Guide',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'If someone immediately asks to move off-platform to Telegram/WhatsApp, avoids a live CupTV video hello, sends external spam links, or asks for money/crypto, stop engaging right away.',
                    'On CupDate, every chat, profile, and CupTV live video room includes a one-tap Report & Block button that immediately severs contact and sends a snapshot to our moderation team.',
                ],
                'checklist' => [
                    'Watch for urgency, financial requests, or refusal to video chat',
                    'Use Report & Block inside Messages or Live Video immediately',
                    'Never send money, gift cards, or verification codes to anyone online',
                ],
                'faqs' => [
                    ['q' => 'What happens when I report a profile on CupDate?', 'a' => 'The reported account is immediately queued for our Moderator team, and blocking prevents them from contacting or seeing you again.'],
                ],
            ],
            'mbti-personality-dating-compatibility' => [
                'slug' => 'mbti-personality-dating-compatibility',
                'title' => 'MBTI Personality & Coffee Dating Compatibility Guide | CupDate',
                'h1' => 'MBTI Personality & Dating Compatibility Explained',
                'description' => 'Explore how personality types (ENFP, INFJ, INTJ, ESFP, and more) shape conversation styles and coffee date chemistry on CupDate.',
                'category' => 'Compatibility Guide',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Understanding communication styles - whether someone recharges in quiet one-on-one café chats (I) or lively group conversations (E) - helps turn awkward small talk into effortless chemistry.',
                    'CupDate combines city proximity, shared hobbies, coffee preferences, and personality cues to help you spark better conversations.',
                ],
                'checklist' => [
                    'Share your authentic weekend vibe and coffee style on your profile',
                    'Use interest tags to find people who enjoy similar conversations',
                    'Remember compatibility grows through kindness and curiosity, not labels',
                ],
                'faqs' => [
                    ['q' => 'Does MBTI decide who I can match with on CupDate?', 'a' => 'No - it’s simply a fun conversation starter alongside shared cities, interests, and real-time chat.'],
                ],
            ],
            'tips-red-flags' => [
                'slug' => 'tips-red-flags',
                'title' => 'Online Dating Red Flags to Watch Out For | CupDate Safety Tips',
                'h1' => '10 Online Dating Red Flags & How to Stay Safe',
                'description' => 'Protect yourself while dating online in India and worldwide: recognize love-bombing, fake profiles, boundary-pushing, and spam links early.',
                'category' => 'Safety Tips',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Healthy connections feel calm, consistent, and respectful. Watch out for anyone who pressures you for private photos, gets angry when you set a boundary, or tries to rush you off CupDate within minutes.',
                    'Trust your instincts: if a conversation feels off, block and report with one tap and keep meeting people who value genuine respect.',
                ],
                'checklist' => [
                    'Refusing a quick video hello while pushing for an isolated meetup',
                    'Asking for personal financial help or crypto links',
                    'Ignoring your boundaries or sending unsolicited explicit messages',
                ],
                'faqs' => [
                    ['q' => 'How does CupDate handle explicit or disrespectful messages?', 'a' => 'Our automated SafetyModerationService blocks nudity solicitation and spam floods automatically and flags suspicious links for human review.'],
                ],
            ],
            'faq' => [
                'slug' => 'faq',
                'title' => 'Frequently Asked Questions (FAQ) - Free Coffee & CupTV Dating | CupDate',
                'h1' => 'CupDate Frequently Asked Questions',
                'description' => 'Answers to common questions about CupDate: 100% free access, CupTV live video chat, women-friendly café reviews, 200+ cities, and anti-nudity safety.',
                'category' => 'Help & FAQ',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'CupDate (cupdate.in) is built to be the world’s warmest 100% free coffee dating and CupTV live video community - with zero hidden paywalls, real active visitor transparency, and dedicated safety protection.',
                ],
                'checklist' => [
                    '100% Free Forever: signup, messaging, CupTV live video, Moments & Café Reviews',
                    '200+ Indian & global city guides indexed for easy local discovery',
                    'Strict zero-nudity, zero-spam policy with role-based moderation',
                ],
                'faqs' => [
                    ['q' => 'Is CupDate completely free?', 'a' => 'Yes. There is no paid subscription required to message, video call, join CupTV, share moments, or review cafés.'],
                    ['q' => 'How do I delete my account or data?', 'a' => 'Sign in and visit Profile Settings to permanently delete your account anytime.'],
                ],
            ],
            'disclaimer' => [
                'slug' => 'disclaimer',
                'title' => 'Platform Disclaimer & Safety Notice | CupDate',
                'h1' => 'CupDate Platform Disclaimer',
                'description' => 'Important platform disclaimer, user responsibility guidelines, and safety standards for CupDate (cupdate.in).',
                'category' => 'Legal & Safety',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'CupDate provides an online social discovery, messaging, live video, and café review platform for adults aged 18 and older. While we actively screen content against nudity, spam, and abuse and provide verification badges, users remain responsible for exercising good judgment during offline meetups.',
                    'Always meet in public places during daytime hours, avoid sharing financial or sensitive personal details, and report any suspicious activity to our moderation team.',
                ],
                'checklist' => [
                    'Strictly 18+ community',
                    'Zero tolerance for nudity, harassment, commercial solicitation, or impersonation',
                    'Public daytime café meetups recommended for first meetings',
                ],
                'faqs' => [
                    ['q' => 'Who can join CupDate?', 'a' => 'Adults aged 18+ seeking respectful friendships, coffee dates, and genuine conversations.'],
                ],
            ],
            'rishta' => [
                'slug' => 'rishta',
                'title' => 'Genuine Rishta, Meaningful Relationships & Serious Matchmaking | CupDate India',
                'h1' => 'Serious Relationships, Rishta & Meaningful Connections in India',
                'description' => 'Looking for a serious relationship or respectful rishta introduction? Meet family-oriented, verified singles across India over honest conversations and coffee.',
                'category' => 'Serious Relationships',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Not everyone wants casual swiping. Many members on CupDate across Himachal Pradesh, Punjab, Delhi, Chandigarh, Maharashtra, and South India are looking for respectful, long-term companionship and marriage-minded introductions.',
                    'Take time to chat calmly, meet on CupTV or 1-to-1 video calls, and arrange a comfortable daytime café introduction with verified members.',
                ],
                'checklist' => [
                    'City, state, and interest-based discovery across 200+ Indian cities',
                    'Verified profiles and respectful long-form conversations',
                    '100% free - no pricey matrimonial paywalls',
                ],
                'faqs' => [
                    ['q' => 'Is CupDate good for serious relationships in India?', 'a' => 'Yes. CupDate prioritizes genuine profiles, calm conversations, and public café introductions rather than superficial swiping.'],
                ],
            ],
            'community-lgbtq' => [
                'slug' => 'community-lgbtq',
                'title' => 'Inclusive & Safe LGBTQ+ Dating & Friendship Community | CupDate',
                'h1' => 'Respectful, Safe & Inclusive Community for Everyone',
                'description' => 'CupDate welcomes everyone with preference controls, strict anti-harassment moderation, privacy settings, and safe coffee meetup spaces.',
                'category' => 'Community',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Everyone deserves a safe, respectful space to build genuine friendships and romantic connections. CupDate lets you set your own discovery preferences, control profile visibility, and connect without fear of harassment or spam.',
                    'Our moderation team enforces zero tolerance for hate speech, outing, harassment, or unsolicited explicit content.',
                ],
                'checklist' => [
                    'Inclusive gender & preference discovery options',
                    'Strict anti-harassment & instant block/report protection',
                    '100% free messaging, video calls & safe café reviews',
                ],
                'faqs' => [
                    ['q' => 'Can I control who discovers my profile on CupDate?', 'a' => 'Yes. You can configure discovery preferences and toggle profile visibility anytime in Profile Settings.'],
                ],
            ],
            'icebreaker-questions' => [
                'slug' => 'icebreaker-questions',
                'title' => 'Best Coffee Date Icebreaker Questions for First Dates & Video Chats | CupDate',
                'h1' => 'Best Icebreaker Questions for First Coffee Dates & CupTV Video',
                'description' => 'Break the ice naturally on your first coffee date or live video chat with thoughtful, respectful, and fun conversation starters.',
                'category' => 'Conversation Guides',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Good conversations rarely start with generic small talk. Asking about memorable travel detours, favorite weekend café spots, or books someone actually finished creates natural warmth from the first minute.',
                    'Pair these prompts with our free interactive Truth or Dare card deck or try one during a 5-minute CupTV Live Video introduction.',
                ],
                'checklist' => [
                    'Warm open-ended questions for relaxed first coffee meetups',
                    'Respectful prompts suited for couples, friends, and video calls',
                    'Works alongside our free Truth or Dare game on CupDate',
                ],
                'faqs' => [
                    ['q' => 'What is a great icebreaker question for a first coffee date?', 'a' => 'Ask about their ideal Sunday morning neighborhood walk, a small café they always recommend, or the next place on their India travel wish list.'],
                ],
            ],
            'weekend-meetups' => [
                'slug' => 'weekend-meetups',
                'title' => 'Weekend Coffee Meetups, Book Circles & Social Clubs in India | CupDate',
                'h1' => 'Weekend Coffee Meetups, Book Circles & Local Hangouts',
                'description' => 'Discover weekend coffee meetups, book discussions, photography walks, and travel groups across Shimla, Delhi, Chandigarh, Bengaluru, Pune, and Mumbai.',
                'category' => 'Community Meetups',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Meeting new people feels far easier in relaxed daytime settings built around shared interests. CupDate highlights coffee circles, book swaps, and outdoor morning walks across India.',
                    'Start by chatting free on CupDate, check café reviews for comfortable daylight seating, or join a weekend travel group.',
                ],
                'checklist' => [
                    'Daytime public cafés vetted by member reviews',
                    'City filters for Himachal, Delhi NCR, Chandigarh, Pune, Mumbai, and Bengaluru',
                    'Zero subscription fees to organize or join local meetups',
                ],
                'faqs' => [
                    ['q' => 'Are weekend coffee meetups free on CupDate?', 'a' => 'Yes. Discovering people, planning coffee dates, and exploring travel or book groups on CupDate costs nothing.'],
                ],
            ],
            'speed-dating' => [
                'slug' => 'speed-dating',
                'title' => 'Free Online Speed Dating & CupTV 5-Minute Video Chats | CupDate',
                'h1' => 'Free Live Video Speed Dating on CupTV',
                'description' => 'Try safe, 100% free live video speed dating from your browser with CupTV. Meet verified singles across India and worldwide face-to-face before meeting in person.',
                'category' => 'Live Video',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Traditional dating apps trap people in days of dry texting. CupDate brings fast, safe face-to-face chemistry checks to your phone and desktop via encrypted WebRTC live video.',
                    'Choose your city or region preference, start a respectful 5-minute video hello, and tap Next anytime.',
                ],
                'checklist' => [
                    'Instant browser WebRTC video with zero paid tokens',
                    'Filter by region and discovery preference',
                    'Built-in one-tap reporting and safety moderation',
                ],
                'faqs' => [
                    ['q' => 'Do I need to pay for live video speed dating?', 'a' => 'No. CupTV live video and 1-to-1 video calls are completely free for all CupDate members.'],
                ],
            ],
            'couple-activities' => [
                'slug' => 'couple-activities',
                'title' => 'Best Public Daytime Date Activities & Couple Ideas in India | CupDate',
                'h1' => 'Thoughtful Daytime Couple Activities & Date Ideas Across India',
                'description' => 'Explore safe, creative daytime date ideas including botanical walks, bookstore cafés, pottery workshops, museum visits, and coffee tastings across India.',
                'category' => 'Date Ideas',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Memorable dates do not require noisy clubs or expensive bookings. Shared experiences like specialty pour-over tastings, independent bookstore browsing, morning botanical trails, or weekend art galleries encourage easy conversation.',
                    'Browse verified café reviews and share your favorite moments safely on CupDate.',
                ],
                'checklist' => [
                    'Daytime public spaces focused on safety and comfort',
                    'City-by-city café reviews written by real members',
                    'Play Truth or Dare icebreakers together to keep conversation lively',
                ],
                'faqs' => [
                    ['q' => 'What are the safest first date activities in India?', 'a' => 'Daytime coffee shop meetups, busy neighborhood bookstores, art museums, and public park walks are safe, comfortable, and low-pressure.'],
                ],
            ],
            'college-dating-india' => [
                'slug' => 'college-dating-india',
                'title' => 'Safe College & Campus Coffee Dating Across Indian University Cities | CupDate',
                'h1' => 'Respectful College & Young Adult Meetups in India',
                'description' => 'Meet genuine 18+ college students and young professionals in Chandigarh, Delhi University, Pune, Manipal, Roorkee, Shimla, and Bengaluru for free coffee dates.',
                'category' => 'Student & Youth Guides',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Campus towns across India thrive on neighborhood coffeehouses, evening chai stalls, and weekend study groups. CupDate helps adults aged 18+ connect safely without fake profiles or paywalls.',
                    'Every account supports interest tags, privacy toggles, instant blocking, and moderation review.',
                ],
                'checklist' => [
                    'Strictly 18+ community with strict anti-spam moderation',
                    '100% free messaging and live video calling for students',
                    'Public campus-area café suggestions',
                ],
                'faqs' => [
                    ['q' => 'Is CupDate strictly for adults 18 and older?', 'a' => 'Yes. Age verification requires members to be at least 18 years old at registration.'],
                ],
            ],
            'solo-travel-companions' => [
                'slug' => 'solo-travel-companions',
                'title' => 'Find Safe Coffee & Solo Travel Companions in India | CupDate Travel Groups',
                'h1' => 'Solo Travel Companions & Safe Road Trip Coffee Meetups',
                'description' => 'Traveling solo to Himachal Pradesh, Spiti, Rishikesh, Goa, or Jaipur? Meet verified fellow travelers over daytime coffee or join open weekend travel groups on CupDate.',
                'category' => 'Travel & Meetups',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Exploring new hill stations or coastal cities solo is rewarding, and sharing a sunset coffee with fellow travelers makes trips even better. CupDate connects travelers and locals across 200+ cities.',
                    'Start on a CupTV video call first, review local cafés, and always pick sunlit public meetup spots.',
                ],
                'checklist' => [
                    'Curated routes across Dharamshala, Manali, Spiti, Ladakh, Goa, and Rishikesh',
                    'Verify profiles via video chat before meeting on the road',
                    'Join group travel discussions free of cost',
                ],
                'faqs' => [
                    ['q' => 'How do I safely meet travel companions on CupDate?', 'a' => 'Use 1-to-1 chat and a brief video call beforehand, always meet in public cafés first, and share trip plans with someone you trust.'],
                ],
            ],
            'dating-etiquette-india' => [
                'slug' => 'dating-etiquette-india',
                'title' => 'Modern Dating Etiquette & Respectful First Meetup Guide in India | CupDate',
                'h1' => 'Respectful Dating Etiquette in India: From First Chat to Coffee Date',
                'description' => 'Practical guide to respectful texting, split-bill etiquette, punctuality, privacy boundaries, and honest communication while dating in India.',
                'category' => 'Relationship Advice',
                'image' => 'images/cupdate-scene.svg',
                'paragraphs' => [
                    'Respectful communication sets CupDate apart. Clear intentions, respecting personal pace, confirming public daytime venues beforehand, and never pressuring someone for personal numbers early build lasting trust.',
                    'Whether you are looking for friendship, serious matchmaking, or casual coffee company, sincerity always stands out.',
                ],
                'checklist' => [
                    'Listen actively and keep first-date venues comfortable and public',
                    'Offer to split coffee or alternate without awkwardness',
                    'Respect boundaries online and offline at all times',
                ],
                'faqs' => [
                    ['q' => 'Should you ask for someone’s phone number immediately?', 'a' => 'No. Keep initial chats and video introductions inside CupDate until both people feel genuinely comfortable meeting.'],
                ],
            ],
        ];

        return $items;
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::all()[$slug] ?? null;
    }
}
