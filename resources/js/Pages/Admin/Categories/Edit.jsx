import React from 'react';
import CategoryForm from './Partials/Form';

export default function CategoriesEdit({ category, parents = [] }) {
    return <CategoryForm category={category} parents={parents} />;
}
