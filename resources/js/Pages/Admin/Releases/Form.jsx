import React from 'react';
import { Link, useForm } from '@inertiajs/react';
import { ArrowLeft, Save } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import { routes } from '../../../lib/routes';

export default function ReleaseForm({ release = null, categories = {}, statuses = {}, currentVersion = '' }) {
    const editing = Boolean(release?.id);
    const { data, setData, post, put, processing, errors } = useForm({
        version: release?.version || '',
        previous_version: release?.previous_version || (editing ? '' : currentVersion),
        title: release?.title || '',
        category: release?.category || 'improvement',
        status: release?.status || 'development',
        content: release?.content || '',
        released_at: release?.released_at ? String(release.released_at).slice(0, 10) : '',
    });

    const submit = (event) => {
        event.preventDefault();
        if (editing) put(routes.admin.releases.update(release.id));
        else post(routes.admin.releases.store);
    };

    return (
        <AdminLayout
            title={editing ? `Sửa bản ${release.version}` : 'Thêm bản cập nhật'}
            description="Chỉ đánh dấu “Đã phát hành” khi phiên bản thực sự hoàn tất."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Lịch sử cập nhật', href: routes.admin.releases.index }, { label: editing ? release.version : 'Thêm mới' }]}
            actions={<Button href={routes.admin.releases.index} variant="outline" icon={<ArrowLeft size={15} />}>Quay lại</Button>}
        >
            <Card>
                <form className="ui-form" onSubmit={submit}>
                    <div className="ui-grid-2">
                        <label className="ui-field">
                            <span className="ui-label">Version *</span>
                            <input className="ui-input" value={data.version} onChange={(event) => setData('version', event.target.value)} placeholder="2.2.0" />
                            {errors.version && <span className="ui-error">{errors.version}</span>}
                        </label>

                        <label className="ui-field">
                            <span className="ui-label">Version trước đó</span>
                            <input className="ui-input" value={data.previous_version} onChange={(event) => setData('previous_version', event.target.value)} placeholder="2.1.0" />
                            {errors.previous_version && <span className="ui-error">{errors.previous_version}</span>}
                        </label>
                    </div>

                    <label className="ui-field">
                        <span className="ui-label">Tiêu đề bản cập nhật *</span>
                        <input className="ui-input" value={data.title} onChange={(event) => setData('title', event.target.value)} />
                        {errors.title && <span className="ui-error">{errors.title}</span>}
                    </label>

                    <div className="ui-grid-3">
                        <label className="ui-field">
                            <span className="ui-label">Loại thay đổi *</span>
                            <select className="ui-select" value={data.category} onChange={(event) => setData('category', event.target.value)}>
                                {Object.entries(categories).map(([value, label]) => <option key={value} value={value}>{label}</option>)}
                            </select>
                            {errors.category && <span className="ui-error">{errors.category}</span>}
                        </label>

                        <label className="ui-field">
                            <span className="ui-label">Trạng thái *</span>
                            <select className="ui-select" value={data.status} onChange={(event) => setData('status', event.target.value)}>
                                {Object.entries(statuses).map(([value, label]) => <option key={value} value={value}>{label}</option>)}
                            </select>
                            {errors.status && <span className="ui-error">{errors.status}</span>}
                        </label>

                        <label className="ui-field">
                            <span className="ui-label">Ngày phát hành</span>
                            <input type="date" className="ui-input" value={data.released_at} onChange={(event) => setData('released_at', event.target.value)} />
                            {errors.released_at && <span className="ui-error">{errors.released_at}</span>}
                        </label>
                    </div>

                    <label className="ui-field">
                        <span className="ui-label">Nội dung chi tiết * (mỗi dòng một ý, dùng “-” cho danh sách)</span>
                        <textarea className="ui-input" rows={10} value={data.content} onChange={(event) => setData('content', event.target.value)} />
                        {errors.content && <span className="ui-error">{errors.content}</span>}
                    </label>

                    <div className="ui-form-actions">
                        <Button type="submit" disabled={processing} icon={<Save size={15} />}>{editing ? 'Lưu thay đổi' : 'Tạo bản cập nhật'}</Button>
                        <Link href={routes.admin.releases.index} className="ui-btn ui-btn--ghost">Hủy</Link>
                    </div>
                </form>
            </Card>
        </AdminLayout>
    );
}
