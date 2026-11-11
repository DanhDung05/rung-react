import React from 'react';
import BlogList from '../../Components/Public/BlogList';
import { routes } from '../../lib/routes';

export default function BlogTag({ tag, posts, categories = [], recentPosts = [], popularTags = [] }) {
    return (
        <BlogList
            eyebrow="Thẻ"
            title={`#${tag?.name || ''}`}
            lead={`Các bài viết được gắn thẻ ${tag?.name || ''}.`}
            breadcrumbs={[
                { label: 'Trang chủ', href: routes.home },
                { label: 'Cẩm nang', href: routes.posts.index },
                { label: `#${tag?.name || ''}` },
            ]}
            posts={posts}
            categories={categories}
            recentPosts={recentPosts}
            popularTags={popularTags}
            seoTitle={`#${tag?.name} — Cẩm nang Rừng Adventure`}
        />
    );
}
