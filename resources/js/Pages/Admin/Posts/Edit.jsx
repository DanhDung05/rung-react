import React from 'react';
import PostForm from './Partials/Form';

export default function PostsEdit({ post, categories = [], tags = [], authors = [] }) {
    return <PostForm post={post} categories={categories} tags={tags} authors={authors} />;
}
