<?php

namespace App\Data;

class SeoAstrology
{
    /**
     * 12 Zodiac dating & communication profiles for Love & Astrology SEO pages.
     *
     * @return array<string, array{slug: string, name: string, element: string, dates: string, style: string, coffee: string, trait: string}>
     */
    public static function signs(): array
    {
        return [
            'aries' => ['slug' => 'aries', 'name' => 'Aries', 'element' => 'Fire', 'dates' => 'Mar 21 - Apr 19', 'style' => 'Direct, enthusiastic, and energized by spontaneous plans', 'coffee' => 'Double espresso or iced americano at an outdoor terrace', 'trait' => 'Bold conversation and clear intentions'],
            'taurus' => ['slug' => 'taurus', 'name' => 'Taurus', 'element' => 'Earth', 'dates' => 'Apr 20 - May 20', 'style' => 'Warm, steady, and drawn to calm sensory comfort', 'coffee' => 'Caramel latte with fresh bakery pastry in a garden cafe', 'trait' => 'Patience, loyalty, and grounded presence'],
            'gemini' => ['slug' => 'gemini', 'name' => 'Gemini', 'element' => 'Air', 'dates' => 'May 21 - Jun 20', 'style' => 'Curious, witty, and thrives on lively idea exchanges', 'coffee' => 'Cold brew flight or bookstore specialty cafe table', 'trait' => 'Playful banter and wide curiosity'],
            'cancer' => ['slug' => 'cancer', 'name' => 'Cancer', 'element' => 'Water', 'dates' => 'Jun 21 - Jul 22', 'style' => 'Thoughtful, empathetic, and values emotional safety', 'coffee' => 'Warm hazelnut cappuccino at a quiet corner table', 'trait' => 'Kindness, attentive listening, and sincerity'],
            'leo' => ['slug' => 'leo', 'name' => 'Leo', 'element' => 'Fire', 'dates' => 'Jul 23 - Aug 22', 'style' => 'Generous, expressive, and appreciates genuine warmth', 'coffee' => 'Signature mocha or sunlit rooftop brunch coffee', 'trait' => 'Confidence, loyalty, and celebratory energy'],
            'virgo' => ['slug' => 'virgo', 'name' => 'Virgo', 'element' => 'Earth', 'dates' => 'Aug 23 - Sep 22', 'style' => 'Observant, sincere, and notices small thoughtful details', 'coffee' => 'Single-origin pour-over in a clean minimalist cafe', 'trait' => 'Reliability, clarity, and meaningful care'],
            'libra' => ['slug' => 'libra', 'name' => 'Libra', 'element' => 'Air', 'dates' => 'Sep 23 - Oct 22', 'style' => 'Harmonious, charming, and loves balanced give-and-take', 'coffee' => 'Artisanal rose latte or gallery-district espresso bar', 'trait' => 'Diplomacy, aesthetic taste, and fairness'],
            'scorpio' => ['slug' => 'scorpio', 'name' => 'Scorpio', 'element' => 'Water', 'dates' => 'Oct 23 - Nov 21', 'style' => 'Deep, honest, and prefers substance over surface talk', 'coffee' => 'Dark roast flat white at a calm, unhurried cafe', 'trait' => 'Depth, honesty, and fierce devotion'],
            'sagittarius' => ['slug' => 'sagittarius', 'name' => 'Sagittarius', 'element' => 'Fire', 'dates' => 'Nov 22 - Dec 21', 'style' => 'Adventurous, optimistic, and loves travel stories', 'coffee' => 'Mountain-view drip coffee after a morning walk', 'trait' => 'Open-mindedness and humor'],
            'capricorn' => ['slug' => 'capricorn', 'name' => 'Capricorn', 'element' => 'Earth', 'dates' => 'Dec 22 - Jan 19', 'style' => 'Goal-focused, dependable, and respects people’s time', 'coffee' => 'Classic black Americano before or after work', 'trait' => 'Steadiness, ambition, and respect'],
            'aquarius' => ['slug' => 'aquarius', 'name' => 'Aquarius', 'element' => 'Air', 'dates' => 'Jan 20 - Feb 18', 'style' => 'Independent, original, and values friendship-first chemistry', 'coffee' => 'Matcha tonic or indie co-working cafe meetup', 'trait' => 'Original thought and authentic freedom'],
            'pisces' => ['slug' => 'pisces', 'name' => 'Pisces', 'element' => 'Water', 'dates' => 'Feb 19 - Mar 20', 'style' => 'Gentle, creative, and attuned to unspoken warmth', 'coffee' => 'Spiced vanilla latte by a rainy window seat', 'trait' => 'Imagination, empathy, and gentleness'],
        ];
    }

    /**
     * Build 156 zodiac guides (12 individual signs + 144 sign pairs) + Love Topics.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }

        $signs = self::signs();
        $out = [];

        foreach ($signs as $slug => $sign) {
            $out[$slug] = [
                'slug' => $slug,
                'kind' => 'sign',
                'sign_a' => $sign,
                'sign_b' => null,
                'title' => "{$sign['name']} Dating, Love Compatibility & First Coffee Date Guide | CupDate",
                'h1' => "{$sign['name']} Love, Personality & Coffee Dating Guide",
                'description' => "Explore {$sign['name']} ({$sign['dates']}) dating traits, {$sign['element']} element compatibility, conversation tips, and best coffee date ideas on CupDate.",
                'intro' => "{$sign['name']} ({$sign['element']} sign, {$sign['dates']}) brings {$sign['trait']} to every conversation. {$sign['style']}.",
                'coffee_idea' => $sign['coffee'],
                'paragraphs' => [
                    "When connecting with a {$sign['name']} on CupDate, sincerity and clear pacing make all the difference. Their natural strength lies in {$sign['trait']}, making daytime coffee dates an ideal setting to explore shared values comfortably.",
                    "Start with a 5-minute CupTV video hello or a relaxed message about travel, books, or weekend routines, then suggest meeting at {$sign['coffee']}.",
                ],
                'faqs' => [
                    ['q' => "What is {$sign['name']}'s ideal first coffee date?", 'a' => "A comfortable public daytime spot featuring {$sign['coffee']} suits {$sign['name']} best."],
                    ['q' => "How does {$sign['name']} communicate in early dating?", 'a' => "{$sign['style']}, with particular appreciation for {$sign['trait']}."],
                    ['q' => "Can I meet compatible signs near my city on CupDate?", 'a' => "Yes. CupDate connects 18+ members across India and global cities for free 1-to-1 chat, live video, and daytime café meetups."],
                ],
            ];
        }

        foreach ($signs as $slugA => $a) {
            foreach ($signs as $slugB => $b) {
                $pairSlug = "{$slugA}-and-{$slugB}";
                $harmony = self::elementHarmony($a['element'], $b['element']);

                $out[$pairSlug] = [
                    'slug' => $pairSlug,
                    'kind' => 'pair',
                    'sign_a' => $a,
                    'sign_b' => $b,
                    'title' => "{$a['name']} and {$b['name']} Love Compatibility & Dating Guide | CupDate",
                    'h1' => "{$a['name']} and {$b['name']} Compatibility Over Coffee",
                    'description' => "How do {$a['name']} ({$a['element']}) and {$b['name']} ({$b['element']}) connect in love, friendship, and communication? Read the CupDate astrology compatibility guide.",
                    'intro' => "{$a['name']} ({$a['element']}) and {$b['name']} ({$b['element']}) create {$harmony['headline']}. Together they balance {$a['trait']} with {$b['trait']}.",
                    'coffee_idea' => $a['coffee'],
                    'paragraphs' => [
                        $harmony['detail'],
                        "On a first date, {$a['name']} tends toward '{$a['style']}' while {$b['name']} leans into '{$b['style']}'. A neutral, sunlit neighborhood café gives both people breathing room to listen carefully and enjoy the moment.",
                    ],
                    'faqs' => [
                        ['q' => "Are {$a['name']} and {$b['name']} compatible for a long-term relationship?", 'a' => "{$harmony['detail']} Clear communication and shared respect matter far more than sun signs alone."],
                        ['q' => "What is a good icebreaker for {$a['name']} and {$b['name']}?", 'a' => "Play a quick round of CupDate Truth or Dare or talk about travel stories over {$b['coffee']}."],
                        ['q' => "Where can {$a['name']} and {$b['name']} meet safely?", 'a' => "Start with free CupDate messaging and CupTV live video, then choose a women-friendly daytime café in your city."],
                    ],
                ];
            }
        }

        $cached = $out;

        return $cached;
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::all()[strtolower(trim($slug, '/ '))] ?? null;
    }

    /**
     * @return array{headline: string, detail: string}
     */
    private static function elementHarmony(string $elA, string $elB): array
    {
        if ($elA === $elB) {
            return [
                'headline' => 'instant intuitive understanding',
                'detail' => "Sharing the {$elA} element means natural rhythm around pace, humor, and weekend energy.",
            ];
        }

        $pair = [$elA, $elB];
        sort($pair);
        $key = implode('-', $pair);

        return match ($key) {
            'Air-Fire' => [
                'headline' => 'lively curiosity and spontaneous momentum',
                'detail' => 'Fire enthusiasm fuels Air ideas, turning simple coffee chats into memorable plans.',
            ],
            'Earth-Water' => [
                'headline' => 'calm steadiness and emotional warmth',
                'detail' => 'Water empathy softens Earth structure, building steady trust and quiet comfort.',
            ],
            'Earth-Fire' => [
                'headline' => 'ambitious drive balanced by practical grounding',
                'detail' => 'Fire inspires vision while Earth turns big ideas into reliable, real-world dates.',
            ],
            'Air-Water' => [
                'headline' => 'thoughtful conversation paired with emotional depth',
                'detail' => 'Combining reflection with intuition teaches both partners to listen and communicate clearly.',
            ],
            'Air-Earth' => [
                'headline' => 'fresh perspective meeting patient consistency',
                'detail' => 'Thoughtful planning helps Air creativity and Earth dependability build lasting friendship first.',
            ],
            default => [
                'headline' => 'passionate contrast and mutual discovery',
                'detail' => 'Dynamic emotional and expressive differences reward patience, warmth, and open dialogue.',
            ],
        };
    }
}
