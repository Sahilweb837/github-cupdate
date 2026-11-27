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

            // Expanded Tier-2 / Tier-3 Indian Cities & Global Hubs (200+ Total SEO Pages)
            ['mcleod-ganj', 'McLeod Ganj', 'Himachal Pradesh', 'India', 'Bhagsu & Jogibara café terraces', 'Himalayan coffee culture and peaceful conversations'],
            ['bir-billing', 'Bir Billing', 'Himachal Pradesh', 'India', 'Landing site & Tibetan colony cafés', 'Adventure cafés and sunlit afternoon meetups'],
            ['kasol', 'Kasol', 'Himachal Pradesh', 'India', 'Parvati River & Chalal cafés', 'Mountain river views and traveler coffee dates'],
            ['baddi', 'Baddi', 'Himachal Pradesh', 'India', 'HIMUDA & Mall Road cafés', 'Growing professional community and friendly chats'],
            ['paonta-sahib', 'Paonta Sahib', 'Himachal Pradesh', 'India', 'Yamuna riverside & Main Market cafés', 'Peaceful riverside coffee dates'],
            ['sundernagar', 'Sundernagar', 'Himachal Pradesh', 'India', 'BBMB Lake & Bhojpur cafés', 'Lakeside evening walks and warm coffee'],
            ['keylong', 'Keylong', 'Himachal Pradesh', 'India', 'Lahaul Valley & Main Bazaar cafés', 'High-mountain coffee stops and genuine stories'],
            ['spiti', 'Kaza (Spiti)', 'Himachal Pradesh', 'India', 'Kaza Market & Monastery cafés', 'Starry mountain skies and unforgettable conversations'],
            ['mathura', 'Mathura', 'Uttar Pradesh', 'India', 'Dampier Nagar & Krishna Nagar cafés', 'Warm heritage town cafés and genuine connections'],
            ['vrindavan', 'Vrindavan', 'Uttar Pradesh', 'India', 'Chhatikara Road & Prem Mandir cafés', 'Peaceful garden cafés and respectful introductions'],
            ['ayodhya', 'Ayodhya', 'Uttar Pradesh', 'India', 'Civil Lines & Saryu Riverfront cafés', 'Calm riverfront cafés and friendly conversations'],
            ['noida-extension', 'Greater Noida', 'Uttar Pradesh', 'India', 'Knowledge Park & Alpha Commercial cafés', 'Campus hangouts and specialty coffee lounges'],
            ['saharanpur', 'Saharanpur', 'Uttar Pradesh', 'India', 'Court Road & Delhi Road cafés', 'Friendly local coffee shops and verified profiles'],
            ['muzaffarnagar', 'Muzaffarnagar', 'Uttar Pradesh', 'India', 'Civil Lines & New Mandi cafés', 'Warm neighborhood cafés and respectful dating'],
            ['firozabad', 'Firozabad', 'Uttar Pradesh', 'India', 'Suhag Nagar & Station Road cafés', 'Relaxed local cafés and genuine introductions'],
            ['ramnagar', 'Ramnagar', 'Uttarakhand', 'India', 'Corbett Road & Dhikuli cafés', 'Forest-edge cafés and weekend coffee dates'],
            ['almora', 'Almora', 'Uttarakhand', 'India', 'Lala Bazaar & Bright End Corner cafés', 'Kumaon hill views and cozy sunset coffee'],
            ['roorkee', 'Roorkee', 'Uttarakhand', 'India', 'Civil Lines & IIT corridor cafés', 'University town cafés and smart conversations'],
            ['kashipur', 'Kashipur', 'Uttarakhand', 'India', 'Ramnagar Road & Awas Vikas cafés', 'Friendly foothill cafés and local meetups'],
            ['rudrapur', 'Rudrapur', 'Uttarakhand', 'India', 'Civil Lines & Metropolis cafés', 'Modern neighborhood cafés and verified profiles'],
            ['hoshiarpur', 'Hoshiarpur', 'Punjab', 'India', 'Model Town & Mall Road cafés', 'Peaceful local cafés and warm introductions'],
            ['moga', 'Moga', 'Punjab', 'India', 'GT Road & Civil Lines cafés', 'Friendly town cafés and authentic local dating'],
            ['firozpur', 'Firozpur', 'Punjab', 'India', 'Cantt & Mall Road cafés', 'Warm local hospitality and daytime coffee chats'],
            ['batala', 'Batala', 'Punjab', 'India', 'Jalandhar Road & Model Town cafés', 'Comfortable cafés and verified local profiles'],
            ['phagwara', 'Phagwara', 'Punjab', 'India', 'Model Town & GT Road cafés', 'Student-friendly coffee spots and easy chats'],
            ['zirakpur', 'Zirakpur', 'Punjab', 'India', 'VIP Road & Lohgarh cafés', 'Tricity espresso bars and weekend coffee dates'],
            ['yamunanagar', 'Yamunanagar', 'Haryana', 'India', 'Model Town & Jagadhri cafés', 'Twin-city cafés and friendly conversations'],
            ['kurukshetra', 'Kurukshetra', 'Haryana', 'India', 'Sector 13 & Brahma Sarovar cafés', 'Lakeside heritage charm and calm coffee dates'],
            ['rewari', 'Rewari', 'Haryana', 'India', 'Model Town & Sector 3 cafés', 'Friendly neighborhood cafés and verified singles'],
            ['bhiwani', 'Bhiwani', 'Haryana', 'India', 'Huda City & Hansi Gate cafés', 'Warm local cafés and genuine friendships'],
            ['sirsa', 'Sirsa', 'Haryana', 'India', 'Barnala Road & Sector 20 cafés', 'Peaceful neighborhood cafés and respectful dating'],
            ['alwar', 'Alwar', 'Rajasthan', 'India', 'Moti Dungri & Scheme 2 cafés', 'Aravalli hill cafés and warm daytime chats'],
            ['bharatpur', 'Bharatpur', 'Rajasthan', 'India', 'Circular Road & Ranjit Nagar cafés', 'Garden-city cafés and relaxed introductions'],
            ['sikar', 'Sikar', 'Rajasthan', 'India', 'Piprali Road & Station Road cafés', 'Campus cafés and friendly local dating'],
            ['bhilwara', 'Bhilwara', 'Rajasthan', 'India', 'Shastri Nagar & Azad Nagar cafés', 'Cozy city cafés and verified local profiles'],
            ['mount-abu', 'Mount Abu', 'Rajasthan', 'India', 'Nakki Lake & Sunset Road cafés', 'Hill-station lake cafés and romantic conversations'],
            ['pushkar', 'Pushkar', 'Rajasthan', 'India', 'Pushkar Lake & Sunset Café terraces', 'Rooftop lake views and mindful coffee chats'],
            ['gandhinagar', 'Gandhinagar', 'Gujarat', 'India', 'Infocity & Kudasan cafés', 'Green-capital cafés and friendly tech-campus meetups'],
            ['anand', 'Anand', 'Gujarat', 'India', 'Vallabh Vidyanagar & Amul Dairy Road cafés', 'University-town espresso bars and warm conversations'],
            ['vapi', 'Vapi', 'Gujarat', 'India', 'Chala & Gunjan cafés', 'Friendly town cafés and relaxed weekend meetups'],
            ['navsari', 'Navsari', 'Gujarat', 'India', 'Lunsikui & Station Road cafés', 'Calm coastal-town cafés and genuine connections'],
            ['junagadh', 'Junagadh', 'Gujarat', 'India', 'Moti Baug & Girnar Road cafés', 'Hillside heritage cafés and warm coffee chats'],
            ['kutch-bhuj', 'Bhuj', 'Gujarat', 'India', 'Hamirsar Lake & Jubilee Ground cafés', 'Artisan-city cafés and respectful local dating'],
            ['lonavala', 'Lonavala', 'Maharashtra', 'India', 'MG Road & Tiger Point cafés', 'Misty Western Ghat cafés and weekend coffee dates'],
            ['satara', 'Satara', 'Maharashtra', 'India', 'Powai Naka & Rajwada cafés', 'Valley-town cafés and genuine conversations'],
            ['solapur', 'Solapur', 'Maharashtra', 'India', 'Saat Rasta & Hotgi Road cafés', 'Warm local cafés and verified local profiles'],
            ['amravati', 'Amravati', 'Maharashtra', 'India', 'Camp & Rajapeth cafés', 'Friendly university cafés and easy coffee meetups'],
            ['akola', 'Akola', 'Maharashtra', 'India', 'Ratanlal Plot & Civil Lines cafés', 'Neighborhood coffee houses and respectful dating'],
            ['jalgaon', 'Jalgaon', 'Maharashtra', 'India', 'Ring Road & Navi Peth cafés', 'Cozy local cafés and genuine friendships'],
            ['sangli', 'Sangli', 'Maharashtra', 'India', 'Vishrambag & College Corner cafés', 'Riverside town cafés and warm conversations'],
            ['ratnagiri', 'Ratnagiri', 'Maharashtra', 'India', 'Mandvi Beach & Maruti Mandir cafés', 'Konkan coastal breezes and relaxed coffee dates'],
            ['ujjain-mp', 'Dewas', 'Madhya Pradesh', 'India', 'AB Road & Civil Lines cafés', 'Malwa hospitality and cozy daytime cafés'],
            ['satna', 'Satna', 'Madhya Pradesh', 'India', 'Civil Lines & Rewa Road cafés', 'Friendly local cafés and verified profiles'],
            ['rewa', 'Rewa', 'Madhya Pradesh', 'India', 'Sirmour Chowk & Civil Lines cafés', 'Calm town cafés and genuine conversations'],
            ['sagar', 'Sagar', 'Madhya Pradesh', 'India', 'Makronia & Civil Lines cafés', 'Lakeside views and friendly student cafés'],
            ['ratlam', 'Ratlam', 'Madhya Pradesh', 'India', 'Station Road & Do Batti cafés', 'Warm local cafés and respectful introductions'],
            ['korba', 'Korba', 'Chhattisgarh', 'India', 'TP Nagar & Kosabadi cafés', 'Green-city cafés and friendly coffee meetups'],
            ['bhilai', 'Bhilai-Durg', 'Chhattisgarh', 'India', 'Civic Centre & Nehru Nagar cafés', 'Tree-lined parks and relaxed afternoon coffee'],
            ['muzaffarpur', 'Muzaffarpur', 'Bihar', 'India', 'Mithanpura & Motijheel cafés', 'Friendly neighborhood cafés and verified profiles'],
            ['gaya', 'Gaya', 'Bihar', 'India', 'AP Colony & Bodh Gaya cafés', 'Peaceful garden cafés and mindful conversations'],
            ['bhagalpur', 'Bhagalpur', 'Bihar', 'India', 'Tilkamanjhi & Kachahari Chowk cafés', 'Silk-city warmth and cozy coffee shops'],
            ['darbhanga', 'Darbhanga', 'Bihar', 'India', 'Laheriasarai & Mirzapur cafés', 'Calm town cafés and genuine local dating'],
            ['bokaro', 'Bokaro', 'Jharkhand', 'India', 'City Centre Sector 4 cafés', 'Planned green-sector cafés and friendly meetups'],
            ['deoghar', 'Deoghar', 'Jharkhand', 'India', 'Tower Chowk & VIP Chowk cafés', 'Peaceful hillside town cafés and respectful chats'],
            ['puri', 'Puri', 'Odisha', 'India', 'Marine Drive & Chakra Tirtha Road cafés', 'Ocean-breeze cafés and relaxed sunset coffee'],
            ['berhampur', 'Berhampur', 'Odisha', 'India', 'Gopalpur Road & Gate Bazar cafés', 'Coastal-city cafés and genuine conversations'],
            ['sambalpur', 'Sambalpur', 'Odisha', 'India', 'Budharaja & Ainthapali cafés', 'Hirakud breezes and friendly local cafés'],
            ['darjeeling', 'Darjeeling', 'West Bengal', 'India', 'Chowrasta Mall & Glenary’s corridor cafés', 'Iconic Himalayan tea & coffee terraces'],
            ['kalimpong', 'Kalimpong', 'West Bengal', 'India', 'Rishi Road & Deolo Hill cafés', 'Misty orchid-hill cafés and calm conversations'],
            ['kharagpur', 'Kharagpur', 'West Bengal', 'India', 'Prembazar & IIT corridor cafés', 'Campus energy and cozy late-afternoon coffee'],
            ['haldia', 'Haldia', 'West Bengal', 'India', 'Durgachak & Township cafés', 'Riverfront breezes and friendly neighborhood cafés'],
            ['dibrugarh', 'Dibrugarh', 'Assam', 'India', 'Thana Chariali & Mancotta Road cafés', 'Tea-country hospitality and cozy espresso bars'],
            ['jorhat', 'Jorhat', 'Assam', 'India', 'Gar-Ali & AT Road cafés', 'Tea-garden charm and warm afternoon chats'],
            ['silchar', 'Silchar', 'Assam', 'India', 'Club Road & Rangirkhari cafés', 'Barak Valley warmth and friendly coffee spots'],
            ['tezpur', 'Tezpur', 'Assam', 'India', 'Main Road & Agnigarh Hill cafés', 'Scenic river-hill views and calm coffee dates'],
            ['dimapur', 'Dimapur', 'Nagaland', 'India', 'City Tower & Circular Road cafés', 'Vibrant indie coffee culture and warm connections'],
            ['belagavi', 'Belagavi', 'Karnataka', 'India', 'Tilakwadi & Camp cafés', 'Pleasant green-city cafés and genuine conversations'],
            ['kalaburagi', 'Kalaburagi', 'Karnataka', 'India', 'Super Market & Sedam Road cafés', 'Friendly local cafés and verified profiles'],
            ['udupi', 'Udupi-Manipal', 'Karnataka', 'India', 'Manipal End Point & Malpe Beach cafés', 'Vibrant university coffee bars and coastal sunsets'],
            ['coorg', 'Coorg (Madikeri)', 'Karnataka', 'India', 'Estate roasteries & Raja’s Seat cafés', 'Coffee-plantation homeland and scenic café dates'],
            ['chikmagalur', 'Chikmagalur', 'Karnataka', 'India', 'MG Road & Mullayanagiri foothill cafés', 'India’s coffee birthplace and aromatic estate brews'],
            ['nizamabad', 'Nizamabad', 'Telangana', 'India', 'Khaleelwadi & Vinayak Nagar cafés', 'Warm local cafés and respectful introductions'],
            ['karimnagar', 'Karimnagar', 'Telangana', 'India', 'Mankammathota & Lower Manair cafés', 'Evening lakeside cafés and friendly chats'],
            ['madurai-ooty', 'Ooty (Udhagamandalam)', 'Tamil Nadu', 'India', 'Charing Cross & Botanical Garden cafés', 'Nilgiri mountain mist and cozy hot chocolate/coffee'],
            ['kodaikanal', 'Kodaikanal', 'Tamil Nadu', 'India', 'Coaker’s Walk & Lake Road cafés', 'Pine-forest cafés and peaceful afternoon coffee'],
            ['vellore', 'Vellore', 'Tamil Nadu', 'India', 'Katpadi & Gandhi Nagar cafés', 'Campus-town cafés and friendly local dating'],
            ['tirunelveli', 'Tirunelveli', 'Tamil Nadu', 'India', 'Palayamkottai & Vannarpettai cafés', 'Warm southern hospitality and calm cafés'],
            ['erode', 'Erode', 'Tamil Nadu', 'India', 'Perundurai Road & Brough Road cafés', 'Cozy neighborhood cafés and verified profiles'],
            ['thrissur', 'Thrissur', 'Kerala', 'India', 'Swaraj Round & Punkunnam cafés', 'Cultural-capital cafés and thoughtful conversations'],
            ['kannur', 'Kannur', 'Kerala', 'India', 'Payyambalam Beach & Thavakkara cafés', 'Arabian Sea breezes and relaxed coffee dates'],
            ['munnar', 'Munnar', 'Kerala', 'India', 'Tea Museum Road & Old Munnar cafés', 'Misty tea-hill cafés and warm conversations'],
            ['guntur', 'Guntur', 'Andhra Pradesh', 'India', 'Brodipet & Lakshmipuram cafés', 'Friendly local coffee bars and genuine connections'],
            ['rajahmundry', 'Rajahmundry', 'Andhra Pradesh', 'India', 'Godavari Bund & Danavaipeta cafés', 'Riverfront sunsets and relaxed coffee chats'],
            ['nellore', 'Nellore', 'Andhra Pradesh', 'India', 'Magunta Layout & Trunk Road cafés', 'Warm neighborhood cafés and verified local singles'],
            ['daman', 'Daman', 'Dadra & Nagar Haveli and Daman & Diu', 'India', 'Devka Beach & Nani Daman cafés', 'Seaside breezes and easy weekend coffee dates'],
            ['kavaratti', 'Lakshadweep', 'Lakshadweep', 'India', 'Kavaratti Lagoon & Island cafés', 'Island calm and friendly digital/live video chats'],
            ['new-york', 'New York City', 'New York', 'United States', 'SoHo, Greenwich Village & Brooklyn cafés', 'Global free coffee dating & CupTV live video'],
            ['london', 'London', 'England', 'United Kingdom', 'Soho, Shoreditch & Kensington coffee shops', 'Free UK coffee dating & verified live video chats'],
            ['toronto', 'Toronto', 'Ontario', 'Canada', 'Queen West, Yorkville & Kensington Market cafés', 'Friendly Canadian coffee dates & CupTV cam'],
            ['dubai', 'Dubai', 'Dubai', 'United Arab Emirates', 'JBR, City Walk & DIFC cafés', 'Safe modern coffee lounges & verified profiles'],
            ['melbourne', 'Melbourne', 'Victoria', 'Australia', 'Fitzroy, CBD laneways & St Kilda cafés', 'World-famous flat whites & free global dating'],
            ['sydney', 'Sydney', 'New South Wales', 'Australia', 'Surry Hills, Bondi & Circular Quay cafés', 'Harbourside coffee dates & free live video'],
            ['delhi-ncr', 'Delhi NCR', 'Delhi NCR', 'India', 'Connaught Place, Hauz Khas, CyberHub & Sector 18 cafés', 'Best free Delhi NCR dating, coffee dates & CupTV live video'],
            ['up', 'Uttar Pradesh', 'Uttar Pradesh', 'India', 'Lucknow, Noida, Varanasi, Agra & Kanpur cafés', 'Free coffee dating & safe connections across Uttar Pradesh'],
            ['karachi', 'Karachi', 'Sindh', 'Pakistan', 'Clifton & DHA specialty cafés', 'Friendly coffee conversations & free global video chats'],
            ['lahore', 'Lahore', 'Punjab', 'Pakistan', 'Gulberg & DHA specialty cafés', 'Warm hospitality, coffee lounges, and genuine local chats'],
            ['islamabad', 'Islamabad', 'ICT', 'Pakistan', 'F-6, F-7 & Kohsar Market cafés', 'Scenic Margalla-view cafés and calm daytime conversations'],
            ['rawalpindi', 'Rawalpindi', 'Punjab', 'Pakistan', 'Saddar & Bahria Town cafés', 'Friendly neighborhood coffeehouses and respectful chats'],
            ['peshawar', 'Peshawar', 'Khyber Pakhtunkhwa', 'Pakistan', 'University Town & Hayatabad cafés', 'Warm hospitality and respectful conversations'],
            ['miami', 'Miami', 'Florida', 'United States', 'Wynwood, Brickell & Coconut Grove cafés', 'Sunny coastal specialty coffee shops and free live video'],
            ['phoenix', 'Phoenix', 'Arizona', 'United States', 'Downtown Phoenix, Scottsdale & Tempe cafés', 'Desert-sun daytime cafés and verified free connections'],
            ['austin', 'Austin', 'Texas', 'United States', 'South Congress & East Austin cafés', 'Music-city coffee roasters and friendly social meetups'],
            ['boston', 'Boston', 'Massachusetts', 'United States', 'Back Bay, Cambridge & Newbury Street cafés', 'University-town book cafés and thoughtful conversations'],
            ['paris', 'Paris', 'Ile-de-France', 'France', 'Le Marais, Saint-Germain & Montmartre cafés', 'Classic Parisian terrace coffee dates and global live video'],
            ['tokyo', 'Tokyo', 'Kanto', 'Japan', 'Shibuya, Daikanyama & Kiyosumi cafés', 'Artisanal kissaten and specialty pour-over coffee dates'],
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

    public static function canonicalSlug(string $slug): ?string
    {
        $clean = strtolower(trim($slug, '/ '));
        if ($clean === '' || ! preg_match('/^[a-z0-9\-]{2,60}$/', $clean)) {
            return null;
        }

        if (self::findBySlug($clean)) {
            return $clean;
        }

        $aliases = [
            'delhi' => 'new-delhi',
            'city-delhi' => 'new-delhi',
            'dating-delhi' => 'new-delhi',
            'bangalore' => 'bengaluru',
            'bombay' => 'mumbai',
            'calcutta' => 'kolkata',
            'madras' => 'chennai',
            'trivandrum' => 'thiruvananthapuram',
            'cochin' => 'kochi',
            'baroda' => 'vadodara',
            'benares' => 'varanasi',
            'banaras' => 'varanasi',
            'allahabad' => 'prayagraj',
            'gurgaon' => 'gurugram',
            'poona' => 'pune',
            'mysore' => 'mysuru',
            'mangalore' => 'mangaluru',
            'pondicherry' => 'puducherry',
            'vizag' => 'visakhapatnam',
            'simla' => 'shimla',
            'sanjauli' => 'shimla',
            'summer-hill-hpu' => 'shimla',
            'kasumpti' => 'shimla',
            'new-shimla' => 'shimla',
            'lakkar-bazaar' => 'shimla',
            'mall-road' => 'shimla',
            'kufri' => 'shimla',
            'narkanda' => 'shimla',
            'theog' => 'shimla',
            'chail' => 'solan',
            'dharamsala' => 'dharamshala',
            'mcleodganj' => 'mcleod-ganj',
            'bhuntar' => 'kullu',
            'manikaran' => 'kasol',
            'amb' => 'una',
            'nangal' => 'una',
            'ner-chowk' => 'mandi',
            'nurpur' => 'kangra',
            'bilaspur' => 'bilaspur-hp',
            'goa' => 'panaji',
            'himachal' => 'shimla',
            'himachal-pradesh' => 'shimla',
            'punjab' => 'chandigarh',
            'haryana' => 'gurugram',
            'bihar' => 'patna',
            'jharkhand' => 'ranchi',
            'secunderabad' => 'hyderabad',
            'murree' => 'islamabad',
        ];

        return $aliases[$clean] ?? null;
    }

    /**
     * Resolve curated city by exact slug or alias (no synthetic thin pages for unknown slugs).
     *
     * @return array{slug: string, name: string, state: string, country: string, spot: string, vibe: string}|null
     */
    public static function findOrBuild(string $slug): ?array
    {
        $resolved = self::canonicalSlug($slug);

        return $resolved ? self::findBySlug($resolved) : null;
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
