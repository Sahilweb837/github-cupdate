<?php

namespace App\Http\Controllers;

use App\Models\Moment;
use App\Services\SafetyModerationService;
use App\Support\SqlSafe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MomentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Moment::with('user')->latest('created_at');

        if (Schema::hasColumn('posts', 'is_approved')) {
            $query->where('is_approved', true)->where('is_flagged', false);
        }

        if ($city = $request->query('city')) {
            if (Schema::hasColumn('posts', 'city')) {
                $query->where('city', $city);
            }
        }

        if ($mood = $request->query('mood')) {
            if (Schema::hasColumn('posts', 'mood')) {
                $query->where('mood', $mood);
            }
        }

        $moments = $query->paginate(12)->withQueryString();

        return view('pages.moments', [
            'moments' => $moments,
            'activeCity' => $request->query('city'),
            'activeMood' => $request->query('mood'),
        ]);
    }

    public function show(Moment $moment): View
    {
        $moment->load('user');

        return view('pages.moment-show', [
            'moment' => $moment,
        ]);
    }

    public function store(Request $request, SafetyModerationService $safety): RedirectResponse
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'min:4', 'max:600'],
            'city' => ['nullable', 'string', 'max:100'],
            'mood' => ['nullable', 'string', 'max:60'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $textCheck = $safety->screenText($data['content']);
        if (! $textCheck['allowed']) {
            return back()->withInput()->withErrors(['content' => $textCheck['reason']]);
        }

        $imageFlagged = false;
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imgCheck = $safety->screenImage($file);
            if (! $imgCheck['allowed']) {
                return back()->withInput()->withErrors(['image' => $imgCheck['reason']]);
            }
            $imageFlagged = $imgCheck['flagged'];

            $dir = public_path('uploads/moments');
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $filename = 'moment_' . $request->user()->id . '_' . Str::random(10) . '.' . SqlSafe::safeImageExtension($file);
            $file->move($dir, $filename);
            $imageUrl = 'uploads/moments/' . $filename;
        }

        $payload = [
            'user_id' => $request->user()->id,
            'content' => trim($data['content']),
            'image_url' => $imageUrl,
            'created_at' => now(),
        ];

        $isFlagged = $textCheck['flagged'] || $imageFlagged;

        if (Schema::hasColumn('posts', 'city')) {
            $payload['city'] = $data['city'] ?: $request->user()->city;
        }
        if (Schema::hasColumn('posts', 'mood')) {
            $payload['mood'] = $data['mood'] ?: 'Coffee Chat';
        }
        if (Schema::hasColumn('posts', 'is_flagged')) {
            $payload['is_flagged'] = $isFlagged;
        }
        if (Schema::hasColumn('posts', 'is_approved')) {
            $payload['is_approved'] = ! $isFlagged;
        }

        Moment::create($payload);

        $msg = $isFlagged
            ? 'Your moment was submitted and queued for quick safety review.'
            : 'Coffee moment shared! Use the social buttons to share it with friends.';

        return redirect()->route('moments.index')->with('success', $msg);
    }

    public function share(Moment $moment): JsonResponse
    {
        if (Schema::hasColumn('posts', 'shares_count')) {
            $moment->increment('shares_count');
        }

        return response()->json([
            'ok' => true,
            'shares' => (int) ($moment->shares_count ?? 0),
        ]);
    }
}
