import React from 'react';

/**
 * tone: success | danger | warning | info | muted | gold
 */
export default function Badge({ tone = 'muted', className = '', children }) {
    return <span className={`ui-badge ui-badge--${tone} ${className}`.trim()}>{children}</span>;
}
