<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactStatusRequest;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Danh sách liên hệ.
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Lọc theo status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Mặc định: chỉ hiện chưa archive
            $query->where('status', '!=', 'archived');
        }

        // Tìm kiếm
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%')
                  ->orWhere('subject', 'like', '%' . $request->keyword . '%');
            });
        }

        $contacts = $query
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        // Đếm số liên hệ mới
        $newCount = Contact::where('status', 'new')->count();

        return Inertia::render('Admin/Contacts/Index', compact('contacts', 'newCount'));
    }

    /**
     * Chi tiết liên hệ — tự động đánh dấu "đã đọc".
     */
    public function show(Contact $contact)
    {
        // Nếu là "new" → tự chuyển thành "read"
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }

        return Inertia::render('Admin/Contacts/Show', compact('contact'));
    }

    /**
     * Cập nhật trạng thái.
     */
    public function updateStatus(UpdateContactStatusRequest $request, Contact $contact)
    {
        $contact->update(['status' => $request->validated()['status']]);

        $messages = [
            'new'      => 'Đã đánh dấu là chưa đọc.',
            'read'     => 'Đã đánh dấu là đã đọc.',
            'replied'  => 'Đã đánh dấu là đã trả lời.',
            'archived' => 'Đã lưu trữ liên hệ.',
        ];

        return back()->with('success', $messages[$request->status] ?? 'Đã cập nhật trạng thái.');
    }
}