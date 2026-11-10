<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'          => ['required', 'exists:users,id'],
            'category_id'      => [
                'required',
                Rule::exists('categories', 'id')->where('type', 'post'),
            ],
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', 'unique:posts,slug'],
            'excerpt'          => ['nullable', 'string', 'max:1000'],
            'content'          => ['nullable', 'string'],
            'featured_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'is_featured'      => ['nullable', 'boolean'],
            'status'           => ['required', Rule::in(['draft', 'published'])],
            'published_at'     => ['nullable', 'date'],
            'tag_ids'          => ['nullable', 'array'],
            'tag_ids.*'        => ['exists:tags,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'      => 'Vui lòng chọn tác giả.',
            'user_id.exists'        => 'Tác giả không tồn tại.',
            'category_id.required'  => 'Vui lòng chọn danh mục.',
            'category_id.exists'    => 'Danh mục không hợp lệ (phải là danh mục loại post).',
            'title.required'        => 'Vui lòng nhập tiêu đề.',
            'slug.unique'           => 'Slug này đã tồn tại.',
            'featured_image.image'  => 'File phải là ảnh.',
            'featured_image.mimes'  => 'Ảnh phải có định dạng JPG, JPEG, PNG hoặc WEBP.',
            'featured_image.max'    => 'Ảnh không vượt quá 20MB.',
            'status.required'       => 'Vui lòng chọn trạng thái.',
            'status.in'             => 'Trạng thái không hợp lệ.',
            'tag_ids.*.exists'      => 'Tag không tồn tại.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
}