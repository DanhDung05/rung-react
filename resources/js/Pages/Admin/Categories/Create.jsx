import React from 'react';
import CategoryForm from './Partials/Form';

export default function CategoriesCreate({ parents = [] }) {
    return <CategoryForm parents={parents} />;
}
