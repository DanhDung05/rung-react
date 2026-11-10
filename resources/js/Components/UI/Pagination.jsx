import React from 'react';
import { Link } from '@inertiajs/react';

/** Laravel paginator → nhãn tiếng Việt */
function labelOf(raw) {
    return String(raw)
        .replace('&laquo; Previous', '‹ Trước')
        .replace('Next &raquo;', 'Sau ›')
        .replace('&laquo;', '‹')
        .replace('&raquo;', '›');
}

/**
 * links: mảng { url, label, active } từ Laravel.
 */
export default function Pagination({ links = [], from = null, to = null, total = null }) {
    if (!links || links.length <= 3) return null;

    return (
        <nav className="ui-pagination" aria-label="Phân trang">
            {(from !== null || total !== null) && (
                <span className="ui-pagination__info">
                    {from ?? 0}–{to ?? 0} / {total ?? 0}
                </span>
            )}
            <div className="ui-pagination__links">
                {links.map((link, index) =>
                    link.url ? (
                        <Link
                            key={index}
                            href={link.url}
                            className={`ui-page ${link.active ? 'is-active' : ''}`}
                            preserveScroll
                            dangerouslySetInnerHTML={{ __html: labelOf(link.label) }}
                        />
                    ) : (
                        <span
                            key={index}
                            className="ui-page is-disabled"
                            dangerouslySetInnerHTML={{ __html: labelOf(link.label) }}
                        />
                    )
                )}
            </div>
        </nav>
    );
}
