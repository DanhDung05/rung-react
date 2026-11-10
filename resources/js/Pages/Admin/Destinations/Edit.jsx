import React from 'react';
import DestinationForm from './Partials/Form';

export default function DestinationsEdit({ destination, parents = [] }) {
    return <DestinationForm destination={destination} parents={parents} />;
}
