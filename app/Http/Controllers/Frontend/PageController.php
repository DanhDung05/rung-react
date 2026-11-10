<?php

namespace App\Http\Controllers\Frontend;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;

class PageController extends Controller
{
    /**
     * Trang giới thiệu.
     * Slug lấy từ setting `about_page_slug`, mặc định `gioi-thieu`.
     */
    public function about()
    {
        $slug = Setting::get('about_page_slug', 'gioi-thieu');

        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->first();

        if (!$page) {
            abort(404, 'Trang giới thiệu chưa được tạo.');
        }

        return Inertia::render('Pages/About', compact('page'));
    }

    /**
     * Trang tĩnh chung theo slug.
     */
    public function show(string $slug)
    {
        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Pages/Show', compact('page'));
    }
}