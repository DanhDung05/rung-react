import React, { useEffect, useId, useState } from 'react';
import { useForm } from '@inertiajs/react';
import AdminLayout from '../../../../Layouts/AdminLayout';
import Card from '../../../../Components/UI/Card';
import Button from '../../../../Components/UI/Button';
import Input from '../../../../Components/UI/Input';
import Textarea from '../../../../Components/UI/Textarea';
import Checkbox from '../../../../Components/UI/Checkbox';
import { routes } from '../../../../lib/routes';

const CROP_PREVIEW_WIDTH = 260;

export default function GallerySlideForm({ slide = null }) {
    const isEdit = !!slide;
    const fileId = useId();
    const currentImage = slide?.full_url || slide?.slide_url || null;

    const { data, setData, post, transform, processing, errors } = useForm({
        title: slide?.title ?? '',
        category: slide?.category ?? '',
        accent: slide?.accent ?? '',
        description: slide?.description ?? '',
        is_active: slide ? !!slide.is_active : true,
        crop_x: Number(slide?.crop_x ?? 50),
        crop_y: Number(slide?.crop_y ?? 50),
        image: null,
    });

    const [previewUrl, setPreviewUrl] = useState(currentImage);
    const [imageSize, setImageSize] = useState(
        slide ? { width: slide.full_width, height: slide.full_height } : null
    );

    useEffect(() => {
        if (!data.image) {
            setPreviewUrl(currentImage);
            setImageSize(slide ? { width: slide.full_width, height: slide.full_height } : null);
            return undefined;
        }

        const url = URL.createObjectURL(data.image);
        setPreviewUrl(url);

        return () => URL.revokeObjectURL(url);
    }, [data.image, currentImage, slide]);

    const onImageLoad = (event) => {
        setImageSize({
            width: event.currentTarget.naturalWidth,
            height: event.currentTarget.naturalHeight,
        });
    };

    // PHP không parse multipart cho PUT; dùng POST + _method=put để upload file ổn định.
    transform((payload) => (isEdit ? { ...payload, _method: 'put' } : payload));

    const set = (key, value) => setData(key, value);

    const submit = (event) => {
        event.preventDefault();
        const options = { forceFormData: true, preserveScroll: true };

        if (isEdit) post(routes.admin.gallerySlides.update(slide.id), options);
        else post(routes.admin.gallerySlides.store, options);
    };

    const tooSmall = imageSize && (imageSize.width < 660 || imageSize.height < 900);

    return (
        <AdminLayout
            title={isEdit ? 'Sửa ảnh thư viện' : 'Thêm ảnh thư viện'}
            description="Ảnh được crop dọc 660×900 để đưa vào carousel 3D."
            breadcrumbs={[
                { label: 'Quản trị' },
                { label: 'Thư viện ảnh', href: routes.admin.gallerySlides.index },
                { label: isEdit ? 'Sửa' : 'Tạo mới' },
            ]}
            actions={<Button href={routes.admin.gallerySlides.index} variant="outline">← Danh sách</Button>}
        >
            <form onSubmit={submit} noValidate className="admin-form-layout">
                <div>
                    <Card title="Thông tin ảnh">
                        <div className="ui-form-grid">
                            <Input
                                label="Tên ảnh"
                                name="title"
                                required
                                value={data.title}
                                error={errors.title}
                                placeholder="Ví dụ: Hồ trên núi"
                                onChange={(event) => set('title', event.target.value)}
                            />
                            <Input
                                label="Nhóm / loại hình"
                                name="category"
                                required
                                value={data.category}
                                error={errors.category}
                                placeholder="Vùng miền / Loại hình"
                                hint="Ví dụ: Miền Bắc / Trekking"
                                onChange={(event) => set('category', event.target.value)}
                            />
                        </div>

                        <Input
                            label="Chữ nhấn"
                            name="accent"
                            value={data.accent}
                            error={errors.accent}
                            placeholder="A quieter kind of wild."
                            onChange={(event) => set('accent', event.target.value)}
                        />

                        <Textarea
                            label="Mô tả"
                            name="description"
                            rows={4}
                            value={data.description}
                            error={errors.description}
                            placeholder="Mô tả ngắn về khoảnh khắc trong ảnh."
                            onChange={(event) => set('description', event.target.value)}
                        />

                        <div className="ui-field">
                            <label className="ui-label" htmlFor={fileId}>
                                {isEdit ? 'Thay ảnh (để trống nếu giữ ảnh cũ)' : 'Ảnh gốc *'}
                            </label>
                            <input
                                id={fileId}
                                type="file"
                                name="image"
                                accept="image/jpeg,image/png,image/webp"
                                className="ui-input"
                                onChange={(event) => set('image', event.target.files?.[0] || null)}
                            />
                            {errors.image && <span className="ui-error">{errors.image}</span>}
                            <span className="ui-hint">JPG, JPEG, PNG, WEBP — tối đa 10 MB. Ảnh phải đủ lớn để crop 660×900.</span>
                        </div>

                        <div className="ui-form-grid__checkbox">
                            <Checkbox
                                label="Hiển thị trong carousel"
                                name="is_active"
                                checked={!!data.is_active}
                                error={errors.is_active}
                                onChange={(event) => set('is_active', event.target.checked)}
                            />
                        </div>
                    </Card>

                    <Card title="Tâm crop">
                        <div className="ui-field">
                            <label className="ui-label" htmlFor="crop-x">Ngang — {data.crop_x}%</label>
                            <input
                                id="crop-x"
                                name="crop_x"
                                type="range"
                                min="0"
                                max="100"
                                step="1"
                                value={data.crop_x}
                                onChange={(event) => set('crop_x', Number(event.target.value))}
                                style={{ width: '100%' }}
                            />
                            <div className="ui-hint" style={{ display: 'flex', justifyContent: 'space-between' }}>
                                <span>Trái</span><span>Giữa</span><span>Phải</span>
                            </div>
                        </div>

                        <div className="ui-field">
                            <label className="ui-label" htmlFor="crop-y">Dọc — {data.crop_y}%</label>
                            <input
                                id="crop-y"
                                name="crop_y"
                                type="range"
                                min="0"
                                max="100"
                                step="1"
                                value={data.crop_y}
                                onChange={(event) => set('crop_y', Number(event.target.value))}
                                style={{ width: '100%' }}
                            />
                            <div className="ui-hint" style={{ display: 'flex', justifyContent: 'space-between' }}>
                                <span>Trên</span><span>Giữa</span><span>Dưới</span>
                            </div>
                        </div>
                        {errors.crop_x && <span className="ui-error">{errors.crop_x}</span>}
                        {errors.crop_y && <span className="ui-error">{errors.crop_y}</span>}
                    </Card>
                </div>

                <aside>
                    <Card title="Xem trước 660×900" description="Khớp đúng vùng crop server sẽ tạo.">
                        <div
                            style={{
                                width: CROP_PREVIEW_WIDTH,
                                maxWidth: '100%',
                                aspectRatio: '660 / 900',
                                overflow: 'hidden',
                                borderRadius: 8,
                                background: '#e9e4d6',
                                position: 'relative',
                                margin: '0 auto',
                            }}
                        >
                            {previewUrl ? (
                                <img
                                    src={previewUrl}
                                    alt="Xem trước crop"
                                    onLoad={onImageLoad}
                                    style={{
                                        position: 'absolute',
                                        inset: 0,
                                        width: '100%',
                                        height: '100%',
                                        objectFit: 'cover',
                                        objectPosition: `${data.crop_x}% ${data.crop_y}%`,
                                    }}
                                />
                            ) : (
                                <div style={{ display: 'grid', placeItems: 'center', height: '100%', color: '#7a8479' }}>
                                    Chưa có ảnh
                                </div>
                            )}
                        </div>

                        {imageSize && (
                            <p className="ui-hint" style={{ textAlign: 'center', marginTop: 10 }}>
                                Ảnh: {imageSize.width} × {imageSize.height}px
                            </p>
                        )}
                        {tooSmall && (
                            <p className="ui-error">
                                Ảnh nhỏ hơn 660×900 sau khi xoay; vui lòng chọn ảnh lớn hơn.
                            </p>
                        )}
                        {!tooSmall && imageSize && (
                            <p className="ui-hint" style={{ textAlign: 'center' }}>
                                Xem trước dùng đúng object-position của server.
                            </p>
                        )}
                    </Card>
                </aside>

                <div className="ui-form-actions" style={{ gridColumn: '1 / -1' }}>
                    <Button href={routes.admin.gallerySlides.index} variant="outline">Huỷ</Button>
                    <Button type="submit" loading={processing}>{isEdit ? 'Lưu thay đổi' : 'Tạo ảnh'}</Button>
                </div>
            </form>
        </AdminLayout>
    );
}
