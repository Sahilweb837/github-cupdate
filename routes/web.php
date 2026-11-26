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

    // Legacy Search Console / PHP URL compatibility (prevents 404s, Soft 404s & canonical drift)
    Route::get('/city.php', 'legacyCityQuery');
    Route::get('/city/{slug}', 'cityLanding')->where('slug', '[a-z0-9\-]+');
    Route::get('/dating-in-{slug}', 'cityLanding')->where('slug', '[a-z0-9\-]+');
    Route::get('/dating-{slug}', 'cityLanding')->where('slug', '[a-z0-9\-]+');
    Route::get('/community/{slug}', fn (string $slug) => app(PublicController::class)->guide('community-' . $slug));
    Route::get('/rishta', fn () => app(PublicController::class)->guide('rishta'));
    Route::get('/dates', 'howItWorks');
    Route::get('/coffee-date-ideas', fn () => app(PublicController::class)->seoLanding('coffee-date-ideas-in-himachal-pradesh'));
    Route::get('/privacy', fn () => app(PublicController::class)->legal('privacy-policy'));
    Route::get('/terms', fn () => app(PublicController::class)->legal('terms-and-conditions'));

    Route::get('/faq.php', fn () => app(PublicController::class)->guide('faq'));
    Route::get('/disclaimer.php', fn () => app(PublicController::class)->guide('disclaimer'));
    Route::get('/community-guidelines.php', fn () => app(PublicController::class)->legal('community-guidelines'));
    Route::get('/privacy.php', fn () => app(PublicController::class)->legal('privacy-policy'));
    Route::get('/terms.php', fn () => app(PublicController::class)->legal('terms-and-conditions'));
    Route::get('/about.php', 'about');
    Route::get('/contact.php', 'contact');
    Route::get('/safety.php', 'safety');
    Route::get('/blog.php', 'blog');
    Route::get('/discover.php', 'discover');
    Route::get('/tips-red-flags.php', fn () => app(PublicController::class)->guide('tips-red-flags'));
    Route::get('/blog-how-to-report-suspicious-profile.php', fn () => app(PublicController::class)->guide('how-to-report-suspicious-profile'));
    Route::get('/blog-mbti-personality-dating-compatibility.php', fn () => app(PublicController::class)->guide('mbti-personality-dating-compatibility'));
    Route::get('/blog-{slug}.php', 'blogShow')->where('slug', '[a-z0-9\-]+');
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
