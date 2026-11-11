import React, { useState } from 'react';
import { router } from '@inertiajs/react';
import { Search } from 'lucide-react';
import BlogList from '../../Components/Public/BlogList';
import { routes } from '../../lib/routes';

const initialFilters = () => {
    if (typeof window === 'undefined') return { q: '', category: '', sort: 'newest' };
    const params = new URLSearchParams(window.location.search);
    return {
        q: params.get('q') || '',
        category: params.get('category') || '',
        sort: params.get('sort') || 'newest',
    };
};

export default function BlogIndex({ posts, categories = [], recentPosts = [], popularTags = [] }) {
    const [filters, setFilters] = useState(initialFilters);

    const apply = (patch) => {
        const next = { ...filters, ...patch };
        setFilters(next);
        router.get(routes.posts.index, Object.fromEntries(Object.entries(next).filter(([, value]) => value !== '')), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    };

    const toolbar = (
        <div className="rp-filterbar rp-filterbar--3" style={{ marginBottom: 28 }}>
            <label className="rp-field">
                <span>Tìm bài viết</span>
                <div className="rp-field__input">
                    <Search size={16} />
                    <input
                        type="search"
                        value={filters.q}
                        placeholder="Tiêu đề, mô tả..."
                        onChange={(event) => setFilters({ ...filters, q: event.target.value })}
                        onKeyDown={(event) => event.key === 'Enter' && apply({})}
                    />
                </div>
            </label>

            <label className="rp-field">
                <span>Chuyên mục</span>
                <select value={filters.category} onChange={(event) => apply({ category: event.target.value })}>
                    <option value="">Tất cả</option>
                    {categories.map((category) => (
                        <option key={category.id} value={category.slug}>{category.name}</option>
                    ))}
                </select>
            </label>

            <label className="rp-field">
                <span>Sắp xếp</span>
                <select value={filters.sort} onChange={(event) => apply({ sort: event.target.value })}>
                    <option value="newest">Mới nhất</option>
                    <option value="oldest">Cũ nhất</option>
                    <option value="popular">Xem nhiều</option>
                </select>
            </label>
        </div>
    );

    return (
        <BlogList
            eyebrow="Cẩm nang"
            title="Ghi chép từ rừng"
            lead="Câu chuyện, kinh nghiệm và kiến thức cho những ai muốn đi chậm và hiểu sâu."
            breadcrumbs={[{ label: 'Trang chủ', href: routes.home }, { label: 'Cẩm nang' }]}
            posts={posts}
            categories={categories}
            recentPosts={recentPosts}
            popularTags={popularTags}
            toolbar={toolbar}
        />
    );
}
