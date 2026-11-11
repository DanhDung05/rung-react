<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class GuideController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Guide/Index', [
            'guide' => config('admin_guide', []),
        ]);
    }
}
