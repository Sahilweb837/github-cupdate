<?php

namespace App\Data;

class SeoCities
{
    /**
     * 105+ Indian cities across all states & UTs plus major global coffee-dating hubs.
     *
     * @return array<string, array{slug: string, name: string, state: string, country: string, spot: string, vibe: string}>
     */
    public static function all(): array
    {
        $raw = [
            // Himachal Pradesh & North Hills
            ['shimla', 'Shimla', 'Himachal Pradesh', 'India', 'The Ridge & Mall Road cafés', 'Heritage hillside walks and warm espresso conversations'],
            ['dharamshala', 'Dharamshala', 'Himachal Pradesh', 'India', 'McLeod Ganj & Jogibara Road cafés', 'Mountain-view bookshops, organic coffee, and calm sunset chats'],
            ['kangra', 'Kangra', 'Himachal Pradesh', 'India', 'Kangra Valley & Bir Road cafés', 'Relaxed valley mornings and genuine local connections'],
            ['mandi', 'Mandi', 'Himachal Pradesh', 'India', 'Beas Riverfront & Seri Manch cafés', 'Riverside coffee dates and friendly campus meetups'],
            ['manali', 'Manali', 'Himachal Pradesh', 'India', 'Old Manali & Vashisht garden cafés', 'Cozy cedar-forest bakeries and traveler-friendly coffee dates'],
            ['palampur', 'Palampur', 'Himachal Pradesh', 'India', 'Tea Garden & Dhauladhar cafés', 'Peaceful tea-estate trails and sunlit afternoon coffee'],
            ['solan', 'Solan', 'Himachal Pradesh', 'India', 'Mall Road & Barog hillside cafés', 'University town energy and scenic weekend meetups'],
            ['kullu', 'Kullu', 'Himachal Pradesh', 'India', 'Dhalpur & riverside cafés', 'Warm valley hospitality and unhurried daytime chats'],
            ['hamirpur', 'Hamirpur', 'Himachal Pradesh', 'India', 'Gandhi Chowk & NIT corridor cafés', 'Friendly local coffee spots and student-professional community'],
            ['una', 'Una', 'Himachal Pradesh', 'India', 'Nangal Road & central cafés', 'Easy foothill coffee meetups and verified local profiles'],
            ['kasauli', 'Kasauli', 'Himachal Pradesh', 'India', 'Heritage Market & Sunset Point cafés', 'Pine-scented strolls and quiet afternoon cappuccinos'],
            ['dalhousie', 'Dalhousie', 'Himachal Pradesh', 'India', 'Gandhi Chowk & Subhash Baoli cafés', 'Misty mountain views and relaxed coffee conversations'],
            ['chamba', 'Chamba', 'Himachal Pradesh', 'India', 'Chowgan & Ravi valley cafés', 'Historic valley charm and respectful local introductions'],
            ['bilaspur-hp', 'Bilaspur', 'Himachal Pradesh', 'India', 'Gobind Sagar lakeside cafés', 'Scenic lakeside coffee talks and genuine friendships'],
            ['nahan', 'Nahan', 'Himachal Pradesh', 'India', 'Ranital Garden & Mall Road cafés', 'Quiet town squares and comfortable first coffee dates'],

            // NCR & North India
            ['new-delhi', 'New Delhi', 'Delhi NCR', 'India', 'Khan Market, Connaught Place & Hauz Khas cafés', 'Artisanal roasters, book cafés, and curated city connections'],
            ['noida', 'Noida', 'Uttar Pradesh', 'India', 'Sector 18 & Galleria specialty cafés', 'Modern tech-hub coffee lounges and weekend conversations'],
            ['gurugram', 'Gurugram', 'Haryana', 'India', 'CyberHub & Galleria Market cafés', 'Bright specialty coffee bars and after-work meetups'],
            ['faridabad', 'Faridabad', 'Haryana', 'India', 'Sector 15 & Crown Plaza cafés', 'Neighborhood coffee spots and genuine NCR introductions'],
            ['ghaziabad', 'Ghaziabad', 'Uttar Pradesh', 'India', 'Indirapuram & Raj Nagar cafés', 'Warm neighborhood coffee houses and easy local chats'],
            ['chandigarh', 'Chandigarh', 'Chandigarh', 'India', 'Sector 17, Sector 35 & Sukhna Lake cafés', 'Tree-lined avenues, lake walks, and relaxed coffee culture'],
            ['mohali', 'Mohali', 'Punjab', 'India', 'Sector 70 & VR Punjab cafés', 'Vibrant café strips and friendly young professionals'],
            ['panchkula', 'Panchkula', 'Haryana', 'India', 'Sector 5 & Morni foothills cafés', 'Calm parks and sunlit weekend coffee dates'],
            ['ludhiana', 'Ludhiana', 'Punjab', 'India', 'Sarabha Nagar & Kipps Market cafés', 'Lively espresso bars and authentic local dating'],
            ['amritsar', 'Amritsar', 'Punjab', 'India', 'Ranjit Avenue & Lawrence Road cafés', 'Warm hospitality, heritage streets, and cozy coffee dates'],
            ['jalandhar', 'Jalandhar', 'Punjab', 'India', 'Model Town & PPR Mall cafés', 'Friendly local cafés and meaningful conversations'],
            ['patiala', 'Patiala', 'Punjab', 'India', 'Bhupindra Road & Leela Bhawan cafés', 'Royal city charm and relaxed daytime coffee meetups'],
            ['bathinda', 'Bathinda', 'Punjab', 'India', 'Model Town & Civil Lines cafés', 'Comfortable neighborhood cafés and verified local singles'],
            ['pathankot', 'Pathankot', 'Punjab', 'India', 'Dalhousie Road & Patel Chowk cafés', 'Gateway-to-hills coffee spots and genuine connections'],
            ['ambala', 'Ambala', 'Haryana', 'India', 'Cantt & Sector 7 cafés', 'Easygoing local cafés and friendly introductions'],
            ['karnal', 'Karnal', 'Haryana', 'India', 'Model Town & Sector 12 cafés', 'Peaceful lakeside parks and warm coffee dates'],
            ['panipat', 'Panipat', 'Haryana', 'India', 'Model Town & Sector 11 cafés', 'Relaxed local hangouts and respectful dating'],
            ['rohtak', 'Rohtak', 'Haryana', 'India', 'Model Town & D-Park cafés', 'University energy and friendly coffee meetups'],
            ['hisar', 'Hisar', 'Haryana', 'India', 'PLA & Urban Estate cafés', 'Warm neighborhood cafés and genuine connections'],
            ['sonipat', 'Sonipat', 'Haryana', 'India', 'Sector 14 & Murthal corridor cafés', 'Campus-town cafés and easy weekend coffee chats'],
            ['dehradun', 'Dehradun', 'Uttarakhand', 'India', 'Rajpur Road & Jakhan cafés', 'Foothill breezes, indie book cafés, and slow pour-overs'],
            ['rishikesh', 'Rishikesh', 'Uttarakhand', 'India', 'Tapovan & Lakshman Jhula riverside cafés', 'Ganga-view terraces, herbal teas, and mindful connections'],
            ['haridwar', 'Haridwar', 'Uttarakhand', 'India', 'Ranipur More & Jwalapur cafés', 'Peaceful town cafés and respectful local introductions'],
            ['nainital', 'Nainital', 'Uttarakhand', 'India', 'Mall Road & Naini Lake cafés', 'Lakefront strolls and warm hillside coffee dates'],
            ['mussoorie', 'Mussoorie', 'Uttarakhand', 'India', 'Landour & Camel’s Back Road cafés', 'Bakehouses, mountain mist, and classic coffee dates'],
            ['haldwani', 'Haldwani', 'Uttarakhand', 'India', 'Nainital Road & Civil Lines cafés', 'Foothill hospitality and relaxed local meetups'],
            ['srinagar', 'Srinagar', 'Jammu & Kashmir', 'India', 'Boulevard Road & Dal Lake cafés', 'Scenic kahwa & coffee terraces and warm conversations'],
            ['jammu', 'Jammu', 'Jammu & Kashmir', 'India', 'Gandhi Nagar & Trikuta Nagar cafés', 'Cozy city cafés and verified local profiles'],
            ['leh', 'Leh', 'Ladakh', 'India', 'Main Bazaar & Fort Road cafés', 'High-altitude bakeries and memorable traveler coffee chats'],

            // Uttar Pradesh & Central India
            ['lucknow', 'Lucknow', 'Uttar Pradesh', 'India', 'Hazratganj & Gomti Nagar cafés', 'Tehzeeb, garden cafés, and thoughtful conversations'],
            ['kanpur', 'Kanpur', 'Uttar Pradesh', 'India', 'Swaroop Nagar & Civil Lines cafés', 'Warm local espresso bars and friendly city meetups'],
            ['varanasi', 'Varanasi', 'Uttar Pradesh', 'India', 'Assi Ghat & Sigra rooftop cafés', 'Sunrise ghats, quiet cafés, and soulful connections'],
            ['agra', 'Agra', 'Uttar Pradesh', 'India', 'Fatehabad Road & Sanjay Place cafés', 'Heritage views and sunlit rooftop coffee dates'],
            ['prayagraj', 'Prayagraj', 'Uttar Pradesh', 'India', 'Civil Lines & Thornhill Road cafés', 'Academic city energy and calm café conversations'],
            ['meerut', 'Meerut', 'Uttar Pradesh', 'India', 'Abu Lane & Shastri Nagar cafés', 'Neighborhood coffee spots and genuine local connections'],
            ['bareilly', 'Bareilly', 'Uttar Pradesh', 'India', 'Civil Lines & DD Puram cafés', 'Friendly neighborhood cafés and verified profiles'],
            ['aligarh', 'Aligarh', 'Uttar Pradesh', 'India', 'Ramghat Road & Civil Lines cafés', 'Campus charm and relaxed afternoon coffee'],
            ['moradabad', 'Moradabad', 'Uttar Pradesh', 'India', 'Civil Lines & Kanth Road cafés', 'Comfortable local hangouts and respectful dating'],
            ['gorakhpur', 'Gorakhpur', 'Uttar Pradesh', 'India', 'Golghar & Taramandal cafés', 'Lakeside evenings and friendly local coffee dates'],
            ['jhansi', 'Jhansi', 'Uttar Pradesh', 'India', 'Civil Lines & Sipri Bazar cafés', 'Historic fort views and warm coffee meetups'],
            ['bhopal', 'Bhopal', 'Madhya Pradesh', 'India', 'Upper Lake & Arera Colony cafés', 'Lakeside sunsets and relaxed specialty coffee dates'],
            ['indore', 'Indore', 'Madhya Pradesh', 'India', 'Vijay Nagar & 56 Dukaan cafés', 'Vibrant food & coffee culture and friendly local singles'],
            ['gwalior', 'Gwalior', 'Madhya Pradesh', 'India', 'City Centre & Lashkar cafés', 'Warm central-India hospitality and daytime cafés'],
            ['jabalpur', 'Jabalpur', 'Madhya Pradesh', 'India', 'Napier Town & Civil Lines cafés', 'Calm neighborhood cafés and meaningful connections'],
            ['ujjain', 'Ujjain', 'Madhya Pradesh', 'India', 'Freeganj & Nanakheda cafés', 'Peaceful cafés and genuine local introductions'],
            ['raipur', 'Raipur', 'Chhattisgarh', 'India', 'Marine Drive & Civil Lines cafés', 'Evening lake breezes and modern coffee hangouts'],
            ['bilaspur-cg', 'Bilaspur CG', 'Chhattisgarh', 'India', 'Link Road & Vyapar Vihar cafés', 'Friendly local cafés and respectful conversations'],

            // West India
            ['mumbai', 'Mumbai', 'Maharashtra', 'India', 'Bandra, Kala Ghoda & Powai cafés', 'Sea-breeze roasteries, art cafés, and fast city connections'],
            ['navi-mumbai', 'Navi Mumbai', 'Maharashtra', 'India', 'Vashi, Nerul & Seawoods cafés', 'Palm-lined cafés and relaxed weekend coffee meetups'],
            ['thane', 'Thane', 'Maharashtra', 'India', 'Hiranandani Estate & Upvan Lake cafés', 'Lakeside coffee bars and verified neighborhood profiles'],
            ['pune', 'Pune', 'Maharashtra', 'India', 'Koregaon Park, FC Road & Baner cafés', 'Lush garden cafés, indie music, and thoughtful conversations'],
            ['nagpur', 'Nagpur', 'Maharashtra', 'India', 'Dharampeth & Civil Lines cafés', 'Orange-city warmth and cozy neighborhood coffee bars'],
            ['nashik', 'Nashik', 'Maharashtra', 'India', 'College Road & Gangapur Road cafés', 'Vineyard breezes and sunlit afternoon coffee dates'],
            ['aurangabad', 'Chhatrapati Sambhajinagar', 'Maharashtra', 'India', 'Cidco & Osmanpura cafés', 'Heritage charm and relaxed city coffee lounges'],
            ['kolhapur', 'Kolhapur', 'Maharashtra', 'India', 'Rajarampuri & Rankala Lake cafés', 'Lakeside evenings and friendly local introductions'],
            ['ahmedabad', 'Ahmedabad', 'Gujarat', 'India', 'Sindhu Bhavan Road & CG Road cafés', 'Vibrant late-evening cafés and warm community spirit'],
            ['surat', 'Surat', 'Gujarat', 'India', 'Vesu & Piplod specialty cafés', 'Modern riverside cafés and genuine local dating'],
            ['vadodara', 'Vadodara', 'Gujarat', 'India', 'Alkapuri & Fatehgunj cafés', 'Art-city charm and cozy afternoon coffee meetups'],
            ['rajkot', 'Rajkot', 'Gujarat', 'India', 'Kalawad Road & Race Course cafés', 'Friendly neighborhood cafés and respectful connections'],
            ['bhavnagar', 'Bhavnagar', 'Gujarat', 'India', 'Waghawadi Road & Victoria Park cafés', 'Calm coastal town cafés and verified profiles'],
            ['jamnagar', 'Jamnagar', 'Gujarat', 'India', 'Patel Colony & Lakhota Lake cafés', 'Lakeside strolls and relaxed local coffee dates'],
            ['jaipur', 'Jaipur', 'Rajasthan', 'India', 'C-Scheme, Malviya Nagar & Bani Park cafés', 'Courtyard cafés, pink-city aesthetics, and warm conversations'],
            ['udaipur', 'Udaipur', 'Rajasthan', 'India', 'Lake Pichola & Fateh Sagar rooftop cafés', 'Romantic lakefront coffee terraces and calm mountain views'],
            ['jodhpur', 'Jodhpur', 'Rajasthan', 'India', 'Ratanada & Old City stepwell cafés', 'Blue-city rooftops and memorable afternoon coffee chats'],
            ['kota', 'Kota', 'Rajasthan', 'India', 'Talwandi & Chambal Riverfront cafés', 'Riverfront hangouts and friendly campus-city connections'],
            ['ajmer', 'Ajmer', 'Rajasthan', 'India', 'Ana Sagar & Vaishali Nagar cafés', 'Lakefront cafés and easygoing local introductions'],
            ['bikaner', 'Bikaner', 'Rajasthan', 'India', 'Civil Lines & Station Road cafés', 'Warm desert-city hospitality and peaceful cafés'],
            ['panaji', 'Panaji (Goa)', 'Goa', 'India', 'Fontainhas, Assagao & Miramar cafés', 'Sunlit Portuguese-quarter cafés, cold brews, and coastal vibes'],

            // East & Northeast India
            ['kolkata', 'Kolkata', 'West Bengal', 'India', 'Park Street, Salt Lake & Hindustan Park cafés', 'Literary heritage cafés, filter coffee, and deep conversations'],
            ['howrah', 'Howrah', 'West Bengal', 'India', 'Shibpur & Avani Mall cafés', 'River-breeze cafés and warm local connections'],
            ['siliguri', 'Siliguri', 'West Bengal', 'India', 'Sevoke Road & City Centre cafés', 'Foothill gateway cafés and Darjeeling tea/coffee culture'],
            ['durgapur', 'Durgapur', 'West Bengal', 'India', 'City Centre & Bidhannagar cafés', 'Peaceful campus town cafés and genuine friendships'],
            ['asansol', 'Asansol', 'West Bengal', 'India', 'GT Road & Burnpur cafés', 'Friendly neighborhood cafés and verified local profiles'],
            ['patna', 'Patna', 'Bihar', 'India', 'Boring Road & Kankarbagh cafés', 'Warm hospitality and cozy city coffee houses'],
            ['ranchi', 'Ranchi', 'Jharkhand', 'India', 'Lalpur & Kanke Road cafés', 'Breezy plateau evenings and friendly coffee dates'],
            ['jamshedpur', 'Jamshedpur', 'Jharkhand', 'India', 'Bistupur & Jubilee Park cafés', 'Green-city parks and relaxed specialty coffee'],
            ['dhanbad', 'Dhanbad', 'Jharkhand', 'India', 'Bank More & Saraidhela cafés', 'Campus energy and welcoming neighborhood cafés'],
            ['bhubaneswar', 'Bhubaneswar', 'Odisha', 'India', 'Patia, Saheed Nagar & Jaydev Vihar cafés', 'Temple-city calm and modern specialty coffee bars'],
            ['cuttack', 'Cuttack', 'Odisha', 'India', 'Buxi Bazar & CDA cafés', 'Silver-city charm and warm daytime coffee chats'],
            ['rourkela', 'Rourkela', 'Odisha', 'India', 'Civil Township & Sector 19 cafés', 'Green avenues and friendly local meetups'],
            ['guwahati', 'Guwahati', 'Assam', 'India', 'GS Road, Zoo Road & Brahmaputra cafés', 'Riverfront sunsets, Assam brews, and warm connections'],
            ['shillong', 'Shillong', 'Meghalaya', 'India', 'Police Bazar & Laitumkhrah cafés', 'Acoustic music cafés, misty hills, and rich coffee culture'],
            ['gangtok', 'Gangtok', 'Sikkim', 'India', 'MG Marg & Tadong cafés', 'Himalayan bakeries, scenic viewpoints, and calm coffee dates'],
            ['imphal', 'Imphal', 'Manipur', 'India', 'Thangal Bazar & Lamphel cafés', 'Warm local cafés and genuine community connections'],
            ['aizawl', 'Aizawl', 'Mizoram', 'India', 'Zarkawt & Dawrpui cafés', 'Hilltop coffee views and friendly local conversations'],
            ['agartala', 'Agartala', 'Tripura', 'India', 'City Centre & Ujjayanta cafés', 'Peaceful town cafés and respectful connections'],
            ['kohima', 'Kohima', 'Nagaland', 'India', 'PR Hill & High School Junction cafés', 'Artisan roasters and hillside coffee meetups'],
            ['itanagar', 'Itanagar', 'Arunachal Pradesh', 'India', 'Ganga Market & Naharlagun cafés', 'Mountain-valley cafés and warm introductions'],

            // South India & Islands
            ['bengaluru', 'Bengaluru', 'Karnataka', 'India', 'Indiranagar, Koramangala & Church Street cafés', 'India’s specialty coffee capital, garden terraces, and live chats'],
            ['mysuru', 'Mysuru', 'Karnataka', 'India', 'Gokulam & Saraswathipuram cafés', 'Heritage boulevards, filter coffee, and calm conversations'],
            ['mangaluru', 'Mangaluru', 'Karnataka', 'India', 'Hampankatta & Kadri cafés', 'Coastal breezes and cozy daytime coffee spots'],
            ['hubballi', 'Hubballi-Dharwad', 'Karnataka', 'India', 'Vidyanagar & Gokul Road cafés', 'Friendly campus cafés and genuine local dating'],
            ['hyderabad', 'Hyderabad', 'Telangana', 'India', 'Jubilee Hills, Banjara Hills & Gachibowli cafés', 'Artisan coffee lounges, lake views, and warm conversations'],
            ['warangal', 'Warangal', 'Telangana', 'India', 'Hanamkonda & Nakkalagutta cafés', 'Historic city charm and friendly neighborhood cafés'],
            ['chennai', 'Chennai', 'Tamil Nadu', 'India', 'Alwarpet, Besant Nagar & Nungambakkam cafés', 'Beachside breezes, filter coffee, and authentic conversations'],
            ['coimbatore', 'Coimbatore', 'Tamil Nadu', 'India', 'RS Puram & Race Course Road cafés', 'Pleasant hill breezes and cozy specialty cafés'],
            ['madurai', 'Madurai', 'Tamil Nadu', 'India', 'KK Nagar & Anna Nagar cafés', 'Warm hospitality and relaxed evening coffee chats'],
            ['tiruchirappalli', 'Tiruchirappalli', 'Tamil Nadu', 'India', 'Thillai Nagar & Cantonment cafés', 'Friendly campus cafés and respectful dating'],
            ['salem', 'Salem', 'Tamil Nadu', 'India', 'Fairlands & Five Roads cafés', 'Foothill calm and welcoming neighborhood coffee spots'],
            ['puducherry', 'Puducherry', 'Puducherry', 'India', 'White Town & Promenade cafés', 'French-quarter bakeries, oceanfront walks, and warm coffee dates'],
            ['kochi', 'Kochi', 'Kerala', 'India', 'Fort Kochi, Panampilly Nagar & Kakkanad cafés', 'Art-quarter cafés, backwater views, and relaxed conversations'],
            ['thiruvananthapuram', 'Thiruvananthapuram', 'Kerala', 'India', 'Kowdiar & Vazhuthacaud cafés', 'Lush avenues and cozy coastal-city coffee bars'],
            ['kozhikode', 'Kozhikode', 'Kerala', 'India', 'Beach Road & Mavoor Road cafés', 'Warm Malabar hospitality and sea-breeze coffee dates'],
            ['visakhapatnam', 'Visakhapatnam', 'Andhra Pradesh', 'India', 'RK Beach & MVP Colony cafés', 'Ocean-view cafés and relaxed coastal coffee dates'],
            ['vijayawada', 'Vijayawada', 'Andhra Pradesh', 'India', 'MG Road & Benz Circle cafés', 'Riverside evenings and friendly neighborhood cafés'],
            ['tirupati', 'Tirupati', 'Andhra Pradesh', 'India', 'Air Bypass Road & Korlagunta cafés', 'Peaceful foothills and welcoming daytime cafés'],
            ['port-blair', 'Port Blair', 'Andaman & Nicobar', 'India', 'Aberdeen Bazar & Marina Park cafés', 'Island ocean views and calm coffee conversations'],

            // Global Country & International Hubs
            ['india', 'India (All States)', 'Nationwide', 'India', '100+ Indian cities from Himachal to Bengaluru', 'The #1 free coffee & social dating community across every Indian state'],
            ['united-states', 'United States', 'North America', 'United States', 'New York, Austin, Seattle & San Francisco cafés', 'Free global coffee dating and instant live video connections'],
            ['united-kingdom', 'United Kingdom', 'Europe', 'United Kingdom', 'London, Manchester & Edinburgh coffee houses', 'Warm British café dates and safe video chats'],
            ['canada', 'Canada', 'North America', 'Canada', 'Toronto, Vancouver & Montreal roasteries', 'Friendly multicultural coffee dates and live video calls'],
            ['australia', 'Australia', 'Oceania', 'Australia', 'Melbourne laneway & Sydney harbour cafés', 'Flat whites, sunny terraces, and genuine connections'],
            ['uae', 'Dubai & UAE', 'Middle East', 'United Arab Emirates', 'JBR, Downtown Dubai & Abu Dhabi cafés', 'Safe modern café meetups and verified international singles'],
            ['singapore', 'Singapore', 'Southeast Asia', 'Singapore', 'Tiong Bahru & Marina Bay cafés', 'Artisan coffee spots and global free video dating'],
            ['germany', 'Germany', 'Europe', 'Germany', 'Berlin, Munich & Hamburg cafés', 'Cozy European coffee houses and respectful connections'],
            ['france', 'France', 'Europe', 'France', 'Paris, Lyon & Nice sidewalk cafés', 'Classic café culture and thoughtful conversations'],
            ['japan', 'Japan', 'East Asia', 'Japan', 'Tokyo, Kyoto & Osaka kissaten cafés', 'Specialty pour-over cafés and respectful global connections'],
        ];

        $out = [];
        foreach ($raw as [$slug, $name, $state, $country, $spot, $vibe]) {
            $out[$slug] = [
                'slug' => $slug,
                'name' => $name,
                'state' => $state,
                'country' => $country,
                'spot' => $spot,
                'vibe' => $vibe,
            ];
        }

        return $out;
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::all()[$slug] ?? null;
    }

    /**
     * Group cities by country/state for clean directory rendering.
     *
     * @return array<string, array<int, array{slug: string, name: string, state: string, country: string, spot: string, vibe: string}>>
     */
    public static function grouped(): array
    {
        $groups = [];
        foreach (self::all() as $city) {
            $group = $city['country'] === 'India' && $city['slug'] !== 'india'
                ? $city['state']
                : 'Global & Country Hubs';
            $groups[$group][] = $city;
        }

        return $groups;
    }
}
