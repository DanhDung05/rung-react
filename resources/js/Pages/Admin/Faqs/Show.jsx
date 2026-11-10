import React from 'react';
import { router } from '@inertiajs/react';
import { Pencil, Trash2 } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { datetime } from '../../../lib/format';

export default function FaqsShow({ faq }) {
    const { confirm, dialog } = useConfirm();

    const remove = async () => {
        const ok = await confirm({ title: 'Chuyển câu hỏi vào thùng rác?', message: `Câu hỏi “${faq.question}” sẽ được chuyển vào thùng rác.`, confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.faqs.destroy(faq.id));
    };

    return (
        <AdminLayout
            title="Chi tiết câu hỏi"
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'FAQ', href: routes.admin.faqs.index }, { label: 'Chi tiết' }]}
            actions={<><Button href={routes.admin.faqs.index} variant="outline">← Danh sách</Button><Button href={routes.admin.faqs.edit(faq.id)} icon={<Pencil size={15} />}>Sửa</Button><Button variant="danger" icon={<Trash2 size={15} />} onClick={remove}>Xóa</Button></>}
        >
            <div className="admin-form-narrow">
                <Card title={faq.question}>
                    <div className="ui-detail-row"><span>Nhóm</span><div>{faq.category || '—'}</div></div>
                    <div className="ui-detail-row"><span>Hiển thị</span><div><Badge tone={faq.is_active ? 'success' : 'muted'}>{faq.is_active ? 'Có' : 'Không'}</Badge></div></div>
                    <div className="ui-detail-row"><span>Thứ tự</span><div>{faq.sort_order}</div></div>
                    <div className="ui-detail-row"><span>Trả lời</span><div className="ui-prose">{faq.answer}</div></div>
                    <div className="ui-detail-row"><span>Cập nhật</span><div>{datetime(faq.updated_at)}</div></div>
                </Card>
            </div>
            {dialog}
        </AdminLayout>
    );
}
