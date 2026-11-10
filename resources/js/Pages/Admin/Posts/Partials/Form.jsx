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

export default function PostForm({ post = null, categories = [], tags = [], authors = [] }) {
    const isEdit = !!post;

    const { data, setData, post: submitPost, put, processing, errors } = useForm({
        user_id: post?.user_id ?? authors[0]?.id ?? '',
        category_id: post?.category_id ?? '',
        title: post?.title ?? '',
        slug: post?.slug ?? '',
        excerpt: post?.excerpt ?? '',
        content: post?.content ?? '',
        featured_image: null,
        meta_title: post?.meta_title ?? '',
        meta_description: post?.meta_description ?? '',
        is_featured: !!post?.is_featured,
        status: post?.status ?? 'draft',
        published_at: post?.published_at ?? '',
        tag_ids: (post?.tags || []).map((tag) => tag.id),
    });

    const set = (key, value) => setData(key, value);

    const submit = (event) => {
        event.preventDefault();
        if (isEdit) put(routes.admin.posts.update(post.id), { forceFormData: true });
        else submitPost(routes.admin.posts.store, { forceFormData: true });
    };

    return (
        <AdminLayout
            title={isEdit ? `Sửa bài viết: ${post.title}` : 'Thêm bài viết mới'}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Bài viết', href: routes.admin.posts.index }, { label: isEdit ? 'Sửa' : 'Tạo mới' }]}
            actions={<Button href={routes.admin.posts.index} variant="outline">← Danh sách</Button>}
        >
            <form onSubmit={submit} noValidate>
                <div className="admin-form-layout">
                    <div>
                        <Card title="Nội dung bài viết">
                            <Input label="Tiêu đề" name="title" required value={data.title} error={errors.title} onChange={(e) => set('title', e.target.value)} />
                            <SlugField value={data.slug} source={data.title} error={errors.slug} onChange={(value) => set('slug', value)} />
                            <Textarea label="Mô tả ngắn" name="excerpt" rows={3} value={data.excerpt} error={errors.excerpt} onChange={(e) => set('excerpt', e.target.value)} />
                            <RichTextEditor label="Nội dung" value={data.content} error={errors.content} uploadUrl={routes.admin.uploadImage} onChange={(value) => set('content', value)} />
                        </Card>

                        <Card title="SEO">
                            <SeoFields data={data} errors={errors} onChange={set} />
                        </Card>
                    </div>

                    <aside>
                        <Card title="Xuất bản">
                            <Select
                                label="Tác giả"
                                name="user_id"
                                required
                                value={data.user_id}
                                placeholder="— Chọn tác giả —"
                                options={authors.map((author) => ({ value: author.id, label: author.name }))}
                                error={errors.user_id}
                                onChange={(e) => set('user_id', e.target.value)}
                            />
                            <Select
                                label="Danh mục"
                                name="category_id"
                                required
                                value={data.category_id}
                                placeholder="— Chọn danh mục —"
                                options={categories.map((category) => ({ value: category.id, label: category.name }))}
                                error={errors.category_id}
                                onChange={(e) => set('category_id', e.target.value)}
                            />
                            <StatusField data={data} errors={errors} onChange={set} withSortOrder={false} />
                            <Button type="submit" loading={processing} className="ui-btn--block">
                                {isEdit ? 'Lưu thay đổi' : 'Tạo bài viết'}
                            </Button>
                        </Card>

                        <Card title="Ảnh đại diện">
                            <ImageField
                                label="Ảnh chính"
                                name="featured_image"
                                file={data.featured_image}
                                currentUrl={mediaUrl(post?.featured_image)}
                                error={errors.featured_image}
                                onChange={(file) => set('featured_image', file)}
                            />
                        </Card>

                        <Card title="Tag">
                            <div className="ui-field">
                                <label className="ui-label" htmlFor="tag-ids">Chọn tag</label>
                                <select
                                    id="tag-ids"
                                    multiple
                                    className="ui-select"
                                    size={Math.min(8, Math.max(4, tags.length))}
                                    value={data.tag_ids.map(String)}
                                    onChange={(event) => set('tag_ids', Array.from(event.target.selectedOptions).map((option) => option.value))}
                                >
                                    {tags.map((tag) => (
                                        <option key={tag.id} value={tag.id}>{tag.name}</option>
                                    ))}
                                </select>
                                <span className="ui-hint">Giữ Ctrl/Cmd để chọn nhiều.</span>
                            </div>
                        </Card>
                    </aside>
                </div>
            </form>
        </AdminLayout>
    );
}
