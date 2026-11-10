import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Plus, Pencil, Trash2 } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import Pagination from '../../../Components/UI/Pagination';
import DataTable from '../../../Components/Data/DataTable';
import FilterBar from '../../../Components/Data/FilterBar';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';

export default function FaqsIndex({ faqs, categories = [] }) {
    const { confirm, dialog } = useConfirm();
    const rows = faqs?.data || [];

    const remove = async (faq) => {
        const ok = await confirm({ title: 'Chuyển câu hỏi vào thùng rác?', message: `Câu hỏi “${faq.question}” sẽ được chuyển vào thùng rác.`, confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.faqs.destroy(faq.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Quản lý FAQ"
            description="Câu hỏi thường gặp hiển thị trên website."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'FAQ' }]}
            actions={<><Button href={routes.admin.faqs.trash} variant="outline">Thùng rác</Button><Button href={routes.admin.faqs.create} icon={<Plus size={16} />}>Thêm câu hỏi</Button></>}
        >
            <Card>
                <FilterBar
                    url={routes.admin.faqs.index}
                    values={Object.fromEntries(new URLSearchParams(window.location.search))}
                    searchPlaceholder="Tìm theo câu hỏi…"
                    filters={[{ name: 'category', label: 'Nhóm', options: categories.map((category) => ({ value: category, label: category })) }]}
                />
                <DataTable
                    empty="Chưa có câu hỏi nào"
                    rows={rows}
                    columns={[
                        { key: 'question', label: 'Câu hỏi', render: (faq) => <Link href={routes.admin.faqs.show(faq.id)} className="ui-link">{faq.question}</Link> },
                        { key: 'category', label: 'Nhóm', render: (faq) => faq.category || '—' },
                        { key: 'sort_order', label: 'Thứ tự', render: (faq) => faq.sort_order },
                        { key: 'is_active', label: 'Hiển thị', render: (faq) => <Badge tone={faq.is_active ? 'success' : 'muted'}>{faq.is_active ? 'Có' : 'Không'}</Badge> },
                        {
                            key: 'actions', label: 'Thao tác', width: 100,
                            render: (faq) => (
                                <div className="ui-row-actions">
                                    <Link href={routes.admin.faqs.edit(faq.id)} className="ui-icon-btn" title="Sửa"><Pencil size={15} /></Link>
                                    <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xóa" onClick={() => remove(faq)}><Trash2 size={15} /></button>
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
