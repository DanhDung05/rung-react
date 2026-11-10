import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Pencil, Trash2, Eye } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { mediaUrl, datetime, number } from '../../../lib/format';
import { STATUS } from '../../../lib/constants';

export default function PostsShow({ post }) {
    const { confirm, dialog } = useConfirm();

    const remove = async () => {
        const ok = await confirm({ title: 'Chuyển bài viết vào thùng rác?', message: `Bài viết “${post.title}” sẽ được chuyển vào thùng rác.`, confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.posts.destroy(post.id));
    };

    return (
        <AdminLayout
            title={post.title}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Bài viết', href: routes.admin.posts.index }, { label: 'Chi tiết' }]}
            actions={<><Button href={routes.admin.posts.index} variant="outline">← Danh sách</Button><Button href={routes.admin.posts.edit(post.id)} icon={<Pencil size={15} />}>Sửa</Button><Button variant="danger" icon={<Trash2 size={15} />} onClick={remove}>Xóa</Button></>}
        >
            <div className="admin-form-layout">
                <div>
                    <Card title="Thông tin bài viết">
                        <div className="ui-detail-row"><span>Danh mục</span><div>{post.category?.name || '—'}</div></div>
                        <div className="ui-detail-row"><span>Tác giả</span><div>{post.author?.name || '—'}</div></div>
                        <div className="ui-detail-row"><span>Trạng thái</span><div><Badge tone={STATUS[post.status]?.tone}>{STATUS[post.status]?.label || post.status}</Badge>{post.is_featured && <Badge tone="gold">Nổi bật</Badge>}</div></div>
                        <div className="ui-detail-row"><span>Slug</span><div><code>{post.slug}</code></div></div>
                        <div className="ui-detail-row"><span>Lượt xem</span><div>{number(post.view_count)}</div></div>
                        <div className="ui-detail-row"><span>Ngày đăng</span><div>{datetime(post.published_at)}</div></div>
                        <div className="ui-detail-row"><span>Mô tả ngắn</span><div>{post.excerpt || '—'}</div></div>
                    </Card>
                    <Card title="Nội dung">
                        {post.content ? <div className="ui-prose" dangerouslySetInnerHTML={{ __html: post.content }} /> : <p className="ui-hint">Chưa có nội dung.</p>}
                    </Card>
                </div>
                <aside>
                    <Card title="Ảnh đại diện">
                        {post.featured_image ? <img className="ui-detail-image" src={mediaUrl(post.featured_image)} alt={post.title} /> : <p className="ui-hint">Chưa có ảnh.</p>}
                    </Card>
                    <Card title="Tag">
                        {(post.tags || []).length === 0 ? <p className="ui-hint">Chưa gắn tag.</p> : (
                            <ul className="ui-chip-list">{post.tags.map((tag) => <li key={tag.id}><Link href={routes.admin.tags.show(tag.id)}>{tag.name}</Link></li>)}</ul>
                        )}
                    </Card>
                    <Card title="Liên kết">
                        <Link href={routes.posts.show(post.slug)} target="_blank" className="ui-btn ui-btn--outline ui-btn--block"><Eye size={15} /> Xem trên website</Link>
                    </Card>
                </aside>
            </div>
            {dialog}
        </AdminLayout>
    );
}
