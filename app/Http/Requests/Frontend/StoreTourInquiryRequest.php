<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StoreTourInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255'],
            'phone'          => ['required', 'string', 'regex:/^(0|\+84)(\d{9,10})$/'],
            'people_count'   => ['required', 'integer', 'min:1', 'max:50'],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
            'message'        => ['nullable', 'string', 'max:2000'],
            'website'        => ['nullable', 'string', 'max:255'], // honeypot
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'Vui lòng nhập họ tên.',
            'email.required'           => 'Vui lòng nhập email.',
            'email.email'              => 'Email không hợp lệ.',
            'phone.required'           => 'Vui lòng nhập số điện thoại.',
            'phone.regex'              => 'Số điện thoại không hợp lệ (ví dụ: 0912345678).',
            'people_count.required'    => 'Vui lòng nhập số người.',
            'people_count.min'         => 'Số người tối thiểu là 1.',
            'people_count.max'         => 'Số người tối đa là 50.',
            'preferred_date.after_or_equal' => 'Ngày dự kiến không được ở quá khứ.',
        ];
    }
}
