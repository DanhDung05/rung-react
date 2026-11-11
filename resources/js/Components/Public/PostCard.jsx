import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import { mediaUrl, date } from '../../lib/format';
import { placeholderImage, resolveImage } from '../../lib/placeholder';
import { routes } from '../../lib/routes';

export default function PostCard({ post }) {
    const [failed, setFailed] = useState(false);
    const image = failed ? placeholderImage(post.slug || post.title) : resolveImage(mediaUrl(post.featured_image), post.slug || post.title);

    return (
        <Link href={routes.posts.show(post.slug)} className="rp-post-card">
            <div className="rp-post-card__media" style={{ backgroundImage: `url("${placeholderImage(post.slug || post.title)}")`, backgroundSize: 'cover' }}>
                <img src={image} alt={post.title} loading="lazy" decoding="async" onError={() => setFailed(true)} />
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
