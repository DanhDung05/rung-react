import React, { useState } from 'react';
import { router } from '@inertiajs/react';
import { Search, X } from 'lucide-react';

/**
 * Thanh tìm kiếm + lọc, giữ query string trên URL.
 *
 * filters: [{ name, label, options: [{ value, label }] }]
 */
export default function FilterBar({ url, filters = [], values = {}, searchPlaceholder = 'Tìm kiếm…' }) {
    const [state, setState] = useState({
        keyword: values.keyword || '',
        ...Object.fromEntries(filters.map((filter) => [filter.name, values[filter.name] || ''])),
    });

    const submit = (event) => {
        event.preventDefault();

        const query = Object.fromEntries(
            Object.entries(state).filter(([, value]) => value !== '' && value !== null && value !== undefined)
        );

        router.get(url, query, { preserveState: true, preserveScroll: true, replace: true });
    };

    const reset = () => {
        setState({ keyword: '', ...Object.fromEntries(filters.map((filter) => [filter.name, ''])) });
        router.get(url, {}, { preserveState: true, preserveScroll: true, replace: true });
    };

    const hasValue = Object.values(state).some((value) => value !== '');

    return (
        <form className="ui-filter" onSubmit={submit}>
            <div className="ui-filter__search">
                <Search size={16} />
                <input
                    type="search"
                    value={state.keyword}
                    onChange={(event) => setState({ ...state, keyword: event.target.value })}
                    placeholder={searchPlaceholder}
                    aria-label={searchPlaceholder}
                />
            </div>

            {filters.map((filter) => (
                <select
                    key={filter.name}
                    className="ui-select ui-filter__select"
                    value={state[filter.name]}
                    onChange={(event) => setState({ ...state, [filter.name]: event.target.value })}
                    aria-label={filter.label}
                >
                    <option value="">{filter.label}</option>
                    {filter.options.map((option) => (
                        <option key={String(option.value)} value={option.value}>
                            {option.label}
                        </option>
                    ))}
                </select>
            ))}

            <button type="submit" className="ui-btn ui-btn--dark ui-btn--sm">
                Lọc
            </button>

            {hasValue && (
                <button type="button" className="ui-btn ui-btn--ghost ui-btn--sm" onClick={reset}>
                    <X size={14} /> Xóa lọc
                </button>
            )}
        </form>
    );
}
