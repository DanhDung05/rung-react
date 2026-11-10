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
import RichTextEditor from '../../../../Components/Form/RichTextEditor';
import SeoFields from '../../../../Components/Form/SeoFields';
import StatusField from '../../../../Components/Form/StatusField';
import SlugField from '../../../../Components/Form/SlugField';
import { routes } from '../../../../lib/routes';
import { mediaUrl } from '../../../../lib/format';

export default function DestinationForm({ destination = null, parents = [] }) {
    const isEdit = !!destination;

    const { data, setData, post, put, processing, errors } = useForm({
        parent_id: destination?.parent_id ?? '',
        name: destination?.name ?? '',
        slug: destination?.slug ?? '',
        description: destination?.description ?? '',
        content: destination?.content ?? '',
        image: null,
        region: destination?.region ?? '',
        province: destination?.province ?? '',
        latitude: destination?.latitude ?? '',
        longitude: destination?.longitude ?? '',
        altitude: destination?.altitude ?? '',
        meta_title: destination?.meta_title ?? '',
        meta_description: destination?.meta_description ?? '',
        is_featured: !!destination?.is_featured,
        status: destination?.status ?? 'draft',
        published_at: destination?.published_at ?? '',
        sort_order: destination?.sort_order ?? 0,
    });

    const set = (key, value) => setData(key, value);

    const submit = (event) => {
        event.preventDefault();
        if (isEdit) put(routes.admin.destinations.update(destination.id), { forceFormData: true });
        else post(routes.admin.destinations.store, { forceFormData: true });
    };

    return (
        <AdminLayout
            title={isEdit ? `Sửa điểm đến: ${destination.name}` : 'Thêm điểm đến mới'}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Điểm đến', href: routes.admin.destinations.index }, { label: isEdit ? 'Sửa' : 'Tạo mới' }]}
            actions={<Button href={routes.admin.destinations.index} variant="outline">← Danh sách</Button>}
        >
            <form onSubmit={submit} noValidate>
                <div className="admin-form-layout">
                    <div>
                        <Card title="Thông tin điểm đến">
                            <div className="ui-form-grid">
                                <Input label="Tên điểm đến" name="name" required value={data.name} error={errors.name} onChange={(e) => set('name', e.target.value)} />
                                <Select
                                    label="Điểm đến cha"
                                    name="parent_id"
                                    value={data.parent_id}
                                    placeholder="— Không có (cấp gốc) —"
                                    options={parents.map((item) => ({ value: item.id, label: item.name }))}
                                    error={errors.parent_id}
                                    onChange={(e) => set('parent_id', e.target.value)}
                                />
                            </div>

                            <SlugField value={data.slug} source={data.name} error={errors.slug} onChange={(value) => set('slug', value)} />

                            <Textarea label="Mô tả ngắn" name="description" rows={3} value={data.description} error={errors.description} onChange={(e) => set('description', e.target.value)} />

                            <RichTextEditor label="Nội dung chi tiết" value={data.content} error={errors.content} uploadUrl={routes.admin.uploadImage} onChange={(value) => set('content', value)} />
                        </Card>

                        <Card title="Vị trí địa lý">
                            <div className="ui-form-grid ui-form-grid--3">
                                <Input label="Vùng miền" name="region" value={data.region} error={errors.region} onChange={(e) => set('region', e.target.value)} />
                                <Input label="Tỉnh / thành" name="province" value={data.province} error={errors.province} onChange={(e) => set('province', e.target.value)} />
                                <Input label="Độ cao (m)" name="altitude" type="number" value={data.altitude} error={errors.altitude} onChange={(e) => set('altitude', e.target.value)} />
                                <Input label="Vĩ độ" name="latitude" type="number" step="0.0000001" value={data.latitude} error={errors.latitude} onChange={(e) => set('latitude', e.target.value)} />
                                <Input label="Kinh độ" name="longitude" type="number" step="0.0000001" value={data.longitude} error={errors.longitude} onChange={(e) => set('longitude', e.target.value)} />
                            </div>
                        </Card>

                        <Card title="SEO">
                            <SeoFields data={data} errors={errors} onChange={set} />
                        </Card>
                    </div>

                    <aside>
                        <Card title="Xuất bản">
                            <StatusField data={data} errors={errors} onChange={set} />
                            <Button type="submit" loading={processing} className="ui-btn--block">{isEdit ? 'Lưu thay đổi' : 'Tạo điểm đến'}</Button>
                        </Card>

                        <Card title="Ảnh đại diện">
                            <ImageField
                                label="Ảnh chính"
                                name="image"
                                file={data.image}
                                currentUrl={mediaUrl(destination?.image)}
                                error={errors.image}
                                onChange={(file) => set('image', file)}
                            />
                        </Card>
                    </aside>
                </div>
            </form>
        </AdminLayout>
    );
}
