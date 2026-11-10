import React from 'react';
import TourForm from './Partials/Form';

export default function ToursCreate({ categories = [], destinations = [] }) {
    return <TourForm categories={categories} destinations={destinations} />;
}
