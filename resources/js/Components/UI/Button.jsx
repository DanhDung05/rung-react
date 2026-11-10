import React from 'react';
import { Link } from '@inertiajs/react';

/**
 * Nút dùng chung.
 *
 * variants: primary | dark | gold | danger | outline | ghost
 * sizes: sm | md | lg
 * Nếu truyền `href` sẽ render <Link> (Inertia) thay vì <button>.
 */
export default function Button({
    variant = 'primary',
    size = 'md',
    href = null,
    type = 'button',
    loading = false,
    disabled = false,
    icon = null,
    className = '',
    children,
    ...props
}) {
    const classes = ['ui-btn', `ui-btn--${variant}`, `ui-btn--${size}`, className]
        .filter(Boolean)
        .join(' ');

    const content = (
        <>
            {loading ? <span className="ui-spinner" aria-hidden="true" /> : icon}
            {children}
        </>
    );

    if (href) {
        return (
            <Link href={href} className={classes} {...props}>
                {content}
            </Link>
        );
    }

    return (
        <button type={type} className={classes} disabled={disabled || loading} {...props}>
            {content}
        </button>
    );
}
