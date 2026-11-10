import React from 'react';
import Input from '../UI/Input';
import { slugify } from '../../lib/format';

/**
 * Trường slug có nút sinh tự động từ tiêu đề.
 */
export default function SlugField({ value, source, onChange, error, hint, name = 'slug' }) {
    return (
        <div className="ui-slug">
            <Input
                label="Slug (đường dẫn)"
                name={name}
                value={value || ''}
                error={error}
                hint={hint || 'Để trống để hệ thống tự sinh từ tiêu đề.'}
                onChange={(event) => onChange(event.target.value)}
                placeholder="vi-du-ten-tour"
            />
            <button
                type="button"
                className="ui-btn ui-btn--outline ui-btn--sm ui-slug__btn"
                onClick={() => onChange(slugify(source))}
                disabled={!source}
                title="Sinh slug từ tiêu đề"
            >
                Tạo slug
            </button>
        </div>
    );
}
