import React, { useMemo, useState } from 'react';
import { Link } from '@inertiajs/react';
import { Search, ChevronRight, BookOpen, ArrowLeft, ArrowRight, List } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';

function docUrl(slug) {
    return `/admin/huong-dan?doc=${slug}`;
}

export default function GuideIndex({ docs = [], current, previous = null, next = null }) {
    const [keyword, setKeyword] = useState('');

    const grouped = useMemo(() => {
        const term = keyword.trim().toLowerCase();
        const matches = docs.filter((doc) => !term
            || `${doc.title} ${doc.description} ${doc.group}`.toLowerCase().includes(term));

        return matches.reduce((carry, doc) => {
            carry[doc.group] = carry[doc.group] || [];
            carry[doc.group].push(doc);
            return carry;
        }, {});
    }, [docs, keyword]);

    const headings = useMemo(() => {
        const term = keyword.trim().toLowerCase();
        if (!term) return current?.headings || [];

        return (current?.headings || []).filter((heading) => heading.title.toLowerCase().includes(term));
    }, [current, keyword]);

    return (
        <AdminLayout
            title="Hướng dẫn sử dụng"
            description="Tài liệu quản trị đọc trực tiếp từ các file Markdown trong dự án."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Hướng dẫn sử dụng' }]}
        >
            <div className="help-layout">
                <aside className="help-nav">
                    <div className="help-search">
                        <Search size={16} />
                        <input
                            type="search"
                            value={keyword}
                            onChange={(event) => setKeyword(event.target.value)}
                            placeholder="Tìm tài liệu hoặc mục…"
                        />
                    </div>

                    {Object.entries(grouped).map(([group, items]) => (
                        <div className="help-nav__group" key={group}>
                            <div className="help-nav__label">{group}</div>
                            {items.map((doc) => (
                                <Link
                                    key={doc.slug}
                                    href={docUrl(doc.slug)}
                                    className={`help-nav__link ${doc.slug === current?.slug ? 'is-active' : ''}`}
                                >
                                    <BookOpen size={14} />
                                    <span>{doc.title}</span>
                                </Link>
                            ))}
                        </div>
                    ))}

                    {Object.keys(grouped).length === 0 && <p className="ui-hint">Không tìm thấy tài liệu phù hợp.</p>}

                    {headings.length > 0 && (
                        <div className="help-nav__group">
                            <div className="help-nav__label"><List size={13} /> Trong tài liệu này</div>
                            {headings.map((heading) => (
                                <a
                                    key={heading.id}
                                    href={`#${heading.id}`}
                                    className={`help-nav__toc ${heading.level === 3 ? 'is-sub' : ''}`}
                                >
                                    <ChevronRight size={12} /> {heading.title}
                                </a>
                            ))}
                        </div>
                    )}
                </aside>

                <Card className="help-content">
                    <div className="help-content__head">
                        <span className="help-content__group">{current?.group}</span>
                        <h1>{current?.title}</h1>
                    </div>

                    <article className="help-markdown" dangerouslySetInnerHTML={{ __html: current?.html || '' }} />

                    <nav className="help-pager">
                        {previous ? (
                            <Link href={docUrl(previous.slug)} className="help-pager__link">
                                <ArrowLeft size={15} />
                                <span><small>Trước</small>{previous.title}</span>
                            </Link>
                        ) : <span />}
                        {next && (
                            <Link href={docUrl(next.slug)} className="help-pager__link help-pager__link--next">
                                <span><small>Tiếp</small>{next.title}</span>
                                <ArrowRight size={15} />
                            </Link>
                        )}
                    </nav>
                </Card>
            </div>
        </AdminLayout>
    );
}
