<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CafeReviewController;
use App\Http\Controllers\LiveVideoController;
use App\Http\Controllers\MomentController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\VideoCallController;
use Illuminate\Support\Facades\Route;

// Public & SEO Routes
Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/live-stats', 'liveStats')->name('live-stats');
    Route::get('/discover', 'discover')->name('public.discover');
    Route::get('/cities', 'cities')->name('cities');
    Route::get('/dating-in/{slug}', 'cityLanding')->name('seo.city');
    Route::get('/how-it-works', 'howItWorks')->name('how-it-works');
    Route::get('/safety', 'safety')->name('safety');
    Route::get('/about-us', 'about')->name('about');
    Route::permanentRedirect('/about', '/about-us');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'submitContact')->middleware('throttle:10,1')->name('contact.submit');
    Route::get('/blog', 'blog')->name('blog');
    Route::get('/blog/{slug}', 'blogShow')->name('blog.show');

    Route::get('/truth-and-dare', 'truthDare')->name('games.truth-dare');
    Route::get('/travel-groups', 'travelGroups')->name('travel.groups');
    Route::get('/icebreaker-questions', fn () => app(PublicController::class)->guide('icebreaker-questions'))->name('seo.icebreakers');
    Route::get('/weekend-meetups', fn () => app(PublicController::class)->guide('weekend-meetups'))->name('seo.meetups');
    Route::get('/speed-dating', fn () => app(PublicController::class)->guide('speed-dating'))->name('seo.speed-dating');
    Route::get('/couple-activities', fn () => app(PublicController::class)->guide('couple-activities'))->name('seo.couple-activities');
    Route::get('/college-dating-india', fn () => app(PublicController::class)->guide('college-dating-india'))->name('seo.college');
    Route::get('/solo-travel-companions', fn () => app(PublicController::class)->guide('solo-travel-companions'))->name('seo.solo-travel');
    Route::get('/dating-etiquette-india', fn () => app(PublicController::class)->guide('dating-etiquette-india'))->name('seo.etiquette');

    Route::get('/privacy-policy', fn () => app(PublicController::class)->legal('privacy-policy'))->name('privacy');
    Route::get('/terms-and-conditions', fn () => app(PublicController::class)->legal('terms-and-conditions'))->name('terms');
    Route::get('/community-guidelines', fn () => app(PublicController::class)->legal('community-guidelines'))->name('guidelines');
    Route::get('/safety-center', fn () => app(PublicController::class)->legal('safety-center'))->name('safety-center');

    // Himachal Pradesh SEO Landing Pages
    Route::get('/dating-in-himachal-pradesh', fn () => app(PublicController::class)->seoLanding('dating-in-himachal-pradesh'))->name('seo.himachal');
    Route::get('/meet-singles-in-kangra', fn () => app(PublicController::class)->seoLanding('meet-singles-in-kangra'))->name('seo.kangra');
    Route::get('/meet-singles-in-dharamshala', fn () => app(PublicController::class)->seoLanding('meet-singles-in-dharamshala'))->name('seo.dharamshala');
    Route::get('/meet-singles-in-mandi', fn () => app(PublicController::class)->seoLanding('meet-singles-in-mandi'))->name('seo.mandi');
    Route::get('/coffee-date-ideas-in-himachal-pradesh', fn () => app(PublicController::class)->seoLanding('coffee-date-ideas-in-himachal-pradesh'))->name('seo.coffee-ideas');

    Route::get('/sitemap.xml', 'sitemap')->name('sitemap');
    Route::get('/robots.txt', 'robots')->name('robots');

    // Legacy Search Console / PHP URL consolidation (301 to single canonical URL — eliminates duplicate canonicals)
    Route::get('/city.php', 'legacyCityQuery');
    Route::get('/city/{slug}', fn (string $slug) => redirect()->route('seo.city', ['slug' => \App\Data\SeoCities::canonicalSlug($slug) ?? strtolower($slug)], 301))->where('slug', '[A-Za-z0-9\-]+');
    Route::get('/dating/dating-{slug}', fn (string $slug) => redirect()->route('seo.city', ['slug' => \App\Data\SeoCities::canonicalSlug(preg_replace('/^(?:dating-|in-)+/', '', strtolower($slug))) ?? strtolower($slug)], 301))->where('slug', '[A-Za-z0-9\-]+');
    Route::get('/dating/{slug}', fn (string $slug) => redirect()->route('seo.city', ['slug' => \App\Data\SeoCities::canonicalSlug(preg_replace('/^(?:dating-|in-)+/', '', strtolower($slug))) ?? strtolower($slug)], 301))->where('slug', '[A-Za-z0-9\-]+');
    Route::get('/dating-in-{slug}', fn (string $slug) => redirect()->route('seo.city', ['slug' => \App\Data\SeoCities::canonicalSlug(preg_replace('/^(?:dating-|in-)+/', '', strtolower($slug))) ?? strtolower($slug)], 301))->where('slug', '[A-Za-z0-9\-]+');
    Route::get('/dating-{slug}', fn (string $slug) => redirect()->route('seo.city', ['slug' => \App\Data\SeoCities::canonicalSlug(preg_replace('/^(?:dating-|in-)+/', '', strtolower($slug))) ?? strtolower($slug)], 301))->where('slug', '[A-Za-z0-9\-]+');
    Route::permanentRedirect('/sitemap.php', '/sitemap.xml');
    Route::permanentRedirect('/sitemap-post.xml', '/sitemap.xml');
    Route::permanentRedirect('/blog/sitemap.xml', '/sitemap.xml');
    Route::permanentRedirect('/blog/sitemap.php', '/sitemap.xml');
    Route::get('/community/{slug}', fn (string $slug) => app(PublicController::class)->guide('community-' . $slug));
    Route::get('/rishta', fn () => app(PublicController::class)->guide('rishta'));

    Route::permanentRedirect('/dates', '/how-it-works');
    Route::permanentRedirect('/coffee-date-ideas', '/coffee-date-ideas-in-himachal-pradesh');
    Route::permanentRedirect('/privacy', '/privacy-policy');
    Route::permanentRedirect('/terms', '/terms-and-conditions');
    Route::permanentRedirect('/faq', '/blog/faq');
    Route::permanentRedirect('/disclaimer', '/blog/disclaimer');

    Route::permanentRedirect('/faq.php', '/blog/faq');
    Route::permanentRedirect('/disclaimer.php', '/blog/disclaimer');
    Route::permanentRedirect('/community-guidelines.php', '/community-guidelines');
    Route::permanentRedirect('/privacy.php', '/privacy-policy');
    Route::permanentRedirect('/terms.php', '/terms-and-conditions');
    Route::permanentRedirect('/about.php', '/about-us');
    Route::permanentRedirect('/contact.php', '/contact');
    Route::permanentRedirect('/safety.php', '/safety');
    Route::permanentRedirect('/blog.php', '/blog');
    Route::permanentRedirect('/guides', '/blog');
    Route::permanentRedirect('/guides.php', '/blog');
    Route::permanentRedirect('/discover.php', '/discover');
    Route::permanentRedirect('/cities.php', '/cities');
    Route::permanentRedirect('/dating', '/cities');
    Route::permanentRedirect('/dating.php', '/cities');
    Route::permanentRedirect('/home', '/');
    Route::permanentRedirect('/home.php', '/');
    Route::permanentRedirect('/index.html', '/');
    Route::permanentRedirect('/moments.php', '/moments');
    Route::permanentRedirect('/cafes.php', '/cafes');
    Route::permanentRedirect('/video', '/live-video');
    Route::permanentRedirect('/video.php', '/live-video');
    Route::permanentRedirect('/video-chat', '/live-video');
    Route::permanentRedirect('/live-video.php', '/live-video');
    Route::permanentRedirect('/cuptv', '/live-video');
    Route::permanentRedirect('/truth-dare.php', '/truth-and-dare');
    Route::permanentRedirect('/truth-or-dare', '/truth-and-dare');
    Route::permanentRedirect('/travel.php', '/travel-groups');
    Route::permanentRedirect('/travel-groups.php', '/travel-groups');
    Route::permanentRedirect('/login.php', '/login');
    Route::permanentRedirect('/register.php', '/register');
    Route::permanentRedirect('/signup', '/register');
    Route::permanentRedirect('/signup.php', '/register');
    Route::permanentRedirect('/join.php', '/register');
    Route::permanentRedirect('/auth/google', '/login');
    Route::get('/meet-singles-in-{slug}', fn (string $slug) => redirect()->route('seo.city', ['slug' => strtolower($slug)], 301))->where('slug', '[A-Za-z0-9\-]+');
    Route::permanentRedirect('/blog/disclaimer.php', '/blog/disclaimer');
    Route::permanentRedirect('/blog/faq.php', '/blog/faq');
    Route::permanentRedirect('/blog/terms.php', '/terms-and-conditions');
    Route::permanentRedirect('/blog/privacy.php', '/privacy-policy');
    Route::permanentRedirect('/blog/cookie-policy.php', '/privacy-policy');
    Route::permanentRedirect('/blog/community-guidelines.php', '/community-guidelines');
    Route::permanentRedirect('/blog/about.php', '/about-us');
    Route::permanentRedirect('/blog/contact.php', '/contact');
    Route::permanentRedirect('/blog/safety.php', '/safety');
    Route::permanentRedirect('/blog/login.php', '/login');
    Route::permanentRedirect('/blog/blog.php', '/blog');
    Route::permanentRedirect('/blog/how-it-works.php', '/how-it-works');
    Route::permanentRedirect('/blog/coffee-date-ideas.php', '/coffee-date-ideas-in-himachal-pradesh');
    Route::get('/blog/{slug}.php', 'resolveLegacyBlog')->where('slug', '[A-Za-z0-9\-]+');
    Route::permanentRedirect('/tips-red-flags.php', '/blog/tips-red-flags');
    Route::permanentRedirect('/blog-how-to-report-suspicious-profile.php', '/blog/how-to-report-suspicious-profile');
    Route::permanentRedirect('/blog-mbti-personality-dating-compatibility.php', '/blog/mbti-personality-dating-compatibility');
    Route::get('/blog-{slug}.php', 'resolveLegacyBlog')->where('slug', '[A-Za-z0-9\-]+');
    Route::get('/blog-{slug}', 'resolveLegacyBlog')->where('slug', '[A-Za-z0-9\-]+');

    // Retired legacy dynamic profile/social URLs return clean 410 Gone so Search Console drops old 404s
    Route::any('/{legacy}', fn () => response()->view('errors.410', [], 410))
        ->where('legacy', '(profile\.php|user\.php|users\.php|match\.php|matches\.php|chat\.php|messages\.php|feed\.php|reels\.php|stories\.php)');
    Route::get('/{prefix}/{any}', fn () => response()->view('errors.410', [], 410))
        ->where('prefix', '(profile|user|u|post|p|reels|stories|match|chat)')
        ->where('any', '.*');
});

// OmeTV-Style Live Random Video Chat
Route::controller(LiveVideoController::class)->prefix('live-video')->name('live-video.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/join', 'join')->middleware('throttle:20,1')->name('join');
    Route::get('/poll', 'poll')->name('poll');
    Route::post('/signal', 'signal')->name('signal');
    Route::post('/chat', 'chat')->middleware('throttle:30,1')->name('chat');
    Route::post('/leave', 'leave')->name('leave');
    Route::post('/report', 'report')->name('report');
});

// Shareable Coffee Moments (Social Media & Website Feed)
Route::controller(MomentController::class)->prefix('moments')->name('moments.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->middleware(['auth', 'throttle:10,1'])->name('store');
    Route::get('/{moment}', 'show')->name('show');
    Route::post('/{moment}/share', 'share')->name('share');
});

// Café Reviews & Women-Friendly Date Spots
Route::controller(CafeReviewController::class)->prefix('cafes')->name('cafes.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->middleware(['auth', 'throttle:10,1'])->name('store');
    Route::post('/{review}/share', 'share')->name('share');
});

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->middleware('throttle:15,1')->name('login.submit');
    Route::get('/register', 'showRegister')->name('register');
    Route::get('/join', fn () => redirect()->route('register'))->name('join');
    Route::post('/register', 'register')->middleware('throttle:10,1')->name('register.submit');
    Route::post('/guest', 'continueAsGuest')->middleware('throttle:20,1')->name('auth.guest');
    Route::get('/forgot-password', 'showForgotPassword')->name('password.request');
    Route::post('/forgot-password', 'sendResetLink')->middleware('throttle:10,1')->name('password.email');
    Route::get('/reset-password/{token}', 'showResetPassword')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->middleware('throttle:10,1')->name('password.update');
    Route::post('/verify-email', 'verifyEmail')->middleware('auth')->name('verification.send');
    Route::post('/logout', 'logout')->name('logout');
});

// Authenticated User App Workspace
Route::middleware('auth')->prefix('app')->name('app.')->group(function () {
    Route::controller(AppController::class)->group(function () {
        Route::get('/discover', 'discover')->name('discover');
        Route::post('/swipe', 'swipe')->name('swipe');
        Route::get('/messages', 'messages')->name('messages');
        Route::post('/messages/{partner}', 'sendMessage')->name('messages.send');
        Route::get('/messages/{partner}/poll', 'pollMessages')->name('messages.poll');
        Route::delete('/messages/{partner}', 'deleteConversation')->name('messages.delete');
        Route::post('/block/{user}', 'blockUser')->name('block');
        Route::post('/report/{user}', 'reportUser')->name('report');
        Route::get('/coffee-dates', 'coffeeDates')->name('coffee-dates');
        Route::get('/dates', fn () => redirect()->route('app.coffee-dates'))->name('dates');
        Route::post('/coffee-dates', 'storeCoffeeDate')->name('coffee-dates.store');
        Route::patch('/coffee-dates/{coffeeDate}', 'respondCoffeeDate')->name('coffee-dates.respond');
        Route::get('/notifications', 'notifications')->name('notifications');
        Route::post('/notifications/read', 'markNotificationsRead')->name('notifications.read');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/profile', 'updateProfile')->name('profile.update');
        Route::post('/profile/verify', 'requestVerification')->name('profile.verify');
        Route::post('/profile/premium', 'upgradePremium')->name('profile.premium');
        Route::delete('/profile', 'deleteAccount')->name('profile.delete');
    });

    Route::controller(VideoCallController::class)->prefix('video-call')->name('video.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/start', 'start')->name('start');
        Route::get('/incoming', 'incoming')->name('incoming');
        Route::post('/{call}/status', 'updateStatus')->name('status');
        Route::post('/{call}/signal', 'sendSignal')->name('signal');
        Route::get('/{call}/signals', 'pollSignals')->name('signals');
    });
});

// Secure Role-Based Admin Dashboard
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');

    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/analytics/live', [AdminController::class, 'liveAnalytics'])->name('analytics.live');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::get('/moderation', [AdminController::class, 'moderation'])->middleware('admin:super_admin,moderator,support_admin')->name('moderation');
        Route::post('/reports/{report}', [AdminController::class, 'resolveReport'])->middleware('admin:super_admin,moderator')->name('reports.resolve');
        Route::post('/messages/{message}', [AdminController::class, 'moderateMessage'])->middleware('admin:super_admin,moderator')->name('messages.moderate');
        Route::post('/moments/{moment}', [AdminController::class, 'moderateMoment'])->middleware('admin:super_admin,moderator')->name('moments.moderate');
        Route::post('/cafes/{review}', [AdminController::class, 'moderateCafeReview'])->middleware('admin:super_admin,moderator')->name('cafes.moderate');
        Route::post('/tickets/{ticket}', [AdminController::class, 'replyTicket'])->middleware('admin:super_admin,support_admin')->name('tickets.reply');
        Route::get('/content', [AdminController::class, 'content'])->middleware('admin:super_admin,content_manager')->name('content');
        Route::post('/cities', [AdminController::class, 'storeCity'])->middleware('admin:super_admin,content_manager')->name('cities.store');
        Route::post('/interests', [AdminController::class, 'storeInterest'])->middleware('admin:super_admin,content_manager')->name('interests.store');
        Route::post('/blog', [AdminController::class, 'storeBlog'])->middleware('admin:super_admin,content_manager')->name('blog.store');
        Route::get('/settings', [AdminController::class, 'settings'])->middleware('admin:super_admin,content_manager')->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->middleware('admin:super_admin,content_manager')->name('settings.update');
    });
});

// Final Legacy Catch for Bare City/State/Topic Slugs from 2026 Search Console 404 Drilldown
Route::get('/{slug}', [PublicController::class, 'resolveBareLegacy'])
    ->where('slug', '[A-Za-z0-9\-_]+(?:\.php)?');
