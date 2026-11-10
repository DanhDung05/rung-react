<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', 'unique:pages,slug'],
            'content'          => ['nullable', 'string'],
            'featured_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'template'         => ['nullable', 'string', 'max:50'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'status'           => ['required', Rule::in(['draft', 'published'])],
            'published_at'     => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'    => 'Vui lòng nhập tiêu đề trang.',
            'slug.unique'       => 'Slug này đã tồn tại.',
            'featured_image.max'=> 'Ảnh không vượt quá 20MB.',
            'status.required'   => 'Vui lòng chọn trạng thái.',
            'status.in'         => 'Trạng thái không hợp lệ.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'template' => $this->input('template', 'default'),
        ]);
    }
}