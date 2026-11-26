<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\InterestCategory;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], (string) $user->password_hash)) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Invalid email or password.']);
        }

        if ($user->status === 'suspended') {
            return back()->withErrors(['email' => 'Your account has been suspended. Please contact support.']);
        }

        $rehash = Hash::needsRehash((string) $user->password_hash);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $request->session()->forget('guest_mode');

        $updates = [
            'is_online' => true,
            'last_active' => now(),
            'last_login_date' => now()->toDateString(),
        ];
        if ($rehash) {
            $updates['password_hash'] = Hash::make($credentials['password']);
        }
        $user->forceFill($updates)->saveQuietly();

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('app.discover'));
    }

    public function showRegister(): View
    {
        return view('auth.register', [
            'cities' => City::where('is_active', true)->orderBy('sort_order')->get(),
            'interests' => InterestCategory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'dob' => ['required', 'date', 'before:-18 years'],
            'gender' => ['required', 'in:male,female,nonbinary,other'],
            'preference' => ['required', 'in:male,female,everyone'],
            'city' => ['required', 'string', 'max:100'],
            'coffee_preference' => ['nullable', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:500'],
            'interests' => ['nullable', 'array'],
        ], [
            'dob.before' => 'You must be at least 18 years old to join CupDate.',
        ]);

        $cityState = City::where('name', $data['city'])->value('state') ?: 'India';

        $user = User::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'dob' => $data['dob'],
            'gender' => $data['gender'],
            'preference' => $data['preference'],
            'city' => $data['city'],
            'state' => $cityState,
            'coffee_preference' => $data['coffee_preference'] ?? 'Cappuccino',
            'bio' => $data['bio'] ?? 'Here for genuine conversations and good coffee.',
            'interests' => json_encode($data['interests'] ?? ['Coffee Dates']),
            'avatar' => 'images/cafe-blue-bottle.jpg',
            'role' => 'user',
            'status' => 'active',
            'profile_visible' => true,
            'is_online' => true,
            'last_active' => now(),
        ]);

        UserNotification::create([
            'user_id' => $user->id,
            'type' => 'security',
            'title' => 'Welcome to CupDate!',
            'content' => 'Complete your profile photo and verify your email to earn a trusted badge.',
            'is_read' => false,
            'created_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget('guest_mode');

        return redirect()->route('app.profile')->with('success', 'Account created and saved! Add your photo or start discovering.');
    }

    public function continueAsGuest(Request $request): RedirectResponse
    {
        $request->session()->put('guest_mode', true);
        return redirect()->route('public.discover')->with('info', 'Exploring CupDate in Guest Mode. Create a free account anytime to message or video call.');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $user = User::where('email', $data['email'])->first();

        if ($user) {
            $token = Str::random(48);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => Hash::make($token), 'created_at' => now()]
            );

            return back()->with('reset_link', route('password.reset', ['token' => $token, 'email' => $user->email]))
                ->with('success', 'Password reset link generated! Use the verification link below to set a new password.');
        }

        return back()->with('success', 'If that email exists in CupDate, a password reset link has been prepared.');
    }

    public function showResetPassword(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $data['email'])->first();
        if (! $record || ! Hash::check($data['token'], $record->token)) {
            return back()->withErrors(['email' => 'This password reset token is invalid or expired.']);
        }

        User::where('email', $data['email'])->update([
            'password_hash' => Hash::make($data['password']),
        ]);

        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return redirect()->route('login')->with('success', 'Your password has been updated. Please log in.');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        if ($user = $request->user()) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        return back()->with('success', 'Email verified successfully!');
    }

    public function logout(Request $request): RedirectResponse
    {
        if ($user = $request->user()) {
            $user->forceFill(['is_online' => false])->saveQuietly();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
