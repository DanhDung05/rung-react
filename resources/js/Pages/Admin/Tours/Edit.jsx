import React from 'react';
import TourForm from './Partials/Form';

export default function ToursEdit({ tour, categories = [], destinations = [] }) {
    return <TourForm tour={tour} categories={categories} destinations={destinations} />;
}
