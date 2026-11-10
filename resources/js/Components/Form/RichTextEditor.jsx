import React, { useEffect, useRef, useState } from 'react';
import FormField from '../UI/FormField';

/**
 * Rich text editor dùng TinyMCE self-hosted tại /vendor/tinymce.
 *
 * Không bundle TinyMCE vào JS để tránh bundle quá lớn và lỗi esbuild trên Windows.
 * Asset được copy tự động bởi `npm run build` / `npm run dev` (scripts/copy-tinymce.mjs).
 */

const SCRIPT_URL = '/vendor/tinymce/tinymce.min.js';
const BASE_URL = '/vendor/tinymce';

let loader = null;

function loadTinyMce() {
    if (typeof window === 'undefined') return Promise.reject(new Error('No window'));
    if (window.tinymce) return Promise.resolve(window.tinymce);
    if (loader) return loader;

    loader = new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = SCRIPT_URL;
        script.async = true;
        script.onload = () => (window.tinymce ? resolve(window.tinymce) : reject(new Error('TinyMCE rỗng')));
        script.onerror = () => reject(new Error('Không tải được TinyMCE'));
        document.head.appendChild(script);
    });

    return loader;
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

export default function RichTextEditor({ label, value = '', onChange, error, hint, height = 340, uploadUrl = null }) {
    const textareaRef = useRef(null);
    const editorRef = useRef(null);
    const valueRef = useRef(value);
    const [ready, setReady] = useState(false);
    const [failed, setFailed] = useState(false);

    valueRef.current = value;

    useEffect(() => {
        let disposed = false;

        loadTinyMce()
            .then((tinymce) => {
                if (disposed || !textareaRef.current) return;

                return tinymce.init({
                    target: textareaRef.current,
                    base_url: BASE_URL,
                    suffix: '.min',
                    license_key: 'gpl',
                    skin: 'oxide',
                    content_css: 'default',
                    height,
                    menubar: false,
                    branding: false,
                    promotion: false,
                    plugins: 'lists link image table code',
                    toolbar:
                        'undo redo | blocks | bold italic underline | bullist numlist | link image table | code',
                    content_style:
                        'body{font-family:Inter,system-ui,sans-serif;font-size:14px;color:#1d2924;padding:10px;line-height:1.6} img{max-width:100%;height:auto}',
                    images_upload_handler: uploadUrl
                        ? (blobInfo) =>
                              new Promise((resolve, reject) => {
                                  const form = new FormData();
                                  form.append('file', blobInfo.blob(), blobInfo.filename());

                                  fetch(uploadUrl, {
                                      method: 'POST',
                                      headers: { 'X-CSRF-TOKEN': csrfToken(), Accept: 'application/json' },
                                      credentials: 'same-origin',
                                      body: form,
                                  })
                                      .then((response) => {
                                          if (!response.ok) throw new Error('Upload thất bại');
                                          return response.json();
                                      })
                                      .then((json) => resolve(json.location))
                                      .catch(() => reject('Không upload được ảnh'));
                              })
                        : undefined,
                    setup: (editor) => {
                        editor.on('init', () => {
                            editorRef.current = editor;
                            editor.setContent(valueRef.current || '');
                            setReady(true);
                        });

                        editor.on('input change undo redo', () => {
                            onChange(editor.getContent());
                        });
                    },
                });
            })
            .catch((loadError) => {
                // Không làm sập form: giữ textarea gốc để vẫn nhập được nội dung.
                console.error('[RichTextEditor]', loadError);
                setFailed(true);
            });

        return () => {
            disposed = true;

            if (editorRef.current) {
                editorRef.current.remove();
                editorRef.current = null;
            }
        };
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    useEffect(() => {
        const editor = editorRef.current;

        if (ready && editor && (value || '') !== editor.getContent()) {
            editor.setContent(value || '');
        }
    }, [value, ready]);

    return (
        <FormField
            label={label}
            error={error}
            hint={failed ? 'Không tải được trình soạn thảo — đang dùng ô nhập HTML.' : hint || (ready ? null : 'Đang tải trình soạn thảo…')}
        >
            <textarea
                ref={textareaRef}
                className="ui-textarea"
                defaultValue={value}
                onChange={(event) => onChange(event.target.value)}
            />
        </FormField>
    );
}
