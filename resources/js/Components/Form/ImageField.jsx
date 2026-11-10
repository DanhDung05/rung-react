import React, { useEffect, useId, useState } from 'react';
import FormField from '../UI/FormField';

/**
 * Upload 1 ảnh kèm preview.
 * `file` là File object (đã set vào form data), `currentUrl` là ảnh hiện tại (edit).
 */
export default function ImageField({
    label,
    name,
    file = null,
    currentUrl = null,
    onChange,
    error,
    hint,
    accept = 'image/jpeg,image/png,image/webp,image/gif',
}) {
    const id = useId();
    const [preview, setPreview] = useState(currentUrl || null);

    useEffect(() => {
        if (!file) {
            setPreview(currentUrl || null);
            return undefined;
        }

        const url = URL.createObjectURL(file);
        setPreview(url);

        return () => URL.revokeObjectURL(url);
    }, [file, currentUrl]);

    return (
        <FormField label={label} htmlFor={id} error={error} hint={hint}>
            <div className="ui-image-field">
                <div className="ui-image-field__preview">
                    {preview ? <img src={preview} alt={label || 'Ảnh'} /> : <span>Chưa có ảnh</span>}
                </div>
                <div className="ui-image-field__meta">
                    <input
                        id={id}
                        name={name}
                        type="file"
                        accept={accept}
                        onChange={(event) => onChange(event.target.files?.[0] || null)}
                    />
                    <small>JPG, PNG, WEBP, GIF — tối đa 20MB.</small>
                    {file && (
                        <button type="button" className="ui-btn ui-btn--ghost ui-btn--sm" onClick={() => onChange(null)}>
                            Bỏ chọn
                        </button>
                    )}
                </div>
            </div>
        </FormField>
    );
}
