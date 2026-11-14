import React, { useState } from 'react';
import { Link, router } from '@inertiajs/react';
import { Plus, Pencil, Trash2, Eye, Rocket, ChevronRight } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import Pagination from '../../../Components/UI/Pagination';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';

const CATEGORY_TONES = { feature: 'success', improvement: 'info', fix: 'warning', security: 'danger' };

export default function ReleasesIndex({ releases, categories = {}, statuses = {}, currentVersion = '', filters = {} }) {
    const { confirm, dialog } = useConfirm();
    const rows = releases?.data || [];
    const [form, setForm] = useState({
        version: filters.version || '',
        category: filters.category || '',
        status: filters.status || '',
        date_from: filters.date_from || '',
        date_to: filters.date_to || '',
    });

    const applyFilters = (event) => {
        event.preventDefault();
        router.get(routes.admin.releases.index, form, { preserveState: true, replace: true });
    };

    const remove = async (release) => {
        const ok = await confirm({
            title: 'Xóa bản cập nhật?',
            message: `Bản ${release.version} — “${release.title}” sẽ bị xóa vĩnh viễn.`,
            confirmLabel: 'Xóa',
        });
        if (ok) router.delete(routes.admin.releases.destroy(release.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Lịch sử cập nhật"
            description="Các phiên bản đã phát hành và đang phát triển của Rừng Adventure."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Lịch sử cập nhật' }]}
            actions={<Button href={routes.admin.releases.create} icon={<Plus size={16} />}>Thêm bản cập nhật</Button>}
        >
            <div className="release-hero">
                <div>
                    <span className="release-hero__eyebrow">Phiên bản hiện tại</span>
                    <strong className="release-hero__version">{currentVersion || '—'}</strong>
                </div>
                <span className="release-hero__hint">Đồng bộ với tệp <code>VERSION</code> và <code>CHANGELOG.md</code></span>
            </div>

            <Card>
                <form className="release-filters" onSubmit={applyFilters}>
                    <input className="ui-input" placeholder="Version (vd: 2.2.0)" value={form.version} onChange={(event) => setForm({ ...form, version: event.target.value })} />
                    <select className="ui-select" value={form.category} onChange={(event) => setForm({ ...form, category: event.target.value })}>
                        <option value="">Mọi loại</option>
                        {Object.entries(categories).map(([value, label]) => <option key={value} value={value}>{label}</option>)}
                    </select>
                    <select className="ui-select" value={form.status} onChange={(event) => setForm({ ...form, status: event.target.value })}>
                        <option value="">Mọi trạng thái</option>
                        {Object.entries(statuses).map(([value, label]) => <option key={value} value={value}>{label}</option>)}
                    </select>
                    <input type="date" className="ui-input" value={form.date_from} onChange={(event) => setForm({ ...form, date_from: event.target.value })} />
                    <input type="date" className="ui-input" value={form.date_to} onChange={(event) => setForm({ ...form, date_to: event.target.value })} />
                    <button type="submit" className="ui-btn ui-btn--dark">Lọc</button>
                </form>

                {rows.length === 0 ? (
                    <div className="release-empty">
                        <Rocket size={26} />
                        <p>Chưa có bản cập nhật nào. Bấm “Thêm bản cập nhật” để tạo bản ghi đầu tiên.</p>
                    </div>
                ) : (
                    <ol className="release-timeline">
                        {rows.map((release) => (
                            <li className={`release-item ${release.status === 'development' ? 'is-dev' : ''}`} key={release.id}>
                                <span className="release-item__dot" />
                                <div className="release-item__head">
                                    <div className="release-item__version">
                                        <strong>v{release.version.replace(/^v/, '')}</strong>
                                        <Badge tone={release.status === 'released' ? 'success' : 'warning'}>{statuses[release.status] || release.status}</Badge>
                                        <Badge tone={CATEGORY_TONES[release.category] || 'muted'}>{categories[release.category] || release.category}</Badge>
                                    </div>
                                    <time>{release.released_at ? new Date(release.released_at).toLocaleDateString('vi-VN') : 'Chưa phát hành'}</time>
                                </div>

                                <h3>{release.title}</h3>
                                {release.previous_version && (
                                    <div className="release-item__previous">
                                        {release.previous_version} <ChevronRight size={13} /> {release.version}
                                    </div>
                                )}
                                <p className="release-item__excerpt">{release.content?.slice(0, 220)}{release.content?.length > 220 ? '…' : ''}</p>

                                <div className="release-item__actions">
                                    <Link href={routes.admin.releases.show(release.id)} className="ui-link"><Eye size={15} /> Xem chi tiết</Link>
                                    <Link href={routes.admin.releases.edit(release.id)} className="ui-link"><Pencil size={15} /> Sửa</Link>
                                    <button type="button" className="ui-link ui-link--danger" onClick={() => remove(release)}><Trash2 size={15} /> Xóa</button>
                                </div>
                            </li>
                        ))}
                    </ol>
                )}

                {releases?.links?.length > 3 && <Pagination links={releases.links} from={releases.from} to={releases.to} total={releases.total} />}
            </Card>

            {dialog}
        </AdminLayout>
    );
}
