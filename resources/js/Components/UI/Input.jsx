import React, { useId } from 'react';
import FormField from './FormField';

/**
 * Input text/email/password/number/date/url.
 */
export default function Input({
    label,
    name,
    type = 'text',
    error,
    hint,
    required = false,
    className = '',
    ...props
}) {
    const generatedId = useId();
    const id = props.id || `field-${name || generatedId}`;

    return (
        <FormField label={label} htmlFor={id} required={required} error={error} hint={hint}>
            <input
                id={id}
                name={name}
                type={type}
                className={`ui-input ${error ? 'is-invalid' : ''} ${className}`.trim()}
                aria-invalid={error ? 'true' : undefined}
                {...props}
            />
        </FormField>
    );
}
