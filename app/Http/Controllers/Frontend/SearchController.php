<?php

namespace App\Http\Controllers\Frontend;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tour;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Tìm kiếm tổng hợp.
     */
    public function index(Request $request)
    {
        $keyword = trim($request->get('q', ''));
        $type    = $request->get('type', 'all'); // all | tour | destination | post | page

        $results = [
            'tours'        => collect(),
            'destinations' => collect(),
            'posts'        => collect(),
            'pages'        => collect(),
        ];

        $total = 0;

        if ($keyword !== '') {
            // Tìm Tour
            if ($type === 'all' || $type === 'tour') {
                $results['tours'] = Tour::query()
                    ->published()
                    ->where(function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                          ->orWhere('excerpt', 'like', "%{$keyword}%");
                    })
                    ->with(['category'])
                    ->orderByDesc('published_at')
                    ->limit(8)
                    ->get();
            }

            // Tìm Destination
            if ($type === 'all' || $type === 'destination') {
                $results['destinations'] = Destination::query()
                    ->published()
                    ->where(function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                          ->orWhere('description', 'like', "%{$keyword}%")
                          ->orWhere('province', 'like', "%{$keyword}%");
                    })
                    ->withCount('tours')
                    ->limit(8)
                    ->get();
            }

            // Tìm Post
            if ($type === 'all' || $type === 'post') {
                $results['posts'] = Post::query()
                    ->published()
                    ->where(function ($q) use ($keyword) {
                        $q->where('title', 'like', "%{$keyword}%")
                          ->orWhere('excerpt', 'like', "%{$keyword}%");
                    })
                    ->with(['category', 'author'])
                    ->orderByDesc('published_at')
                    ->limit(8)
                    ->get();
            }

            // Tìm Page
            if ($type === 'all' || $type === 'page') {
                $results['pages'] = Page::query()
                    ->published()
                    ->where(function ($q) use ($keyword) {
                        $q->where('title', 'like', "%{$keyword}%")
                          ->orWhere('content', 'like', "%{$keyword}%");
                    })
                    ->limit(5)
                    ->get();
            }

            $total = $results['tours']->count()
                   + $results['destinations']->count()
                   + $results['posts']->count()
                   + $results['pages']->count();
        }

        return Inertia::render('Search/Index', compact('keyword', 'type', 'results', 'total'));
    }
}