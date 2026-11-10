import React from 'react';
import { useForm } from '@inertiajs/react';
import AdminLayout from '../../../../Layouts/AdminLayout';
import Card from '../../../../Components/UI/Card';
import Button from '../../../../Components/UI/Button';
import Input from '../../../../Components/UI/Input';
import Select from '../../../../Components/UI/Select';
import Textarea from '../../../../Components/UI/Textarea';
import ImageField from '../../../../Components/Form/ImageField';
import RichTextEditor from '../../../../Components/Form/RichTextEditor';
import SeoFields from '../../../../Components/Form/SeoFields';
import StatusField from '../../../../Components/Form/StatusField';
import SlugField from '../../../../Components/Form/SlugField';
import { routes } from '../../../../lib/routes';
import { mediaUrl } from '../../../../lib/format';

const TEMPLATES = [
    { value: 'default', label: 'Mặc định' },
    { value: 'about', label: 'Giới thiệu' },
    { value: 'contact', label: 'Liên hệ' },
    { value: 'faq', label: 'FAQ' },
];

export default function PageForm({ page = null }) {
    const isEdit = !!page;

    const { data, setData, post, put, processing, errors } = useForm({
        title: page?.title ?? '',
        slug: page?.slug ?? '',
        content: page?.content ?? '',
        featured_image: null,
        template: page?.template ?? 'default',
        meta_title: page?.meta_title ?? '',
        meta_description: page?.meta_description ?? '',
        status: page?.status ?? 'draft',
        published_at: page?.published_at ?? '',
    });

    const set = (key, value) => setData(key, value);

    const submit = (event) => {
        event.preventDefault();
        if (isEdit) put(routes.admin.pages.update(page.id), { forceFormData: true });
        else post(routes.admin.pages.store, { forceFormData: true });
    };

    return (
        <AdminLayout
            title={isEdit ? `Sửa trang: ${page.title}` : 'Thêm trang mới'}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Trang tĩnh', href: routes.admin.pages.index }, { label: isEdit ? 'Sửa' : 'Tạo mới' }]}
            actions={<Button href={routes.admin.pages.index} variant="outline">← Danh sách</Button>}
        >
            <form onSubmit={submit} noValidate>
                <div className="admin-form-layout">
                    <div>
                        <Card title="Nội dung trang">
                            <Input label="Tiêu đề" name="title" required value={data.title} error={errors.title} onChange={(e) => set('title', e.target.value)} />
                            <SlugField value={data.slug} source={data.title} error={errors.slug} onChange={(value) => set('slug', value)} />
                            <RichTextEditor label="Nội dung" value={data.content} error={errors.content} uploadUrl={routes.admin.uploadImage} onChange={(value) => set('content', value)} />
                        </Card>
                        <Card title="SEO">
                            <SeoFields data={data} errors={errors} onChange={set} />
                        </Card>
                    </div>
                    <aside>
                        <Card title="Xuất bản">
                            <Select
                                label="Template"
                                name="template"
                                value={data.template}
                                options={TEMPLATES}
                                placeholder={null}
                                error={errors.template}
                                onChange={(e) => set('template', e.target.value)}
                            />
                            <StatusField data={data} errors={errors} onChange={set} withFeatured={false} withSortOrder={false} />
                            <Button type="submit" loading={processing} className="ui-btn--block">{isEdit ? 'Lưu thay đổi' : 'Tạo trang'}</Button>
                        </Card>
                        <Card title="Ảnh đại diện">
                            <ImageField label="Ảnh" name="featured_image" file={data.featured_image} currentUrl={mediaUrl(page?.featured_image)} error={errors.featured_image} onChange={(file) => set('featured_image', file)} />
                        </Card>
                    </aside>
                </div>
            </form>
        </AdminLayout>
    );
}
