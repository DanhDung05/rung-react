import React from 'react';
import { router } from '@inertiajs/react';
import { RotateCcw, Trash2 } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Pagination from '../../../Components/UI/Pagination';
import DataTable from '../../../Components/Data/DataTable';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { datetime } from '../../../lib/format';

export default function FaqsTrash({ faqs }) {
    const { confirm, dialog } = useConfirm();
    const rows = faqs?.data || [];

    const forceDelete = async (faq) => {
        const ok = await confirm({ title: 'Xóa vĩnh viễn?', message: 'Câu hỏi này sẽ bị xóa vĩnh viễn.', confirmLabel: 'Xóa vĩnh viễn' });
        if (ok) router.delete(routes.admin.faqs.forceDestroy(faq.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thùng rác — FAQ"
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'FAQ', href: routes.admin.faqs.index }, { label: 'Thùng rác' }]}
            actions={<Button href={routes.admin.faqs.index} variant="outline">← Danh sách</Button>}
        >
            <Card>
                <DataTable
                    empty="Thùng rác trống"
                    rows={rows}
                    columns={[
                        { key: 'question', label: 'Câu hỏi', render: (faq) => faq.question },
                        { key: 'category', label: 'Nhóm', render: (faq) => faq.category || '—' },
                        { key: 'deleted_at', label: 'Đã xóa lúc', render: (faq) => datetime(faq.deleted_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 180,
                            render: (faq) => (
                                <div className="ui-row-actions">
                                    <Button size="sm" variant="outline" icon={<RotateCcw size={14} />} onClick={() => router.patch(routes.admin.faqs.restore(faq.id), {}, { preserveScroll: true })}>Khôi phục</Button>
                                    <Button size="sm" variant="danger" icon={<Trash2 size={14} />} onClick={() => forceDelete(faq)}>Xóa</Button>
                                </div>
                            ),
                        },
                    ]}
                />
                <Pagination links={faqs?.links} from={faqs?.from} to={faqs?.to} total={faqs?.total} />
            </Card>
            {dialog}
        </AdminLayout>
    );
}
