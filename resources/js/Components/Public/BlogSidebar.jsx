import React from 'react';
import { Link } from '@inertiajs/react';
import { routes } from '../../lib/routes';
import { date } from '../../lib/format';

export default function BlogSidebar({ categories = [], recentPosts = [], popularTags = [] }) {
    return (
        <aside className="rp-sidebar">
            {categories.length > 0 && (
                <div className="rp-widget">
                    <h3>Chuyên mục</h3>
                    <div className="rp-widget__list">
                        {categories.map((category) => (
                            <Link key={category.id} href={routes.posts.category(category.slug)}>
                                <span>{category.name}</span>
                                {category.posts_count !== undefined && <em>{category.posts_count}</em>}
                            </Link>
                        ))}
                    </div>
                </div>
            )}

            {recentPosts.length > 0 && (
                <div className="rp-widget">
                    <h3>Bài viết mới</h3>
                    <div className="rp-widget__list rp-widget__list--posts">
                        {recentPosts.slice(0, 5).map((post) => (
                            <Link key={post.id} href={routes.posts.show(post.slug)}>
                                <span>
                                    <strong>{post.title}</strong>
                                    {post.published_at && <small>{date(post.published_at)}</small>}
                                </span>
                            </Link>
                        ))}
                    </div>
                </div>
            )}

            {popularTags.length > 0 && (
                <div className="rp-widget">
                    <h3>Thẻ phổ biến</h3>
                    <div className="rp-widget__tags">
                        {popularTags.map((tag) => (
                            <Link key={tag.id} href={routes.posts.tag(tag.slug)} className="rp-chip">
                                {tag.name}
                            </Link>
                        ))}
                    </div>
                </div>
            )}
        </aside>
    );
}
