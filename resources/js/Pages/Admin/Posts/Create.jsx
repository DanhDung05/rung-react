import React from 'react';
import PostForm from './Partials/Form';

export default function PostsCreate({ categories = [], tags = [], authors = [] }) {
    return <PostForm categories={categories} tags={tags} authors={authors} />;
}
