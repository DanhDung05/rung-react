import React from 'react';
import EmptyState from '../UI/EmptyState';

/**
 * Bảng dữ liệu cấu hình theo cột.
 *
 * columns: [{ key, label, width?, render?(row), className? }]
 */
export default function DataTable({ columns = [], rows = [], empty = 'Chưa có dữ liệu', rowKey = 'id' }) {
    if (!rows || rows.length === 0) {
        return <EmptyState title={empty} />;
    }

    return (
        <div className="ui-table-wrap">
            <table className="ui-table">
                <thead>
                    <tr>
                        {columns.map((column) => (
                            <th
                                key={column.key}
                                style={column.width ? { width: column.width } : undefined}
                                className={column.className || ''}
                            >
                                {column.label}
                            </th>
                        ))}
                    </tr>
                </thead>
                <tbody>
                    {rows.map((row, index) => (
                        <tr key={row[rowKey] ?? index}>
                            {columns.map((column) => (
                                <td key={column.key} className={column.className || ''}>
                                    {column.render ? column.render(row) : (row[column.key] ?? '—')}
                                </td>
                            ))}
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}
