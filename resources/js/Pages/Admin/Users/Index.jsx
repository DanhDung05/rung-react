import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Plus, Pencil, Eye, UserCheck, UserX } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import Pagination from '../../../Components/UI/Pagination';
import DataTable from '../../../Components/Data/DataTable';
import FilterBar from '../../../Components/Data/FilterBar';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { datetime, mediaUrl } from '../../../lib/format';
import { ROLE } from '../../../lib/constants';

export default function UsersIndex({ users }) {
    const { confirm, dialog } = useConfirm();
    const rows = users?.data || [];

    const toggle = async (user) => {
        if (user.is_active) {
            const ok = await confirm({
                title: 'Vô hiệu hóa tài khoản?',
                message: `“${user.name}” sẽ không thể đăng nhập cho đến khi được kích hoạt lại.`,
                confirmLabel: 'Vô hiệu hóa',
            });
            if (!ok) return;
        }

        router.patch(routes.admin.userToggleActive(user.id), {}, { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Quản lý người dùng"
            description="Chỉ quản trị viên mới có quyền truy cập module này."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Người dùng' }]}
            actions={<Button href={routes.admin.users.create} icon={<Plus size={16} />}>Thêm tài khoản</Button>}
        >
            <Card>
                <FilterBar
                    url={routes.admin.users.index}
                    values={Object.fromEntries(new URLSearchParams(window.location.search))}
                    searchPlaceholder="Tìm theo tên hoặc email…"
                    filters={[
                        { name: 'role', label: 'Vai trò', options: [{ value: 'admin', label: 'Quản trị viên' }, { value: 'editor', label: 'Biên tập viên' }, { value: 'user', label: 'Người dùng' }] },
                        { name: 'status', label: 'Trạng thái', options: [{ value: 'active', label: 'Đang hoạt động' }, { value: 'inactive', label: 'Đã vô hiệu' }] },
                    ]}
                />

                <DataTable
                    empty="Chưa có tài khoản nào"
                    rows={rows}
                    columns={[
                        {
                            key: 'name',
                            label: 'Tài khoản',
                            render: (user) => (
                                <div className="ui-user-cell">
                                    {user.avatar ? <img className="ui-avatar" src={mediaUrl(user.avatar)} alt="" /> : <span className="ui-avatar">{String(user.name || '?').charAt(0).toUpperCase()}</span>}
                                    <div>
                                        <Link href={routes.admin.users.show(user.id)} className="ui-link">{user.name}</Link>
                                        <div className="ui-sub">{user.email}</div>
                                    </div>
                                </div>
                            ),
                        },
                        { key: 'role', label: 'Vai trò', render: (user) => <Badge tone={ROLE[user.role]?.tone}>{ROLE[user.role]?.label || user.role}</Badge> },
                        { key: 'is_active', label: 'Trạng thái', render: (user) => <Badge tone={user.is_active ? 'success' : 'danger'}>{user.is_active ? 'Hoạt động' : 'Đã khóa'}</Badge> },
                        { key: 'created_at', label: 'Ngày tạo', render: (user) => datetime(user.created_at) },
                        {
                            key: 'actions',
                            label: 'Thao tác',
                            width: 150,
                            render: (user) => (
                                <div className="ui-row-actions">
                                    <Link href={routes.admin.users.edit(user.id)} className="ui-icon-btn" title="Sửa"><Pencil size={15} /></Link>
                                    <Link href={routes.admin.users.show(user.id)} className="ui-icon-btn" title="Chi tiết"><Eye size={15} /></Link>
                                    <button type="button" className="ui-icon-btn" title={user.is_active ? 'Vô hiệu hóa' : 'Kích hoạt'} onClick={() => toggle(user)}>
                                        {user.is_active ? <UserX size={15} /> : <UserCheck size={15} />}
                                    </button>
                                </div>
                            ),
                        },
                    ]}
                />
                <Pagination links={users?.links} from={users?.from} to={users?.to} total={users?.total} />
            </Card>
            {dialog}
        </AdminLayout>
    );
}
