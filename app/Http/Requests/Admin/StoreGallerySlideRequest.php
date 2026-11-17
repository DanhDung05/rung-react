<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreGallerySlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:120'],
            'category'    => ['required', 'string', 'max:120'],
            'accent'      => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'crop_x'      => ['nullable', 'integer', 'between:0,100'],
            'crop_y'      => ['nullable', 'integer', 'between:0,100'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'        => 'Vui lòng nhập tên ảnh.',
            'title.max'             => 'Tên ảnh không vượt quá 120 ký tự.',
            'category.required'     => 'Vui lòng nhập nhóm ảnh.',
            'category.max'          => 'Nhóm ảnh không vượt quá 120 ký tự.',
            'accent.max'            => 'Chữ nhấn không vượt quá 120 ký tự.',
            'description.max'       => 'Mô tả không vượt quá 1000 ký tự.',
            'image.required'        => 'Vui lòng chọn ảnh gốc.',
            'image.image'           => 'File tải lên không phải ảnh hợp lệ.',
            'image.mimes'           => 'Ảnh phải có định dạng JPG, JPEG, PNG hoặc WEBP.',
            'image.max'             => 'Ảnh không được vượt quá 10 MB.',
            'crop_x.between'        => 'Tâm crop ngang phải trong khoảng 0–100%.',
            'crop_y.between'        => 'Tâm crop dọc phải trong khoảng 0–100%.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'crop_x'    => $this->input('crop_x', 50),
            'crop_y'    => $this->input('crop_y', 50),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
