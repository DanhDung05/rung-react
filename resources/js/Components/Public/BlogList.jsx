import React from 'react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import PostCard from '../../Components/Public/PostCard';
import Pager from '../../Components/Public/Pager';
import BlogSidebar from '../../Components/Public/BlogSidebar';
import Reveal from '../../Components/Public/Reveal';

export default function BlogList({
    eyebrow = 'Cẩm nang',
    title,
    lead = null,
    breadcrumbs = [],
    posts,
    categories = [],
    recentPosts = [],
    popularTags = [],
    toolbar = null,
    seoTitle = null,
    seoDescription = null,
}) {
    const list = posts?.data || [];

    return (
        <AppLayout>
            <Seo title={seoTitle || `${title} — Rừng Adventure`} description={seoDescription || lead} />

            <PageHero heroKey="blog" eyebrow={eyebrow} title={title} lead={lead} breadcrumbs={breadcrumbs} />

            <section className="rp-section">
                <div className="rp-container rp-blog-layout">
                    <div>
                        {toolbar}

                        {list.length === 0 ? (
                            <div className="rp-empty">
                                <h3>Chưa có bài viết nào</h3>
                                <p>Nội dung đang được cập nhật.</p>
                            </div>
                        ) : (
                            <Reveal>
                                <div className="rp-blog-grid">
                                    {list.map((post) => <PostCard key={post.id} post={post} />)}
                                </div>
                            </Reveal>
                        )}

                        <Pager links={posts?.links || []} />
                    </div>

                    <BlogSidebar categories={categories} recentPosts={recentPosts} popularTags={popularTags} />
                </div>
            </section>
        </AppLayout>
    );
}
