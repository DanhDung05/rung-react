@props(['name', 'value' => null, 'label' => null, 'height' => 400, 'hint' => null])

@php
    $editorId = 'tinymce-' . str_replace(['[', ']'], '-', $name);
@endphp

<div>
    @if ($label)
        <label for="{{ $editorId }}" class="block text-sm font-medium text-text mb-1">
            {{ $label }}
        </label>
    @endif

    <textarea
        id="{{ $editorId }}"
        name="{{ $name }}"
        class="tinymce-editor hidden">{{ old($name, $value) }}</textarea>

    @if ($hint)
        <p class="text-xs text-text-muted mt-1">{{ $hint }}</p>
    @endif
</div>

@once
    @push('scripts')
        <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof tinymce === 'undefined') {
                    console.error('TinyMCE chưa load được. Kiểm tra: public/vendor/tinymce/tinymce.min.js');
                    return;
                }

                tinymce.init({
                    selector: '.tinymce-editor',
                    license_key: 'gpl',
                    height: {{ $height }},
                    menubar: false,
                    branding: false,
                    promotion: false,
                    base_url: '{{ asset('vendor/tinymce') }}',
                    suffix: '.min',

                    // ============================================================
                    // PLUGINS — Bật nhiều tính năng
                    // ============================================================
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                        'preview', 'anchor', 'searchreplace', 'visualblocks',
                        'visualchars', 'code', 'fullscreen', 'insertdatetime',
                        'media', 'table', 'help', 'wordcount', 'quickbars'
                    ].join(' '),

                    // ============================================================
                    // TOOLBAR — Sắp xếp theo độ ưu tiên
                    // Nút càng về trái → càng ưu tiên hiện sẵn
                    // Phần còn lại tự động ẩn vào nút "..."
                    // ============================================================
                    toolbar: [
                        // Ưu tiên cao — luôn hiện
                        'undo redo',
                        'blocks',
                        'bold italic underline',
                        'bullist numlist',
                        'link image',
                        'fullscreen code',

                        // Ưu tiên trung bình — hiện nếu đủ chỗ
                        'alignleft aligncenter alignright alignjustify',
                        'forecolor backcolor',
                        'removeformat',

                        // Ưu tiên thấp — ẩn vào "..." khi thiếu chỗ
                        'strikethrough',
                        'outdent indent',
                        'media table',
                        'insertdatetime charmap',
                        'searchreplace visualblocks visualchars',
                        'preview anchor help'
                    ].join(' | '),

                    // Giữ "sliding" → có nút "..." để xem thêm
                    toolbar_mode: 'sliding',
                    toolbar_sticky: true,

                    // ============================================================
                    // QUICKBARS — Thanh công cụ nổi khi bôi đen
                    // ============================================================
                    quickbars_selection_toolbar: 'bold italic underline | quicklink h2 h3 blockquote',
                    quickbars_insert_toolbar: 'quickimage quicktable',

                    // ============================================================
                    // STYLE cho nội dung editor
                    // ============================================================
                    content_style: `
                        body {
                            font-family: 'Inter', system-ui, -apple-system, sans-serif;
                            font-size: 15px;
                            line-height: 1.7;
                            color: #0f172a;
                            padding: 12px 20px;
                        }
                        h1, h2, h3, h4, h5, h6 {
                            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
                            color: #0f172a;
                            margin-top: 1.5em;
                            margin-bottom: 0.5em;
                        }
                        a { color: #307a55; }
                        img { max-width: 100%; height: auto; border-radius: 8px; }
                        blockquote {
                            border-left: 4px solid #307a55;
                            padding-left: 16px;
                            color: #64748b;
                            font-style: italic;
                        }
                    `,

                    // ============================================================
                    // UPLOAD ẢNH
                    // ============================================================
                    images_upload_url: '{{ route('admin.upload.image') }}',
                    automatic_uploads: true,
                    images_upload_handler: function (blobInfo, progress) {
                        return new Promise((resolve, reject) => {
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', '{{ route('admin.upload.image') }}');
                            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                            xhr.setRequestHeader('Accept', 'application/json');

                            xhr.upload.onprogress = function (e) {
                                progress(e.loaded / e.total * 100);
                            };

                            xhr.onload = function () {
                                if (xhr.status === 200) {
                                    const json = JSON.parse(xhr.responseText);
                                    resolve(json.url);
                                } else {
                                    reject({ message: 'Upload failed: ' + xhr.status, remove: true });
                                }
                            };

                            xhr.onerror = function () {
                                reject({ message: 'Upload error', remove: true });
                            };

                            const formData = new FormData();
                            formData.append('file', blobInfo.blob(), blobInfo.filename());
                            xhr.send(formData);
                        });
                    },
                });
            });
        </script>
    @endpush
@endonce