<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['nullable', 'string', 'max:255'],
            'subtitle'    => ['nullable', 'string', 'max:255'],
            'image'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'link_url'    => ['nullable', 'string', 'max:500'],
            'link_target' => ['required', Rule::in(['_self', '_blank'])],
            'position'    => ['required', 'string', 'max:50'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable', 'boolean'],
            'start_at'    => ['nullable', 'date'],
            'end_at'      => ['nullable', 'date', 'after_or_equal:start_at'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required'           => 'Vui lòng chọn ảnh cho slider.',
            'image.image'              => 'File phải là ảnh.',
            'image.mimes'              => 'Ảnh phải có định dạng JPG, JPEG, PNG hoặc WEBP.',
            'image.max'                => 'Ảnh không vượt quá 20MB.',
            'link_target.required'     => 'Vui lòng chọn cách mở link.',
            'position.required'        => 'Vui lòng nhập vị trí hiển thị.',
            'end_at.after_or_equal'    => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active'  => $this->boolean('is_active'),
            'sort_order' => $this->input('sort_order', 0),
            'position'   => $this->input('position', 'home_hero'),
        ]);
    }
}