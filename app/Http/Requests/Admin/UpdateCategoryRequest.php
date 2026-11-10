<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')->id;

        return [
            'type'             => ['required', Rule::in(['tour', 'post'])],
            'parent_id'        => [
                'nullable',
                'exists:categories,id',
                Rule::notIn([$categoryId]),
            ],
            'name'             => ['required', 'string', 'max:255'],
            'slug'             => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($categoryId),
            ],
            'description'      => ['nullable', 'string', 'max:1000'],
            'image'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'sort_order'       => ['nullable', 'integer', 'min:0'],
            'is_active'        => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'    => 'Vui lòng chọn loại danh mục.',
            'name.required'    => 'Vui lòng nhập tên danh mục.',
            'slug.unique'      => 'Slug này đã tồn tại.',
            'image.image'      => 'File phải là ảnh.',
            'image.mimes'      => 'Ảnh phải có định dạng JPG, JPEG, PNG hoặc WEBP.',
            'image.max'        => 'Ảnh không vượt quá 20MB.',
            'parent_id.not_in' => 'Không thể chọn chính nó làm danh mục cha.',
            'parent_id.exists' => 'Danh mục cha không tồn tại.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active'  => $this->boolean('is_active'),
            'sort_order' => $this->input('sort_order', 0),
        ]);
    }
}