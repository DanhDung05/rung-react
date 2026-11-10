import React from 'react';

export default function EmptyState({ icon = null, title = 'Chưa có dữ liệu', description = null, action = null }) {
    return (
        <div className="ui-empty">
            {icon && <div className="ui-empty__icon">{icon}</div>}
            <h3>{title}</h3>
            {description && <p>{description}</p>}
            {action && <div className="ui-empty__action">{action}</div>}
        </div>
    );
}
