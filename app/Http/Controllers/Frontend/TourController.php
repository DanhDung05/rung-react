<?php

namespace App\Http\Controllers\Frontend;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TourController extends Controller
{
    /**
     * Danh sách tour với filter + sort + pagination.
     */
    public function index(Request $request)
    {
        $query = Tour::query()
            ->published()
            ->with(['category']);   // Bỏ 'destinations' — không dùng trong card

        // Filter: danh mục
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter: độ khó
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Filter: khoảng giá
        if ($request->filled('price_min')) {
            $query->where('price_reference', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price_reference', '<=', $request->price_max);
        }

        // Search
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('excerpt', 'like', "%{$keyword}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw('price_reference IS NULL, price_reference ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('price_reference IS NULL, price_reference DESC');
                break;
            case 'popular':
                $query->orderByDesc('view_count');
                break;
            case 'newest':
            default:
                $query->orderByDesc('published_at');
                break;
        }

        $tours = $query->paginate(12)->withQueryString();

        // Cache categories 1 giờ
        $categories = Cache::remember('tour_categories', 3600, function () {
            return Category::where('type', 'tour')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        });

        $difficulties = [
            'easy'     => 'Dễ',
            'moderate' => 'Trung bình',
            'hard'     => 'Khó',
            'expert'   => 'Chuyên gia',
        ];

        return Inertia::render('Tour/Index', compact('tours', 'categories', 'difficulties'));
    }

    /**
     * Tour theo danh mục.
     */
    public function category(string $slug, Request $request)
    {
        $category = Category::where('type', 'tour')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Redirect về index với filter category
        return redirect()->route('tours.index', ['category' => $slug]);
    }

    /**
     * Chi tiết tour.
     */
    public function show(string $slug)
    {
        $tour = Tour::query()
            ->published()
            ->where('slug', $slug)
            ->with([
                'category',
                'destinations',
                'itineraries' => function ($q) {
                    $q->orderBy('day_number');
                },
            ])
            ->firstOrFail();

        // Tăng view_count (1 lần/session)
        $viewedKey = 'tour_viewed_' . $tour->id;
        if (!session()->has($viewedKey)) {
            $tour->increment('view_count');
            session()->put($viewedKey, true);
        }

        // Cache related tours 10 phút — key theo category_id
        $relatedTours = Cache::remember("tour_related_{$tour->category_id}", 600, function () use ($tour) {
            return Tour::query()
                ->published()
                ->where('id', '!=', $tour->id)
                ->where('category_id', $tour->category_id)
                ->with(['category'])
                ->orderByDesc('published_at')
                ->limit(3)
                ->get();
        });

        return Inertia::render('Tour/Show', compact('tour', 'relatedTours'));
    }
}