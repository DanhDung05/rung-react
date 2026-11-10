<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Danh sách FAQ.
     */
    public function index(Request $request)
    {
        $query = Faq::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('keyword')) {
            $query->where('question', 'like', '%' . $request->keyword . '%');
        }

        $faqs = $query
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        // Lấy danh sách category để filter
        $categories = Faq::query()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return Inertia::render('Admin/Faqs/Index', compact('faqs', 'categories'));
    }

    /**
     * Form tạo mới.
     */
    public function create()
    {
        return Inertia::render('Admin/Faqs/Create');
    }

    /**
     * Lưu FAQ mới.
     */
    public function store(StoreFaqRequest $request)
    {
        $data = $request->validated();

        Faq::create($data);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'Đã thêm câu hỏi thành công.');
    }

    /**
     * Chi tiết FAQ.
     */
    public function show(Faq $faq)
    {
        return Inertia::render('Admin/Faqs/Show', compact('faq'));
    }

    /**
     * Form chỉnh sửa.
     */
    public function edit(Faq $faq)
    {
        return Inertia::render('Admin/Faqs/Edit', compact('faq'));
    }

    /**
     * Cập nhật FAQ.
     */
    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $data = $request->validated();

        $faq->update($data);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'Đã cập nhật câu hỏi thành công.');
    }

    /**
     * XÓA MỀM.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'Đã chuyển câu hỏi vào thùng rác.');
    }

    /**
     * THÙNG RÁC.
     */
    public function trash(Request $request)
    {
        $query = Faq::onlyTrashed();

        if ($request->filled('keyword')) {
            $query->where('question', 'like', '%' . $request->keyword . '%');
        }

        $faqs = $query
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Faqs/Trash', compact('faqs'));
    }

    /**
     * KHÔI PHỤC.
     */
    public function restore(int $id)
    {
        $faq = Faq::onlyTrashed()->findOrFail($id);
        $faq->restore();

        return back()->with('success', 'Đã khôi phục câu hỏi thành công.');
    }

    /**
     * XÓA CỨNG — Faq độc lập, luôn xóa được.
     */
    public function forceDestroy(int $id)
    {
        $faq = Faq::onlyTrashed()->findOrFail($id);
        $faq->forceDelete();

        return back()->with('success', 'Đã xóa vĩnh viễn câu hỏi.');
    }
}