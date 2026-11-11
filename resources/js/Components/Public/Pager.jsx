import React from 'react';
import { Link } from '@inertiajs/react';

/** Phân trang Laravel paginator (links: [{url,label,active}]). */
export default function Pager({ links = [], className = '' }) {
    const items = links.filter((link) => link.label !== '&laquo; Previous' && link.label !== 'Next &raquo;');

    if (items.length <= 3) return null;

    return (
        <nav className={`rp-pager ${className}`.trim()} aria-label="Phân trang">
            {items.map((link, index) => {
                const label = link.label
                    .replace('&laquo;', '‹')
                    .replace('&raquo;', '›');

                if (!link.url) {
                    return (
                        <span key={index} className="rp-pager__item is-disabled" dangerouslySetInnerHTML={{ __html: label }} />
                    );
                }

                return (
                    <Link
                        key={index}
                        href={link.url}
                        className={`rp-pager__item ${link.active ? 'is-active' : ''}`.trim()}
                        preserveScroll
                        dangerouslySetInnerHTML={{ __html: label }}
                    />
                );
            })}
        </nav>
    );
}
