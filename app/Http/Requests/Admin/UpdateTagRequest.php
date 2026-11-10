<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tagId = $this->route('tag')->id;

        return [
            'name'        => ['required', 'string', 'max:100'],
            'slug'        => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('tags', 'slug')->ignore($tagId),
            ],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên tag.',
            'slug.unique'   => 'Slug này đã tồn tại.',
        ];
    }
}