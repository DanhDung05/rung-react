import React from 'react';
import { Link } from '@inertiajs/react';
import { mediaUrl, date } from '../../lib/format';
import { routes } from '../../lib/routes';

export default function PostCard({ post }) {
    const image = mediaUrl(post.featured_image);

    return (
        <Link href={routes.posts.show(post.slug)} className="rp-post-card">
            <div className="rp-post-card__media">
                {image && <img src={image} alt={post.title} loading="lazy" decoding="async" />}
            </div>

            <div className="rp-post-card__meta">
                {post.category?.name && <span>{post.category.name}</span>}
                {post.published_at && <span>{date(post.published_at)}</span>}
            </div>

            <h3>{post.title}</h3>
            {post.excerpt && <p>{post.excerpt}</p>}
        </Link>
    );
}
