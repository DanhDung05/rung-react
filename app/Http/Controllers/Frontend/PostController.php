<?php

namespace App\Http\Controllers\Frontend;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Danh sách bài viết.
     */
    public function index(Request $request)
    {
        $query = Post::query()
            ->published()
            ->with(['category', 'author']);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('excerpt', 'like', "%{$keyword}%");
            });
        }

        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'popular':
                $query->orderByDesc('view_count');
                break;
            case 'oldest':
                $query->orderBy('published_at');
                break;
            case 'newest':
            default:
                $query->orderByDesc('published_at');
                break;
        }

        $posts = $query->paginate(9)->withQueryString();

        // Sidebar data — cache
        $categories = Cache::remember('post_categories', 3600, function () {
            return Category::where('type', 'post')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        });

        $recentPosts = Cache::remember('post_recent', 600, function () {
            return Post::query()
                ->published()
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(5)
                ->get();
        });

        $popularTags = Cache::remember('post_popular_tags', 3600, function () {
            return Tag::query()
                ->has('posts')
                ->withCount('posts')
                ->orderByDesc('posts_count')
                ->limit(15)
                ->get();
        });

        return Inertia::render('Blog/Index', compact('posts', 'categories', 'recentPosts', 'popularTags'));
    }

    /**
     * Bài viết theo danh mục.
     */
    public function category(string $slug, Request $request)
    {
        $category = Category::where('type', 'post')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $query = Post::query()
            ->published()
            ->with(['category', 'author'])
            ->where('category_id', $category->id);

        $posts = $query->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Cache::remember('post_categories', 3600, function () {
            return Category::where('type', 'post')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        });

        $recentPosts = Cache::remember('post_recent', 600, function () {
            return Post::query()
                ->published()
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(5)
                ->get();
        });

        $popularTags = Cache::remember('post_popular_tags', 3600, function () {
            return Tag::query()
                ->has('posts')
                ->withCount('posts')
                ->orderByDesc('posts_count')
                ->limit(15)
                ->get();
        });

        return Inertia::render('Blog/Category', compact('category', 'posts', 'categories', 'recentPosts', 'popularTags'));
    }

    /**
     * Bài viết theo tag.
     */
    public function tag(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = Post::query()
            ->published()
            ->with(['category', 'author'])
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            })
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Cache::remember('post_categories', 3600, function () {
            return Category::where('type', 'post')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        });

        $recentPosts = Cache::remember('post_recent', 600, function () {
            return Post::query()
                ->published()
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(5)
                ->get();
        });

        $popularTags = Cache::remember('post_popular_tags', 3600, function () {
            return Tag::query()
                ->has('posts')
                ->withCount('posts')
                ->orderByDesc('posts_count')
                ->limit(15)
                ->get();
        });

        return Inertia::render('Blog/Tag', compact('tag', 'posts', 'categories', 'recentPosts', 'popularTags'));
    }

    /**
     * Chi tiết bài viết.
     */
    public function show(string $slug)
    {
        $post = Post::query()
            ->published()
            ->where('slug', $slug)
            ->with(['category', 'author', 'tags'])
            ->firstOrFail();

        // Tăng view_count 1 lần/session
        $viewedKey = 'post_viewed_' . $post->id;
        if (!session()->has($viewedKey)) {
            $post->increment('view_count');
            session()->put($viewedKey, true);
        }

        // Bài viết liên quan — cache theo category
        $relatedPosts = Cache::remember("post_related_{$post->category_id}", 600, function () use ($post) {
            return Post::query()
                ->published()
                ->where('id', '!=', $post->id)
                ->where('category_id', $post->category_id)
                ->with(['category'])
                ->orderByDesc('published_at')
                ->limit(3)
                ->get();
        });

        // Sidebar — cache
        $recentPosts = Cache::remember('post_recent', 600, function () {
            return Post::query()
                ->published()
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(5)
                ->get();
        });

        $popularTags = Cache::remember('post_popular_tags', 3600, function () {
            return Tag::query()
                ->has('posts')
                ->withCount('posts')
                ->orderByDesc('posts_count')
                ->limit(15)
                ->get();
        });

        return Inertia::render('Blog/Show', compact('post', 'relatedPosts', 'recentPosts', 'popularTags'));
    }
}