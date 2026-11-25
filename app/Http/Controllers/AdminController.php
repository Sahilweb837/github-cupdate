<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\City;
use App\Models\CoffeeDate;
use App\Models\ContactMessage;
use App\Models\InterestCategory;
use App\Models\Message;
use App\Models\SiteSetting;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\UserReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], (string) $user->password_hash) || ! $user->isAdmin()) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Invalid administrator credentials.']);
        }

        if ($user->status === 'suspended') {
            return back()->withErrors(['email' => 'This administrator account is suspended.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $user->forceFill(['last_active' => now()])->saveQuietly();

        return redirect()->route('admin.dashboard');
    }

    public function dashboard(Request $request): View
    {
        $stats = [
            'total_users' => User::withTrashed()->count(),
            'active_users' => User::where('status', 'active')->count(),
            'new_users' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'verified_users' => User::where('is_verified', true)->count(),
            'reported_accounts' => UserReport::where('status', 'pending')->count(),
            'suspended_accounts' => User::where('status', 'suspended')->count(),
            'deleted_accounts' => User::onlyTrashed()->count(),
            'coffee_dates' => CoffeeDate::count(),
            'open_tickets' => SupportTicket::where('status', '!=', 'resolved')->count(),
        ];

        $recentUsers = User::withTrashed()->latest('created_at')->take(8)->get();
        $recentReports = UserReport::with(['reporter', 'reportedUser'])->latest('created_at')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentReports'));
    }

    public function users(Request $request): View
    {
        $query = User::withTrashed();

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            if ($status === 'deleted') {
                $query->onlyTrashed();
            } elseif ($status === 'verified') {
                $query->where('is_verified', true);
            } else {
                $query->where('status', $status);
            }
        }

        $users = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function updateUser(Request $request, int $id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($request->input('action') === 'toggle_verify') {
            $next = ! $user->is_verified;
            $user->update([
                'is_verified' => $next,
                'verification_status' => $next ? 'verified' : 'unverified',
            ]);
        } elseif ($request->input('action') === 'toggle_suspend') {
            $user->update([
                'status' => $user->status === 'active' ? 'suspended' : 'active',
                'is_online' => false,
            ]);
        } elseif ($request->input('action') === 'delete' && $user->id !== $request->user()->id) {
            $user->delete();
        } elseif ($request->input('action') === 'restore') {
            $user->restore();
        } elseif ($request->filled('role') && $request->user()->hasAdminRole('super_admin')) {
            $role = $request->validate(['role' => ['required', 'in:user,super_admin,moderator,support_admin,content_manager']])['role'];
            $user->update([
                'role' => $role,
                'is_admin' => $role !== 'user',
            ]);
        }

        return back()->with('success', "Updated user record for {$user->full_name}.");
    }

    public function moderation(): View
    {
        $reports = UserReport::with(['reporter', 'reportedUser'])->latest('created_at')->take(25)->get();
        $messages = Message::with(['sender', 'receiver'])->latest('created_at')->take(25)->get();
        $tickets = SupportTicket::latest()->take(20)->get();
        $contacts = ContactMessage::latest('created_at')->take(20)->get();

        return view('admin.moderation', compact('reports', 'messages', 'tickets', 'contacts'));
    }

    public function resolveReport(Request $request, UserReport $report): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:resolved,dismissed']]);
        $report->update(['status' => $data['status']]);

        return back()->with('success', 'Report status updated.');
    }

    public function moderateMessage(Request $request, Message $message): RedirectResponse
    {
        if ($request->input('action') === 'flag') {
            $message->update(['is_flagged' => ! $message->is_flagged]);
        } elseif ($request->input('action') === 'delete') {
            $message->delete();
        }

        return back()->with('success', 'Message moderation action completed.');
    }

    public function replyTicket(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved'],
            'admin_reply' => ['nullable', 'string', 'max:2000'],
        ]);

        $ticket->update($data);

        return back()->with('success', 'Support ticket updated.');
    }

    public function content(): View
    {
        $cities = City::orderBy('sort_order')->get();
        $interests = InterestCategory::orderBy('name')->get();
        $posts = BlogPost::latest()->get();

        return view('admin.content', compact('cities', 'interests', 'posts'));
    }

    public function storeCity(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:400'],
        ]);

        City::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'state' => $data['state'],
            'description' => $data['description'] ?? '',
            'is_active' => true,
            'sort_order' => City::count() + 1,
        ]);

        return back()->with('success', 'City added.');
    }

    public function storeInterest(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:80']]);
        InterestCategory::firstOrCreate(
            ['slug' => Str::slug($data['name'])],
            ['name' => $data['name'], 'is_active' => true]
        );

        return back()->with('success', 'Interest category added.');
    }

    public function storeBlog(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'excerpt' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'meta_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:255'],
        ]);

        BlogPost::create([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']) . '-' . Str::random(4),
            'excerpt' => $data['excerpt'],
            'body' => $data['body'],
            'cover_image' => 'images/cafe-blue-bottle.jpg',
            'author_id' => $request->user()->id,
            'meta_title' => $data['meta_title'] ?: $data['title'],
            'meta_description' => $data['meta_description'] ?: $data['excerpt'],
            'is_published' => true,
            'published_at' => now(),
        ]);

        return back()->with('success', 'Blog article published.');
    }

    public function settings(): View
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $fields = $request->except('_token');
        foreach ($fields as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => (string) $value]
            );
        }

        return back()->with('success', 'Platform, SEO, homepage, and advertisement settings saved to MySQL.');
    }
}
