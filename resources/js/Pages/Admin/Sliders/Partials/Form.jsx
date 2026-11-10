import React from 'react';
import { useForm } from '@inertiajs/react';
import AdminLayout from '../../../../Layouts/AdminLayout';
import Card from '../../../../Components/UI/Card';
import Button from '../../../../Components/UI/Button';
import Input from '../../../../Components/UI/Input';
import Select from '../../../../Components/UI/Select';
import Checkbox from '../../../../Components/UI/Checkbox';
import ImageField from '../../../../Components/Form/ImageField';
import { toLocalInput } from '../../../../Components/Form/StatusField';
import { routes } from '../../../../lib/routes';
import { mediaUrl } from '../../../../lib/format';

const POSITIONS = [
    { value: 'home_hero', label: 'Trang chủ — Hero' },
    { value: 'home_middle', label: 'Trang chủ — Giữa trang' },
];

export default function SliderForm({ slider = null }) {
    const isEdit = !!slider;

    const { data, setData, post, put, processing, errors } = useForm({
        title: slider?.title ?? '',
        subtitle: slider?.subtitle ?? '',
        image: null,
        link_url: slider?.link_url ?? '',
        link_target: slider?.link_target ?? '_self',
        position: slider?.position ?? 'home_hero',
        sort_order: slider?.sort_order ?? 0,
        is_active: slider ? !!slider.is_active : true,
        start_at: toLocalInput(slider?.start_at),
        end_at: toLocalInput(slider?.end_at),
    });

    const set = (key, value) => setData(key, value);

    const submit = (event) => {
        event.preventDefault();
        if (isEdit) put(routes.admin.sliders.update(slider.id), { forceFormData: true });
        else post(routes.admin.sliders.store, { forceFormData: true });
    };

    return (
        <AdminLayout
            title={isEdit ? 'Sửa slider' : 'Thêm slider mới'}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Slider', href: routes.admin.sliders.index }, { label: isEdit ? 'Sửa' : 'Tạo mới' }]}
            actions={<Button href={routes.admin.sliders.index} variant="outline">← Danh sách</Button>}
        >
            <form onSubmit={submit} noValidate className="admin-form-narrow">
                <Card title="Thông tin slider">
                    <Input label="Tiêu đề" name="title" value={data.title} error={errors.title} onChange={(e) => set('title', e.target.value)} />
                    <Input label="Phụ đề" name="subtitle" value={data.subtitle} error={errors.subtitle} onChange={(e) => set('subtitle', e.target.value)} />

                    <ImageField
                        label={isEdit ? 'Thay ảnh (để trống nếu giữ ảnh cũ)' : 'Ảnh slider *'}
                        name="image"
                        file={data.image}
                        currentUrl={mediaUrl(slider?.image)}
                        error={errors.image}
                        hint="Ảnh ngang, khuyến nghị 1920×900px."
                        onChange={(file) => set('image', file)}
                    />

                    <div className="ui-form-grid">
                        <Select label="Vị trí" name="position" value={data.position} options={POSITIONS} placeholder={null} error={errors.position} onChange={(e) => set('position', e.target.value)} />
                        <Select
                            label="Mở liên kết"
                            name="link_target"
                            value={data.link_target}
                            options={[{ value: '_self', label: 'Cùng tab' }, { value: '_blank', label: 'Tab mới' }]}
                            placeholder={null}
                            error={errors.link_target}
                            onChange={(e) => set('link_target', e.target.value)}
                        />
                    </div>

                    <Input label="Đường dẫn liên kết" name="link_url" value={data.link_url} error={errors.link_url} hint="Ví dụ: /tour/abc hoặc https://…" onChange={(e) => set('link_url', e.target.value)} />

                    <div className="ui-form-grid">
                        <Input label="Bắt đầu hiển thị" name="start_at" type="datetime-local" value={data.start_at} error={errors.start_at} onChange={(e) => set('start_at', e.target.value)} />
                        <Input label="Kết thúc hiển thị" name="end_at" type="datetime-local" value={data.end_at} error={errors.end_at} onChange={(e) => set('end_at', e.target.value)} />
                        <Input label="Thứ tự" name="sort_order" type="number" min="0" value={data.sort_order} error={errors.sort_order} onChange={(e) => set('sort_order', e.target.value)} />
                        <div className="ui-form-grid__checkbox">
                            <Checkbox label="Đang hiển thị" name="is_active" checked={!!data.is_active} error={errors.is_active} onChange={(e) => set('is_active', e.target.checked)} />
                        </div>
                    </div>

                    <div className="ui-form-actions">
                        <Button href={routes.admin.sliders.index} variant="outline">Hủy</Button>
                        <Button type="submit" loading={processing}>{isEdit ? 'Lưu thay đổi' : 'Tạo slider'}</Button>
                    </div>
                </Card>
            </form>
        </AdminLayout>
    );
}
