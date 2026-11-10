import React from 'react';
import Input from '../UI/Input';
import Textarea from '../UI/Textarea';

/**
 * Bộ trường SEO: meta title + meta description + đếm ký tự.
 */
export default function SeoFields({ data, errors = {}, onChange, titleKey = 'meta_title', descriptionKey = 'meta_description' }) {
    return (
        <div className="ui-form-grid">
            <Input
                label="Meta title"
                name={titleKey}
                value={data[titleKey] || ''}
                maxLength={255}
                error={errors[titleKey]}
                hint={`${(data[titleKey] || '').length}/255 ký tự`}
                onChange={(event) => onChange(titleKey, event.target.value)}
            />
            <Textarea
                label="Meta description"
                name={descriptionKey}
                rows={3}
                value={data[descriptionKey] || ''}
                maxLength={500}
                error={errors[descriptionKey]}
                hint={`${(data[descriptionKey] || '').length}/500 ký tự`}
                onChange={(event) => onChange(descriptionKey, event.target.value)}
            />
        </div>
    );
}
