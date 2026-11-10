<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Chỉ admin được sửa tài khoản (chặn editor tự nâng quyền thành admin).
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role'     => ['required', Rule::in(['admin', 'editor'])],
            'is_active'=> ['nullable', 'boolean'],
            'avatar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Vui lòng nhập tên.',
            'email.required'     => 'Vui lòng nhập email.',
            'email.unique'       => 'Email này đã được sử dụng.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.min'       => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'role.required'      => 'Vui lòng chọn vai trò.',
            'avatar.max'         => 'Ảnh không vượt quá 20MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}