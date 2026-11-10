<?php

namespace App\Http\Controllers\Frontend;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DestinationController extends Controller
{
    /**
     * Danh sách điểm đến.
     */
    public function index(Request $request)
    {
        $query = Destination::query()
            ->published()
            ->withCount('tours');

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('featured') && $request->featured === '1') {
            $query->featured();
        }

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('province', 'like', "%{$keyword}%");
            });
        }

        $sort = $request->get('sort', 'default');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name');
                break;
            case 'name_desc':
                $query->orderByDesc('name');
                break;
            case 'featured':
                $query->orderByDesc('is_featured')->orderBy('sort_order');
                break;
            default:
                $query->orderBy('sort_order')->orderBy('name');
                break;
        }

        $destinations = $query->paginate(12)->withQueryString();

        // Cache regions 1 giờ
        $regions = Cache::remember('destination_regions', 3600, function () {
            return Destination::query()
                ->published()
                ->whereNotNull('region')
                ->distinct()
                ->orderBy('region')
                ->pluck('region');
        });

        return Inertia::render('Destination/Index', compact('destinations', 'regions'));
    }

    /**
     * Chi tiết điểm đến.
     */
    public function show(string $slug)
    {
        $destination = Destination::query()
            ->published()
            ->where('slug', $slug)
            ->with([
                'parent',
                'children' => function ($q) {
                    $q->published()->orderBy('sort_order');
                },
                'tours' => function ($q) {
                    $q->published()
                      ->with(['category'])
                      ->orderByDesc('published_at');
                },
            ])
            ->firstOrFail();

        return Inertia::render('Destination/Show', compact('destination'));
    }
}