<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Danh sách user.
     */
    public function index(Request $request)
    {
        // Chỉ admin được quản lý người dùng (chặn editor leo thang đặc quyền).
        $this->authorize('viewAny', User::class);

        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%');
            });
        }

        $users = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', compact('users'));
    }

    /**
     * Form tạo mới.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/Users/Create');
    }

    /**
     * Lưu user mới.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validated();

        // Hash password
        $data['password'] = Hash::make($data['password']);

        // Upload avatar
        if ($request->hasFile('avatar')) {
            $data['avatar'] = app(ImageService::class)
                ->upload($request->file('avatar'), 'avatars', 300);
        }

        User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Đã thêm tài khoản thành công.');
    }

    /**
     * Chi tiết user.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->loadCount('posts');

        return Inertia::render('Admin/Users/Show', compact('user'));
    }

    /**
     * Form chỉnh sửa.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return Inertia::render('Admin/Users/Edit', compact('user'));
    }

    /**
     * Cập nhật user.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        // Chỉ admin được sửa người dùng.
        $this->authorize('update', $user);

        $isSelf = (int) auth()->id() === (int) $user->id;

        // Không ai được tự đổi vai trò của chính mình (chặn tự nâng quyền / tự hạ quyền).
        if ($isSelf && array_key_exists('role', $data) && $data['role'] !== $user->role) {
            abort(403, 'Bạn không thể tự thay đổi vai trò của chính mình.');
        }

        // Không được tự vô hiệu hóa chính mình (chặn tự khóa tài khoản).
        if ($isSelf && array_key_exists('is_active', $data) && !$data['is_active']) {
            abort(403, 'Bạn không thể tự vô hiệu hóa tài khoản của chính mình.');
        }

        // Password: nếu có nhập thì hash, không thì bỏ
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Upload avatar mới
        if ($request->hasFile('avatar')) {
            $data['avatar'] = app(ImageService::class)
                ->replace($request->file('avatar'), $user->avatar, 'avatars', 300);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Đã cập nhật tài khoản thành công.');
    }

    /**
     * VÔ HIỆU HÓA / KÍCH HOẠT user.
     */
    public function toggleActive(User $user)
    {
        // Không cho tự vô hiệu hóa chính mình
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Không thể vô hiệu hóa chính mình.');
        }

        // Chỉ admin được bật/tắt tài khoản người khác.
        $this->authorize('toggleActive', $user);

        $user->update(['is_active' => !$user->is_active]);

        $message = $user->is_active
            ? 'Đã kích hoạt tài khoản "' . $user->name . '".'
            : 'Đã vô hiệu hóa tài khoản "' . $user->name . '".';

        return back()->with('success', $message);
    }
}