import React from 'react';
import { useForm } from '@inertiajs/react';
import AdminLayout from '../../../../Layouts/AdminLayout';
import Card from '../../../../Components/UI/Card';
import Button from '../../../../Components/UI/Button';
import Input from '../../../../Components/UI/Input';
import Select from '../../../../Components/UI/Select';
import Textarea from '../../../../Components/UI/Textarea';
import Checkbox from '../../../../Components/UI/Checkbox';
import { routes } from '../../../../lib/routes';

const LOCATIONS = [
    { value: 'main', label: 'Menu chính (header)' },
    { value: 'footer', label: 'Menu footer' },
    { value: 'top', label: 'Menu trên cùng' },
];

export default function MenuForm({ menu = null }) {
    const isEdit = !!menu;

    const { data, setData, post, put, processing, errors } = useForm({
        name: menu?.name ?? '',
        location: menu?.location ?? 'main',
        description: menu?.description ?? '',
        is_active: menu ? !!menu.is_active : true,
    });

    const set = (key, value) => setData(key, value);

    const submit = (event) => {
        event.preventDefault();
        if (isEdit) put(routes.admin.menus.update(menu.id));
        else post(routes.admin.menus.store);
    };

    return (
        <AdminLayout
            title={isEdit ? `Sửa menu: ${menu.name}` : 'Thêm menu mới'}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Menu', href: routes.admin.menus.index }, { label: isEdit ? 'Sửa' : 'Tạo mới' }]}
            actions={<Button href={routes.admin.menus.index} variant="outline">← Danh sách</Button>}
        >
            <form onSubmit={submit} noValidate className="admin-form-narrow">
                <Card title="Thông tin menu">
                    <Input label="Tên menu" name="name" required value={data.name} error={errors.name} onChange={(e) => set('name', e.target.value)} />
                    <Select
                        label="Vị trí hiển thị"
                        name="location"
                        required
                        value={data.location}
                        options={LOCATIONS}
                        placeholder={null}
                        error={errors.location}
                        hint="Mỗi vị trí chỉ nên có một menu đang hoạt động."
                        onChange={(e) => set('location', e.target.value)}
                    />
                    <Textarea label="Mô tả" name="description" rows={3} value={data.description} error={errors.description} onChange={(e) => set('description', e.target.value)} />
                    <Checkbox label="Đang hoạt động" name="is_active" checked={!!data.is_active} error={errors.is_active} onChange={(e) => set('is_active', e.target.checked)} />

                    <div className="ui-form-actions">
                        <Button href={routes.admin.menus.index} variant="outline">Hủy</Button>
                        <Button type="submit" loading={processing}>{isEdit ? 'Lưu thay đổi' : 'Tạo menu'}</Button>
                    </div>
                </Card>
            </form>
        </AdminLayout>
    );
}
