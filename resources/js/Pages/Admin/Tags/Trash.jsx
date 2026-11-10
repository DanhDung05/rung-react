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

export default function TagsTrash({ tags }) {
    const { confirm, dialog } = useConfirm();
    const rows = tags?.data || [];

    const forceDelete = async (tag) => {
        const ok = await confirm({ title: 'Xóa vĩnh viễn?', message: `Tag “${tag.name}” sẽ bị xóa vĩnh viễn.`, confirmLabel: 'Xóa vĩnh viễn' });
        if (ok) router.delete(routes.admin.tags.forceDestroy(tag.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thùng rác — Tag"
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Tag', href: routes.admin.tags.index }, { label: 'Thùng rác' }]}
            actions={<Button href={routes.admin.tags.index} variant="outline">← Danh sách</Button>}
        >
            <Card>
                <DataTable
                    empty="Thùng rác trống"
                    rows={rows}
                    columns={[
                        { key: 'name', label: 'Tag', render: (tag) => tag.name },
                        { key: 'slug', label: 'Slug', render: (tag) => <code>{tag.slug}</code> },
                        { key: 'deleted_at', label: 'Đã xóa lúc', render: (tag) => datetime(tag.deleted_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 180,
                            render: (tag) => (
                                <div className="ui-row-actions">
                                    <Button size="sm" variant="outline" icon={<RotateCcw size={14} />} onClick={() => router.patch(routes.admin.tags.restore(tag.id), {}, { preserveScroll: true })}>Khôi phục</Button>
                                    <Button size="sm" variant="danger" icon={<Trash2 size={14} />} onClick={() => forceDelete(tag)}>Xóa</Button>
                                </div>
                            ),
                        },
                    ]}
                />
                <Pagination links={tags?.links} from={tags?.from} to={tags?.to} total={tags?.total} />
            </Card>
            {dialog}
        </AdminLayout>
    );
}
