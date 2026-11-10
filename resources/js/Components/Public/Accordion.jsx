import React, { useState } from 'react';
import { Plus } from 'lucide-react';

/**
 * Accordion dùng cho FAQ / lịch trình.
 * items: [{ id?, title, content }]
 */
export default function Accordion({ items = [], defaultOpen = 0, multiple = false }) {
    const [open, setOpen] = useState(() => (defaultOpen === null ? [] : [defaultOpen]));

    const toggle = (index) => {
        if (multiple) {
            setOpen((current) => (current.includes(index) ? current.filter((i) => i !== index) : [...current, index]));
            return;
        }
        setOpen((current) => (current.includes(index) ? [] : [index]));
    };

    return (
        <div className="rp-accordion">
            {items.map((item, index) => {
                const isOpen = open.includes(index);

                return (
                    <div key={item.id ?? index} className={`rp-accordion__item ${isOpen ? 'is-open' : ''}`}>
                        <button type="button" className="rp-accordion__trigger" onClick={() => toggle(index)} aria-expanded={isOpen}>
                            <span>{item.title}</span>
                            <Plus size={18} className="rp-accordion__icon" />
                        </button>
                        {isOpen && <div className="rp-accordion__panel">{item.content}</div>}
                    </div>
                );
            })}
        </div>
    );
}
