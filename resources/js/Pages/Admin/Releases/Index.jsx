import React, { useState } from 'react';
import { Link, router } from '@inertiajs/react';
import { Eye, Search, Rocket } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Badge from '../../../Components/UI/Badge';
import { routes } from '../../../lib/routes';

export default function ReleasesIndex({ entries = [], groups = {}, currentVersion = '', filters = {} }) {
    const [form, setForm] = useState({ q: filters.q || '', group: filters.group || '' });

    const applyFilters = (event) => {
        event.preventDefault();
        router.get(routes.admin.releases.index, form, { preserveState: true, replace: true });
    };

    return (
        <AdminLayout
            title="Lịch sử cập nhật"
            description="Đọc trực tiếp từ CHANGELOG.md — chỉ xem, không sửa trong Admin."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Lịch sử cập nhật' }]}
        >
            <div className="release-hero">
                <div>
                    <span className="release-hero__eyebrow">Phiên bản hiện tại</span>
                    <strong className="release-hero__version">{currentVersion || '—'}</strong>
                </div>
                <span className="release-hero__hint">Nguồn: <code>CHANGELOG.md</code> · đồng bộ với <code>VERSION</code></span>
            </div>

            <Card>
                <form className="release-filters" onSubmit={applyFilters}>
                    <div className="release-filters__search">
                        <Search size={15} />
                        <input
                            className="ui-input"
                            placeholder="Tìm version hoặc từ khóa…"
                            value={form.q}
                            onChange={(event) => setForm({ ...form, q: event.target.value })}
                        />
                    </div>
                    <select className="ui-select" value={form.group} onChange={(event) => setForm({ ...form, group: event.target.value })}>
                        <option value="">Mọi nhóm thay đổi</option>
                        {Object.entries(groups).map(([value, label]) => <option key={value} value={value}>{label}</option>)}
                    </select>
                    <button type="submit" className="ui-btn ui-btn--dark">Lọc</button>
                </form>

                {entries.length === 0 ? (
                    <div className="release-empty">
                        <Rocket size={26} />
                        <p>Chưa có mục nào trong <code>CHANGELOG.md</code>.</p>
                    </div>
                ) : (
                    <ol className="release-timeline">
                        {entries.map((entry) => (
                            <li className="release-item" key={entry.version}>
                                <span className="release-item__dot" />
                                <div className="release-item__head">
                                    <div className="release-item__version">
                                        <strong>{entry.label}</strong>
                                        {entry.version === currentVersion && <Badge tone="success">Hiện tại</Badge>}
                                        <Badge tone="muted">{entry.date}</Badge>
                                    </div>
                                    <time>{entry.groups.reduce((sum, group) => sum + group.items.length, 0)} thay đổi</time>
                                </div>

                                <h3>{entry.title}</h3>

                                {entry.groups.map((group) => (
                                    <div className="release-group" key={group.key}>
                                        <span className="release-group__label">{group.label}</span>
                                        <ul>
                                            {group.items.slice(0, 4).map((item, index) => <li key={index}>{item}</li>)}
                                        </ul>
                                        {group.items.length > 4 && <small>+{group.items.length - 4} mục khác</small>}
                                    </div>
                                ))}

                                <div className="release-item__actions">
                                    <Link href={routes.admin.releases.show(entry.version)} className="ui-link"><Eye size={15} /> Xem chi tiết</Link>
                                </div>
                            </li>
                        ))}
                    </ol>
                )}
            </Card>
        </AdminLayout>
    );
}
