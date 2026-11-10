@php
    $isEdit = isset($user) && $user->exists;
    $action = $isEdit ? route('admin.users.update', $user) : route('admin.users.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method($method)

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Cột trái --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Thông tin tài khoản</h3>

                <x-input name="name" label="Tên" required
                         :value="old('name', $user->name ?? '')"
                         placeholder="Nguyễn Văn A" />

                <x-input name="email" type="email" label="Email" required
                         :value="old('email', $user->email ?? '')"
                         placeholder="user@example.com" />

                <x-select name="role" label="Vai trò" required
                          :options="['admin' => 'Quản trị viên', 'editor' => 'Biên tập viên']"
                          :selected="old('role', $user->role ?? 'editor')"
                          hint="Admin có toàn quyền, Editor chỉ quản lý nội dung." />
            </div>

            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Mật khẩu</h3>

                @if ($isEdit)
                    <p class="text-sm text-text-muted">
                        Để trống cả 2 ô nếu không muốn đổi mật khẩu.
                    </p>
                @endif

                <x-input name="password" type="password"
                         :label="$isEdit ? 'Mật khẩu mới' : 'Mật khẩu'"
                         :required="!$isEdit"
                         hint="Tối thiểu 8 ký tự." />

                <x-input name="password_confirmation" type="password"
                         label="Xác nhận mật khẩu"
                         :required="!$isEdit" />
            </div>

        </div>

        {{-- Cột phải --}}
        <div class="space-y-6">

            {{-- Avatar --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Ảnh đại diện</h3>

                @if ($isEdit && $user->avatar)
                    <div class="mb-4 flex justify-center">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar) }}"
                             alt="{{ $user->name }}"
                             class="w-32 h-32 object-cover rounded-full border border-border">
                    </div>
                @endif

                <input type="file" name="avatar"
                       accept="image/jpeg,image/png,image/webp"
                       class="block w-full text-sm text-text border border-border rounded-md cursor-pointer
                              file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0
                              file:bg-primary file:text-text-inverse file:font-medium
                              hover:file:bg-primary-hover">
                <p class="text-xs text-text-muted mt-2">JPG, PNG, WEBP. Tối đa 20MB.</p>
            </div>

            {{-- Trạng thái --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Trạng thái</h3>

                <x-checkbox name="is_active" value="1" label="Kích hoạt tài khoản"
                            :checked="old('is_active', $user->is_active ?? true)"
                            hint="Tài khoản bị vô hiệu hóa không thể đăng nhập." />
            </div>

        </div>

    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between gap-3 pt-4 border-t border-border">
        <a href="{{ route('admin.users.index') }}"
           class="text-sm text-text-muted hover:text-text transition-base">
            ← Quay lại danh sách
        </a>

        <div class="flex items-center gap-2">
            <x-button href="{{ route('admin.users.index') }}" variant="outline">Hủy</x-button>
            <x-button type="submit" variant="primary">
                {{ $isEdit ? 'Cập nhật' : 'Tạo tài khoản' }}
            </x-button>
        </div>
    </div>

</form>