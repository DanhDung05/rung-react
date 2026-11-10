import React from 'react';

/**
 * Bọc label + control + lỗi + gợi ý cho mọi field.
 */
export default function FormField({ label, htmlFor, required = false, error, hint, children }) {
    return (
        <div className="ui-field">
            {label && (
                <label className="ui-label" htmlFor={htmlFor}>
                    {label}
                    {required && <span className="ui-required"> *</span>}
                </label>
            )}
            {children}
            {hint && !error && <span className="ui-hint">{hint}</span>}
            {error && <span className="ui-error">{error}</span>}
        </div>
    );
}
