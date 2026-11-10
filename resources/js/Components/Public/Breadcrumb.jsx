import React from 'react';
import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';

/**
 * Breadcrumb ngoài hero (nền sáng).
 * items: [{ label, href? }]
 */
export default function Breadcrumb({ items = [] }) {
    if (items.length === 0) return null;

    return (
        <nav className="rp-breadcrumb rp-breadcrumb--dark" aria-label="Breadcrumb">
            {items.map((item, index) => (
                <React.Fragment key={`${item.label}-${index}`}>
                    {index > 0 && <ChevronRight size={12} className="rp-breadcrumb__sep" />}
                    {item.href ? <Link href={item.href}>{item.label}</Link> : <span>{item.label}</span>}
                </React.Fragment>
            ))}
        </nav>
    );
}
