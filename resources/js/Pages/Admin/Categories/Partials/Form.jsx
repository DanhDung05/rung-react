import React from 'react';
import { useForm } from '@inertiajs/react';
import AdminLayout from '../../../../Layouts/AdminLayout';
import Card from '../../../../Components/UI/Card';
import Button from '../../../../Components/UI/Button';
import Input from '../../../../Components/UI/Input';
import Select from '../../../../Components/UI/Select';
import Textarea from '../../../../Components/UI/Textarea';
import Checkbox from '../../../../Components/UI/Checkbox';
import ImageField from '../../../../Components/Form/ImageField';
import SeoFields from '../../../../Components/Form/SeoFields';
import SlugField from '../../../../Components/Form/SlugField';
import { routes } from '../../../../lib/routes';
import { mediaUrl } from '../../../../lib/format';

export default function CategoryForm({ category = null, parents = [] }) {
    const isEdit = !!category;

    const { data, setData, post, put, processing, errors } = useForm({
        type: category?.type ?? 'tour',
        parent_id: category?.parent_id ?? '',
        name: category?.name ?? '',
        slug: category?.slug ?? '',
        description: category?.description ?? '',
        image: null,
        meta_title: category?.meta_title ?? '',
        meta_description: category?.meta_description ?? '',
        sort_order: category?.sort_order ?? 0,
        is_active: category ? !!category.is_active : true,
    });

    const set = (key, value) => setData(key, value);

    const submit = (event) => {
        event.preventDefault();
        if (isEdit) put(routes.admin.categories.update(category.id), { forceFormData: true });
        else post(routes.admin.categories.store, { forceFormData: true });
    };

    return (
        <AdminLayout
            title={isEdit ? `Sửa danh mục: ${category.name}` : 'Thêm danh mục mới'}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Danh mục', href: routes.admin.categories.index }, { label: isEdit ? 'Sửa' : 'Tạo mới' }]}
            actions={<Button href={routes.admin.categories.index} variant="outline">← Danh sách</Button>}
        >
            <form onSubmit={submit} noValidate>
                <div className="admin-form-layout">
                    <div>
                        <Card title="Thông tin danh mục">
                            <div className="ui-form-grid">
                                <Select
                                    label="Loại danh mục"
                                    name="type"
                                    required
                                    value={data.type}
                                    placeholder={null}
                                    options={[{ value: 'tour', label: 'Danh mục tour' }, { value: 'post', label: 'Danh mục bài viết' }]}
                                    error={errors.type}
                                    onChange={(e) => set('type', e.target.value)}
                                />
                                <Select
                                    label="Danh mục cha"
                                    name="parent_id"
                                    value={data.parent_id}
                                    placeholder="— Không có (cấp gốc) —"
                                    options={parents.map((item) => ({ value: item.id, label: item.name }))}
                                    error={errors.parent_id}
                                    onChange={(e) => set('parent_id', e.target.value)}
                                />
                            </div>

                            <Input label="Tên danh mục" name="name" required value={data.name} error={errors.name} onChange={(e) => set('name', e.target.value)} />
                            <SlugField value={data.slug} source={data.name} error={errors.slug} onChange={(value) => set('slug', value)} />
                            <Textarea label="Mô tả" name="description" rows={3} value={data.description} error={errors.description} onChange={(e) => set('description', e.target.value)} />
                        </Card>

                        <Card title="SEO">
                            <SeoFields data={data} errors={errors} onChange={set} />
                        </Card>
                    </div>

                    <aside>
                        <Card title="Hiển thị">
                            <Input label="Thứ tự" name="sort_order" type="number" min="0" value={data.sort_order} error={errors.sort_order} onChange={(e) => set('sort_order', e.target.value)} />
                            <Checkbox label="Đang hoạt động" name="is_active" checked={!!data.is_active} error={errors.is_active} onChange={(e) => set('is_active', e.target.checked)} />
                            <Button type="submit" loading={processing} className="ui-btn--block">{isEdit ? 'Lưu thay đổi' : 'Tạo danh mục'}</Button>
                        </Card>

                        <Card title="Ảnh đại diện">
                            <ImageField label="Ảnh" name="image" file={data.image} currentUrl={mediaUrl(category?.image)} error={errors.image} onChange={(file) => set('image', file)} />
                        </Card>
                    </aside>
                </div>
            </form>
        </AdminLayout>
    );
}
