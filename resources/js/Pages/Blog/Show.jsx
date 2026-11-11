import React from 'react';
import { Link } from '@inertiajs/react';
import { ArrowLeft, ArrowRight, CalendarDays, User } from 'lucide-react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import PostCard from '../../Components/Public/PostCard';
import BlogSidebar from '../../Components/Public/BlogSidebar';
import Reveal from '../../Components/Public/Reveal';
import { routes } from '../../lib/routes';
import { date, mediaUrl } from '../../lib/format';
import { placeholderImage } from '../../lib/placeholder';
import { articleSchema, breadcrumbSchema } from '../../lib/schema';

export default function BlogShow({ post, relatedPosts = [], recentPosts = [], popularTags = [] }) {
    const cover = mediaUrl(post.featured_image) || placeholderImage(post.slug);
    const tags = post.tags || [];

    const breadcrumbs = [
        { label: 'Trang chủ', href: routes.home },
        { label: 'Cẩm nang', href: routes.posts.index },
        ...(post.category ? [{ label: post.category.name, href: routes.posts.category(post.category.slug) }] : []),
        { label: post.title },
    ];

    return (
        <AppLayout>
            <Seo
                title={post.meta_title || `${post.title} — Rừng Adventure`}
                description={post.meta_description || post.excerpt}
                image={cover}
                type="article"
                jsonLd={[
                    articleSchema(post),
                    breadcrumbSchema(breadcrumbs.map((crumb) => ({ name: crumb.label, url: crumb.href }))),
                ]}
            />

            <PageHero title={post.title} lead={post.excerpt} image={cover} size="tall" breadcrumbs={breadcrumbs} />

            <section className="rp-section">
                <div className="rp-container rp-blog-layout">
                    <article className="rp-article">
                        <div className="rp-post-meta">
                            {post.category && (
                                <Link href={routes.posts.category(post.category.slug)} className="rp-post-meta__cat">
                                    {post.category.name}
                                </Link>
                            )}
                            {post.published_at && (
                                <span><CalendarDays size={14} /> {date(post.published_at)}</span>
                            )}
                            {post.author?.name && (
                                <span><User size={14} /> {post.author.name}</span>
                            )}
                        </div>

                        <div className="rp-prose" dangerouslySetInnerHTML={{ __html: post.content || `<p>${post.excerpt || ''}</p>` }} />

                        {tags.length > 0 && (
                            <div className="rp-tags">
                                {tags.map((tag) => (
                                    <Link key={tag.id} href={routes.posts.tag(tag.slug)} className="rp-chip">
                                        #{tag.name}
                                    </Link>
                                ))}
                            </div>
                        )}

                        {post.author?.name && (
                            <div className="rp-author">
                                <div className="rp-author__avatar">
                                    {post.author.avatar ? <img src={post.author.avatar} alt={post.author.name} /> : post.author.name.charAt(0)}
                                </div>
                                <div>
                                    <div className="rp-author__role">Tác giả</div>
                                    <strong>{post.author.name}</strong>
                                    <p>Chia sẻ những hành trình và trải nghiệm từ rừng Việt Nam.</p>
                                </div>
                            </div>
                        )}

                        <div className="rp-article__foot">
                            <Link href={routes.posts.index} className="rp-link-arrow">
                                <ArrowLeft size={15} /> Tất cả bài viết
                            </Link>
                        </div>
                    </article>

                    <BlogSidebar categories={post.categories || []} recentPosts={recentPosts} popularTags={popularTags} />
                </div>
            </section>

            {relatedPosts.length > 0 && (
                <section className="rp-section rp-section--cream">
                    <div className="rp-container">
                        <div className="rp-spread" style={{ marginBottom: 30 }}>
                            <h2 className="rp-h2">Bài viết liên quan</h2>
                            <Link href={routes.posts.index} className="rp-link-arrow">Xem thêm <ArrowRight size={15} /></Link>
                        </div>

                        <Reveal>
                            <div className="rp-blog-grid rp-blog-grid--3">
                                {relatedPosts.map((item) => <PostCard key={item.id} post={item} />)}
                            </div>
                        </Reveal>
                    </div>
                </section>
            )}
        </AppLayout>
    );
}
