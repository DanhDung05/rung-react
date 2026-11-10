import React from 'react';
import { useForm } from '@inertiajs/react';
import AdminLayout from '../../../../Layouts/AdminLayout';
import Card from '../../../../Components/UI/Card';
import Button from '../../../../Components/UI/Button';
import Input from '../../../../Components/UI/Input';
import Select from '../../../../Components/UI/Select';
import Checkbox from '../../../../Components/UI/Checkbox';
import ImageField from '../../../../Components/Form/ImageField';
import { routes } from '../../../../lib/routes';
import { mediaUrl } from '../../../../lib/format';

export default function UserForm({ user = null }) {
    const isEdit = !!user;

    const { data, setData, post, put, processing, errors } = useForm({
        name: user?.name ?? '',
        email: user?.email ?? '',
        password: '',
        password_confirmation: '',
        role: user?.role ?? 'editor',
        is_active: user ? !!user.is_active : true,
        avatar: null,
    });

    const set = (key, value) => setData(key, value);

    const submit = (event) => {
        event.preventDefault();
        if (isEdit) put(routes.admin.users.update(user.id), { forceFormData: true });
        else post(routes.admin.users.store, { forceFormData: true });
    };

    return (
        <AdminLayout
            title={isEdit ? `Sửa tài khoản: ${user.name}` : 'Thêm tài khoản mới'}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Người dùng', href: routes.admin.users.index }, { label: isEdit ? 'Sửa' : 'Tạo mới' }]}
            actions={<Button href={routes.admin.users.index} variant="outline">← Danh sách</Button>}
        >
            <form onSubmit={submit} noValidate className="admin-form-narrow">
                <Card title="Thông tin tài khoản">
                    <div className="ui-form-grid">
                        <Input label="Họ tên" name="name" required value={data.name} error={errors.name} onChange={(e) => set('name', e.target.value)} />
                        <Input label="Email" name="email" type="email" required value={data.email} error={errors.email} onChange={(e) => set('email', e.target.value)} />
                    </div>

                    <div className="ui-form-grid">
                        <Input
                            label={isEdit ? 'Mật khẩu mới (bỏ trống nếu giữ nguyên)' : 'Mật khẩu'}
                            name="password"
                            type="password"
                            required={!isEdit}
                            autoComplete="new-password"
                            value={data.password}
                            error={errors.password}
                            onChange={(e) => set('password', e.target.value)}
                        />
                        <Input
                            label="Xác nhận mật khẩu"
                            name="password_confirmation"
                            type="password"
                            required={!isEdit}
                            autoComplete="new-password"
                            value={data.password_confirmation}
                            error={errors.password_confirmation}
                            onChange={(e) => set('password_confirmation', e.target.value)}
                        />
                    </div>

                    <div className="ui-form-grid">
                        <Select
                            label="Vai trò"
                            name="role"
                            required
                            value={data.role}
                            options={[{ value: 'admin', label: 'Quản trị viên' }, { value: 'editor', label: 'Biên tập viên' }]}
                            placeholder={null}
                            error={errors.role}
                            hint={isEdit && user?.is_admin ? 'Bạn không thể tự đổi vai trò của chính mình.' : null}
                            onChange={(e) => set('role', e.target.value)}
                        />
                        <div className="ui-form-grid__checkbox">
                            <Checkbox label="Đang hoạt động" name="is_active" checked={!!data.is_active} error={errors.is_active} onChange={(e) => set('is_active', e.target.checked)} />
                        </div>
                    </div>

                    <ImageField
                        label="Ảnh đại diện"
                        name="avatar"
                        file={data.avatar}
                        currentUrl={mediaUrl(user?.avatar)}
                        error={errors.avatar}
                        accept="image/jpeg,image/png,image/webp"
                        onChange={(file) => set('avatar', file)}
                    />

                    <div className="ui-form-actions">
                        <Button href={routes.admin.users.index} variant="outline">Hủy</Button>
                        <Button type="submit" loading={processing}>{isEdit ? 'Lưu thay đổi' : 'Tạo tài khoản'}</Button>
                    </div>
                </Card>
            </form>
        </AdminLayout>
    );
}
