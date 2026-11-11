import React from 'react';
import BlogList from '../../Components/Public/BlogList';
import { routes } from '../../lib/routes';

export default function BlogCategory({ category, posts, categories = [], recentPosts = [], popularTags = [] }) {
    return (
        <BlogList
            eyebrow="Chuyên mục"
            title={category?.name || 'Chuyên mục'}
            lead={category?.description}
            breadcrumbs={[
                { label: 'Trang chủ', href: routes.home },
                { label: 'Cẩm nang', href: routes.posts.index },
                { label: category?.name || 'Chuyên mục' },
            ]}
            posts={posts}
            categories={categories}
            recentPosts={recentPosts}
            popularTags={popularTags}
            seoTitle={`${category?.name} — Cẩm nang Rừng Adventure`}
        />
    );
}
