<?php

namespace App\Http\Controllers\Frontend;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * Trang FAQ.
     */
    public function index()
    {
        $faqs = Faq::query()
            ->active()
            ->ordered()
            ->get()
            ->groupBy('category');

        return Inertia::render('Pages/Faq', compact('faqs'));
    }
}