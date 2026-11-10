import React from 'react';

export default function Checkbox({
    label,
    name,
    checked = false,
    onChange,
    error,
    hint,
    disabled = false,
    ...props
}) {
    return (
        <div className="ui-field ui-field--inline">
            <label className="ui-checkbox">
                <input
                    type="checkbox"
                    name={name}
                    checked={!!checked}
                    onChange={onChange}
                    disabled={disabled}
                    {...props}
                />
                <span>{label}</span>
            </label>
            {hint && !error && <span className="ui-hint">{hint}</span>}
            {error && <span className="ui-error">{error}</span>}
        </div>
    );
}
