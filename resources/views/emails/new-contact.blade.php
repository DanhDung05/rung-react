@component('mail::message')
# Liên hệ mới từ website

Bạn vừa nhận được một liên hệ mới từ form liên hệ trên **Rừng Adventure**.

@component('mail::table')
| Trường | Thông tin |
|:-------|:----------|
| **Họ tên** | {{ $contact->name }} |
| **Email** | [{{ $contact->email }}](mailto:{{ $contact->email }}) |
| **Điện thoại** | {{ $contact->phone ?: '—' }} |
| **Chủ đề** | {{ $contact->subject ?: '—' }} |
| **Thời gian** | {{ $contact->created_at->format('d/m/Y H:i') }} |
| **IP** | {{ $contact->ip_address ?: '—' }} |
@endcomponent

## Nội dung tin nhắn

@component('mail::panel')
{{ $contact->message }}
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'success'])
Xem chi tiết trong Admin
@endcomponent

Trân trọng,<br>
**{{ config('app.name') }}**
@endcomponent