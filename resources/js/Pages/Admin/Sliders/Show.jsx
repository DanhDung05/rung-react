import React from 'react';
import { router } from '@inertiajs/react';
import { Pencil, Trash2 } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { mediaUrl, datetime } from '../../../lib/format';

export default function SlidersShow({ slider }) {
    const { confirm, dialog } = useConfirm();

    const remove = async () => {
        const ok = await confirm({ title: 'Chuyển slider vào thùng rác?', message: 'Slider này sẽ được chuyển vào thùng rác.', confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.sliders.destroy(slider.id));
    };

    return (
        <AdminLayout
            title={slider.title || 'Chi tiết slider'}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Slider', href: routes.admin.sliders.index }, { label: 'Chi tiết' }]}
            actions={<><Button href={routes.admin.sliders.index} variant="outline">← Danh sách</Button><Button href={routes.admin.sliders.edit(slider.id)} icon={<Pencil size={15} />}>Sửa</Button><Button variant="danger" icon={<Trash2 size={15} />} onClick={remove}>Xóa</Button></>}
        >
            <div className="admin-form-layout">
                <div>
                    <Card title="Thông tin slider">
                        <div className="ui-detail-row"><span>Phụ đề</span><div>{slider.subtitle || '—'}</div></div>
                        <div className="ui-detail-row"><span>Vị trí</span><div><code>{slider.position}</code></div></div>
                        <div className="ui-detail-row"><span>Liên kết</span><div>{slider.link_url ? <a className="ui-link" href={slider.link_url} target={slider.link_target}>{slider.link_url}</a> : '—'}</div></div>
                        <div className="ui-detail-row"><span>Hiển thị</span><div><Badge tone={slider.is_active ? 'success' : 'muted'}>{slider.is_active ? 'Có' : 'Không'}</Badge></div></div>
                        <div className="ui-detail-row"><span>Thời gian</span><div>{[datetime(slider.start_at), datetime(slider.end_at)].join(' → ')}</div></div>
                        <div className="ui-detail-row"><span>Thứ tự</span><div>{slider.sort_order}</div></div>
                    </Card>
                </div>
                <aside>
                    <Card title="Ảnh slider">
                        {slider.image ? <img className="ui-detail-image" src={mediaUrl(slider.image)} alt={slider.title || ''} /> : <p className="ui-hint">Chưa có ảnh.</p>}
                    </Card>
                </aside>
            </div>
            {dialog}
        </AdminLayout>
    );
}
