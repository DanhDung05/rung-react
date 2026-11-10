<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Tour;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ============================================================
        // THỐNG KÊ CHÍNH
        // ============================================================
        $stats = [
            'tours_total'        => Tour::count(),
            'tours_published'    => Tour::where('status', Tour::STATUS_PUBLISHED)->count(),
            'tours_draft'        => Tour::where('status', Tour::STATUS_DRAFT)->count(),

            'posts_total'        => Post::count(),
            'posts_published'    => Post::where('status', Post::STATUS_PUBLISHED)->count(),
            'posts_draft'        => Post::where('status', Post::STATUS_DRAFT)->count(),

            'destinations_total' => Destination::count(),
            'categories_total'   => Category::count(),

            'contacts_total'     => Contact::count(),
            'contacts_new'       => Contact::where('status', Contact::STATUS_NEW)->count(),
            'contacts_read'      => Contact::where('status', Contact::STATUS_READ)->count(),
            'contacts_replied'   => Contact::where('status', Contact::STATUS_REPLIED)->count(),
            'contacts_archived'  => Contact::where('status', Contact::STATUS_ARCHIVED)->count(),

            'faqs_total'         => Faq::count(),
            'pages_total'        => Page::count(),
            'sliders_total'      => Slider::count(),
            'menus_total'        => Menu::count(),
            'users_total'        => User::count(),
        ];

        // ============================================================
        // THÙNG RÁC
        // ============================================================
        $trash = [
            'tours'    => Tour::onlyTrashed()->count(),
            'posts'    => Post::onlyTrashed()->count(),
            'contacts' => Contact::onlyTrashed()->count(),
        ];

        // ============================================================
        // TOP XEM NHIỀU
        // ============================================================
        $topTours = Tour::query()
            ->orderByDesc('view_count')
            ->take(5)
            ->get(['id', 'name', 'slug', 'view_count']);

        $topPosts = Post::query()
            ->orderByDesc('view_count')
            ->take(5)
            ->get(['id', 'title', 'slug', 'view_count']);

        // ============================================================
        // PHÂN LOẠI
        // ============================================================
        $tourByDifficulty = Tour::query()
            ->select('difficulty', DB::raw('count(*) as total'))
            ->groupBy('difficulty')
            ->pluck('total', 'difficulty')
            ->toArray();

        // ============================================================
        // BIỂU ĐỒ 7 NGÀY — CONTACT
        // ============================================================
        $chartContacts = [];
        $maxContact = 1;

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Contact::whereDate('created_at', $date)->count();

            $chartContacts[] = [
                'date'  => $date->format('d/m'),
                'label' => $date->format('D'),
                'count' => $count,
            ];

            if ($count > $maxContact) $maxContact = $count;
        }

        // ============================================================
        // 5 MỤC MỚI NHẤT
        // ============================================================
        $latestContacts = Contact::latestFirst()->take(5)->get();

        $latestTours = Tour::with('category')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $latestPosts = Post::with('category')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // ============================================================
        // MENU
        // ============================================================
        $menus = Menu::withCount('allItems')
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Dashboard', compact(
            'stats',
            'trash',
            'topTours',
            'topPosts',
            'tourByDifficulty',
            'chartContacts',
            'maxContact',
            'latestContacts',
            'latestTours',
            'latestPosts',
            'menus'
        ));
    }

    /**
     * Trả về JSON số liệu dashboard cho AJAX real-time.
     */
    public function stats(): JsonResponse
    {
        // Đếm các số liệu chính
        $stats = [
            'contacts_total'    => Contact::count(),
            'contacts_new'      => Contact::where('status', Contact::STATUS_NEW)->count(),
            'contacts_read'     => Contact::where('status', Contact::STATUS_READ)->count(),
            'contacts_replied'  => Contact::where('status', Contact::STATUS_REPLIED)->count(),
            'contacts_archived' => Contact::where('status', Contact::STATUS_ARCHIVED)->count(),
            'tours_total'       => Tour::count(),
            'posts_total'       => Post::count(),
        ];

        // 5 liên hệ mới nhất (để cập nhật dropdown chuông)
        $latestContacts = Contact::where('status', Contact::STATUS_NEW)
            ->latestFirst()
            ->take(5)
            ->get()
            ->map(fn($c) => [
                'id'     => $c->id,
                'name'   => $c->name,
                'email'  => $c->email,
                'url'    => route('admin.contacts.show', $c),
                'time'   => $c->created_at->diffForHumans(),
                'initial' => strtoupper(substr($c->name, 0, 1)),
            ]);

        return response()->json([
            'stats'          => $stats,
            'latestContacts' => $latestContacts,
        ]);
    }
}