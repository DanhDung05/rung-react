import React, { useId } from 'react';
import FormField from './FormField';

export default function Textarea({
    label,
    name,
    error,
    hint,
    required = false,
    rows = 5,
    className = '',
    ...props
}) {
    const generatedId = useId();
    const id = props.id || `field-${name || generatedId}`;

    return (
        <FormField label={label} htmlFor={id} required={required} error={error} hint={hint}>
            <textarea
                id={id}
                name={name}
                rows={rows}
                className={`ui-textarea ${error ? 'is-invalid' : ''} ${className}`.trim()}
                aria-invalid={error ? 'true' : undefined}
                {...props}
            />
        </FormField>
    );
}
