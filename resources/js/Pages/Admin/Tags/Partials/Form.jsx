import React from 'react';
import { useForm } from '@inertiajs/react';
import AdminLayout from '../../../../Layouts/AdminLayout';
import Card from '../../../../Components/UI/Card';
import Button from '../../../../Components/UI/Button';
import Input from '../../../../Components/UI/Input';
import Textarea from '../../../../Components/UI/Textarea';
import SlugField from '../../../../Components/Form/SlugField';
import { routes } from '../../../../lib/routes';

export default function TagForm({ tag = null }) {
    const isEdit = !!tag;

    const { data, setData, post, put, processing, errors } = useForm({
        name: tag?.name ?? '',
        slug: tag?.slug ?? '',
        description: tag?.description ?? '',
    });

    const submit = (event) => {
        event.preventDefault();
        if (isEdit) put(routes.admin.tags.update(tag.id));
        else post(routes.admin.tags.store);
    };

    return (
        <AdminLayout
            title={isEdit ? `Sửa tag: ${tag.name}` : 'Thêm tag mới'}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Tag', href: routes.admin.tags.index }, { label: isEdit ? 'Sửa' : 'Tạo mới' }]}
            actions={<Button href={routes.admin.tags.index} variant="outline">← Danh sách</Button>}
        >
            <form onSubmit={submit} noValidate className="admin-form-narrow">
                <Card title="Thông tin tag">
                    <Input label="Tên tag" name="name" required value={data.name} error={errors.name} onChange={(e) => setData('name', e.target.value)} />
                    <SlugField value={data.slug} source={data.name} error={errors.slug} onChange={(value) => setData('slug', value)} />
                    <Textarea label="Mô tả" name="description" rows={3} value={data.description} error={errors.description} onChange={(e) => setData('description', e.target.value)} />

                    <div className="ui-form-actions">
                        <Button href={routes.admin.tags.index} variant="outline">Hủy</Button>
                        <Button type="submit" loading={processing}>{isEdit ? 'Lưu thay đổi' : 'Tạo tag'}</Button>
                    </div>
                </Card>
            </form>
        </AdminLayout>
    );
}
