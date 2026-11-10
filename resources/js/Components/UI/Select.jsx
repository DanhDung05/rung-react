import React, { useId } from 'react';
import FormField from './FormField';

/**
 * options: [{ value, label }] hoặc [{ id, name }]
 */
export default function Select({
    label,
    name,
    error,
    hint,
    required = false,
    options = [],
    placeholder = '— Chọn —',
    className = '',
    children,
    ...props
}) {
    const generatedId = useId();
    const id = props.id || `field-${name || generatedId}`;

    const normalized = options.map((option) =>
        typeof option === 'object'
            ? { value: option.value ?? option.id, label: option.label ?? option.name }
            : { value: option, label: option }
    );

    return (
        <FormField label={label} htmlFor={id} required={required} error={error} hint={hint}>
            <select
                id={id}
                name={name}
                className={`ui-select ${error ? 'is-invalid' : ''} ${className}`.trim()}
                aria-invalid={error ? 'true' : undefined}
                {...props}
            >
                {placeholder !== null && <option value="">{placeholder}</option>}
                {normalized.map((option) => (
                    <option key={String(option.value)} value={option.value}>
                        {option.label}
                    </option>
                ))}
                {children}
            </select>
        </FormField>
    );
}
