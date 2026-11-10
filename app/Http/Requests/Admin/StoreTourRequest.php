<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Thông tin cơ bản
            'category_id'      => [
                'required',
                Rule::exists('categories', 'id')->where('type', 'tour'),
            ],
            'name'             => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', 'unique:tours,slug'],
            'excerpt'          => ['nullable', 'string', 'max:1000'],
            'content'          => ['nullable', 'string'],
            'featured_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],

            // Ảnh phụ (gallery)
            'gallery'          => ['nullable', 'array', 'max:20'],
            'gallery.*'        => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],

            // Thông số
            'duration_days'    => ['nullable', 'integer', 'min:0', 'max:365'],
            'duration_nights'  => ['nullable', 'integer', 'min:0', 'max:365'],
            'distance_km'      => ['nullable', 'numeric', 'min:0'],
            'altitude_min'     => ['nullable', 'integer', 'min:0'],
            'altitude_max'     => ['nullable', 'integer', 'min:0'],
            'difficulty'       => ['nullable', Rule::in(['easy', 'moderate', 'hard', 'expert'])],
            'price_reference'  => ['nullable', 'integer', 'min:0'],
            'start_point'      => ['nullable', 'string', 'max:255'],
            'end_point'        => ['nullable', 'string', 'max:255'],
            'best_time'        => ['nullable', 'string', 'max:255'],
            'notes'            => ['nullable', 'string'],

            // SEO
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],

            // Trạng thái
            'is_featured'      => ['nullable', 'boolean'],
            'status'           => ['required', Rule::in(['draft', 'published'])],
            'published_at'     => ['nullable', 'date'],
            'sort_order'       => ['nullable', 'integer', 'min:0'],

            // Quan hệ
            'destination_ids'   => ['nullable', 'array'],
            'destination_ids.*' => ['exists:destinations,id'],

            // Itineraries
            'itineraries'                => ['nullable', 'array'],
            'itineraries.*.day_number'   => ['nullable', 'integer', 'min:1'],
            'itineraries.*.title'        => ['nullable', 'string', 'max:255'],
            'itineraries.*.description'  => ['nullable', 'string'],
            'itineraries.*.distance_km'  => ['nullable', 'numeric', 'min:0'],
            'itineraries.*.altitude'     => ['nullable', 'integer', 'min:0'],
            'itineraries.*.meals'        => ['nullable', 'string', 'max:100'],
            'itineraries.*.accommodation'=> ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'   => 'Vui lòng chọn danh mục.',
            'category_id.exists'     => 'Danh mục không hợp lệ (phải là danh mục loại tour).',
            'name.required'          => 'Vui lòng nhập tên tour.',
            'slug.unique'            => 'Slug này đã tồn tại.',
            'featured_image.image'   => 'File phải là ảnh.',
            'featured_image.mimes'   => 'Ảnh phải có định dạng JPG, JPEG, PNG hoặc WEBP.',
            'featured_image.max'     => 'Ảnh không vượt quá 20MB.',
            'gallery.array'          => 'Danh sách ảnh không hợp lệ.',
            'gallery.max'            => 'Chỉ được upload tối đa 20 ảnh mỗi lần.',
            'gallery.*.image'        => 'File phải là ảnh.',
            'gallery.*.mimes'        => 'Ảnh phải có định dạng JPG, JPEG, PNG hoặc WEBP.',
            'gallery.*.max'          => 'Mỗi ảnh không vượt quá 10MB.',
            'difficulty.in'          => 'Độ khó không hợp lệ.',
            'status.required'        => 'Vui lòng chọn trạng thái.',
            'status.in'              => 'Trạng thái không hợp lệ.',
            'destination_ids.*.exists' => 'Điểm đến không tồn tại.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'sort_order'  => $this->input('sort_order', 0),
            'itineraries' => array_filter($this->input('itineraries', []), function ($item) {
                return !empty($item['title']);
            }),
        ]);
    }
}