import React from 'react';

export default function Card({ title, description, actions, children, className = '', bodyClassName = '' }) {
    return (
        <section className={`ui-card ${className}`.trim()}>
            {(title || actions) && (
                <header className="ui-card__head">
                    <div>
                        {title && <h2 className="ui-card__title">{title}</h2>}
                        {description && <p className="ui-card__desc">{description}</p>}
                    </div>
                    {actions && <div className="ui-card__actions">{actions}</div>}
                </header>
            )}
            <div className={`ui-card__body ${bodyClassName}`.trim()}>{children}</div>
        </section>
    );
}
