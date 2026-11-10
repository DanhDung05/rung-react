import React from 'react';
import DestinationForm from './Partials/Form';

export default function DestinationsCreate({ parents = [] }) {
    return <DestinationForm parents={parents} />;
}
