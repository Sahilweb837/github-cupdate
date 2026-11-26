<?php

namespace App\Http\Controllers;

use App\Models\CafeReview;
use App\Models\DatePlace;
use App\Services\SafetyModerationService;
use App\Support\SqlSafe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CafeReviewController extends Controller
{
    public function index(Request $request): View
    {
        $datePlaces = Schema::hasTable('date_places')
            ? DatePlace::orderByDesc('rating')->get()
            : collect();

        $reviews = collect();
        if (Schema::hasTable('cafe_reviews')) {
            $query = CafeReview::with('user')
                ->where('is_approved', true)
                ->where('is_flagged', false)
                ->latest('created_at');

            if ($city = $request->query('city')) {
                $query->where('city', 'like', '%' . SqlSafe::escapeLike((string) $city) . '%');
            }
            if ($tag = $request->query('tag')) {
                $query->where('safety_tag', $tag);
            }

            $reviews = $query->paginate(12)->withQueryString();
        }

        return view('pages.cafes', [
            'datePlaces' => $datePlaces,
            'reviews' => $reviews,
            'activeCity' => $request->query('city'),
            'activeTag' => $request->query('tag'),
        ]);
    }

    public function store(Request $request, SafetyModerationService $safety): RedirectResponse
    {
        $data = $request->validate([
            'date_place_id' => ['nullable', 'integer', 'exists:date_places,id'],
            'cafe_name' => ['required', 'string', 'min:2', 'max:140'],
            'city' => ['required', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'safety_tag' => ['required', 'string', 'max:60'],
            'review_text' => ['required', 'string', 'min:8', 'max:800'],
        ]);

        $check = $safety->screenText($data['cafe_name'] . ' ' . $data['review_text']);
        if (! $check['allowed']) {
            return back()->withInput()->withErrors(['review_text' => $check['reason']]);
        }

        CafeReview::create([
            'user_id' => $request->user()->id,
            'date_place_id' => $data['date_place_id'] ?? null,
            'cafe_name' => trim($data['cafe_name']),
            'city' => trim($data['city']),
            'rating' => (int) $data['rating'],
            'safety_tag' => $data['safety_tag'],
            'review_text' => trim($data['review_text']),
            'is_approved' => ! $check['flagged'],
            'is_flagged' => $check['flagged'],
            'created_at' => now(),
        ]);

        $msg = $check['flagged']
            ? 'Your café review was submitted for quick safety review.'
            : 'Thank you! Your café review is live and ready to share.';

        return redirect()->route('cafes.index')->with('success', $msg);
    }

    public function share(CafeReview $review): JsonResponse
    {
        $review->increment('shares_count');

        return response()->json(['ok' => true, 'shares' => $review->shares_count]);
    }
}
