<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $guest = ! $this->user();

        return [
            'rating'       => ['required', 'integer', 'min:1', 'max:5'],
            'comment'      => ['required', 'string', 'min:10', 'max:2000'],
            'author_name'  => [$guest ? 'required' : 'nullable', 'string', 'max:100'],
            'author_email' => [$guest ? 'required' : 'nullable', 'email', 'max:255'],
            'website'      => ['nullable', 'string', 'max:255'], // honeypot
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required'       => 'Vui lòng chọn số sao.',
            'rating.min'            => 'Số sao tối thiểu là 1.',
            'rating.max'            => 'Số sao tối đa là 5.',
            'comment.required'      => 'Vui lòng nhập nội dung đánh giá.',
            'comment.min'           => 'Nội dung đánh giá phải có ít nhất 10 ký tự.',
            'comment.max'           => 'Nội dung đánh giá không vượt quá 2000 ký tự.',
            'author_name.required'  => 'Vui lòng nhập họ tên.',
            'author_email.required' => 'Vui lòng nhập email.',
            'author_email.email'    => 'Email không hợp lệ.',
        ];
    }
}
