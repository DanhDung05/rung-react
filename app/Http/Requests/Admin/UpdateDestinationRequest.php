<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $destinationId = $this->route('destination')->id;

        return [
            'parent_id'        => [
                'nullable',
                'exists:destinations,id',
                Rule::notIn([$destinationId]),
            ],
            'name'             => ['required', 'string', 'max:255'],
            'slug'             => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('destinations', 'slug')->ignore($destinationId),
            ],
            'description'      => ['nullable', 'string', 'max:1000'],
            'content'          => ['nullable', 'string'],
            'image'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'region'           => ['nullable', 'string', 'max:100'],
            'province'         => ['nullable', 'string', 'max:100'],
            'latitude'         => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'        => ['nullable', 'numeric', 'between:-180,180'],
            'altitude'         => ['nullable', 'integer', 'min:0'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'is_featured'      => ['nullable', 'boolean'],
            'sort_order'       => ['nullable', 'integer', 'min:0'],
            'status'           => ['required', Rule::in(['draft', 'published'])],
            'published_at'     => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Vui lòng nhập tên điểm đến.',
            'slug.unique'       => 'Slug này đã tồn tại.',
            'image.max'         => 'Ảnh không vượt quá 20MB.',
            'parent_id.not_in'  => 'Không thể chọn chính nó làm điểm đến cha.',
            'parent_id.exists'  => 'Điểm đến cha không tồn tại.',
            'latitude.between'  => 'Vĩ độ phải từ -90 đến 90.',
            'longitude.between' => 'Kinh độ phải từ -180 đến 180.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'sort_order'  => $this->input('sort_order', 0),
        ]);
    }
}