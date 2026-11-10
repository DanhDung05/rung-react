import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Pencil, UserCheck, UserX } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { datetime, mediaUrl, number } from '../../../lib/format';
import { ROLE } from '../../../lib/constants';

export default function UsersShow({ user }) {
    const { confirm, dialog } = useConfirm();

    const toggle = async () => {
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
            title={user.name}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Người dùng', href: routes.admin.users.index }, { label: 'Chi tiết' }]}
            actions={
                <>
                    <Button href={routes.admin.users.index} variant="outline">← Danh sách</Button>
                    <Button href={routes.admin.users.edit(user.id)} icon={<Pencil size={15} />}>Sửa</Button>
                    <Button variant={user.is_active ? 'danger' : 'primary'} icon={user.is_active ? <UserX size={15} /> : <UserCheck size={15} />} onClick={toggle}>
                        {user.is_active ? 'Vô hiệu hóa' : 'Kích hoạt'}
                    </Button>
                </>
            }
        >
            <div className="admin-form-layout">
                <div>
                    <Card title="Thông tin tài khoản">
                        <div className="ui-detail-row"><span>Email</span><div><a className="ui-link" href={`mailto:${user.email}`}>{user.email}</a></div></div>
                        <div className="ui-detail-row"><span>Vai trò</span><div><Badge tone={ROLE[user.role]?.tone}>{ROLE[user.role]?.label || user.role}</Badge></div></div>
                        <div className="ui-detail-row"><span>Trạng thái</span><div><Badge tone={user.is_active ? 'success' : 'danger'}>{user.is_active ? 'Hoạt động' : 'Đã khóa'}</Badge></div></div>
                        <div className="ui-detail-row"><span>Số bài viết</span><div>{number(user.posts_count)}</div></div>
                        <div className="ui-detail-row"><span>Ngày tạo</span><div>{datetime(user.created_at)}</div></div>
                        <div className="ui-detail-row"><span>Cập nhật</span><div>{datetime(user.updated_at)}</div></div>
                    </Card>
                </div>
                <aside>
                    <Card title="Ảnh đại diện">
                        {user.avatar ? <img className="ui-detail-image" src={mediaUrl(user.avatar)} alt={user.name} /> : <p className="ui-hint">Chưa có ảnh.</p>}
                    </Card>
                    <Card title="Bài viết của tác giả">
                        <Link href={`${routes.admin.posts.index}?user_id=${user.id}`} className="ui-btn ui-btn--outline ui-btn--block">
                            Xem bài viết
                        </Link>
                    </Card>
                </aside>
            </div>
            {dialog}
        </AdminLayout>
    );
}
