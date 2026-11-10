<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category'   => ['nullable', 'string', 'max:100'],
            'question'   => ['required', 'string', 'max:500'],
            'answer'     => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => 'Vui lòng nhập câu hỏi.',
            'question.max'      => 'Câu hỏi không vượt quá 500 ký tự.',
            'answer.required'   => 'Vui lòng nhập câu trả lời.',
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