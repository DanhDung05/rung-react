import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Pencil, Trash2, ListTree } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { number } from '../../../lib/format';

/** Render cây item (đã flatten sẵn từ backend theo thứ tự cha → con). */
function renderItems(items) {
    const roots = items.filter((item) => !item.parent_id);
    const childrenOf = (id) => items.filter((item) => item.parent_id === id);

    const branch = (item, depth) => (
        <li key={item.id} style={{ marginLeft: depth * 18 }}>
            <div className="ui-tree-row">
                <strong>{item.label}</strong>
                <code>{item.route_name ? `route:${item.route_name}` : item.url || '#'}</code>
                <span className="ui-sub">{item.target}</span>
                {!item.is_active && <Badge tone="muted">Ẩn</Badge>}
            </div>
            {childrenOf(item.id).length > 0 && <ul>{childrenOf(item.id).map((child) => branch(child, depth + 1))}</ul>}
        </li>
    );

    return roots.map((root) => branch(root, 0));
}

export default function MenusShow({ menu }) {
    const { confirm, dialog } = useConfirm();
    const items = menu.all_items || [];

    const remove = async () => {
        const ok = await confirm({ title: 'Chuyển menu vào thùng rác?', message: `Menu “${menu.name}” sẽ được chuyển vào thùng rác.`, confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.menus.destroy(menu.id));
    };

    return (
        <AdminLayout
            title={menu.name}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Menu', href: routes.admin.menus.index }, { label: 'Chi tiết' }]}
            actions={
                <>
                    <Button href={routes.admin.menus.index} variant="outline">← Danh sách</Button>
                    <Button href={routes.admin.menus.items(menu.id)} icon={<ListTree size={15} />}>Quản lý item</Button>
                    <Button href={routes.admin.menus.edit(menu.id)} icon={<Pencil size={15} />}>Sửa</Button>
                    <Button variant="danger" icon={<Trash2 size={15} />} onClick={remove}>Xóa</Button>
                </>
            }
        >
            <div className="admin-form-layout">
                <div>
                    <Card title={`Cấu trúc menu (${number(items.length)} item)`}>
                        {items.length === 0 ? (
                            <p className="ui-hint">Menu chưa có item. Bấm “Quản lý item” để thêm.</p>
                        ) : (
                            <ul className="ui-tree">{renderItems(items)}</ul>
                        )}
                    </Card>
                </div>
                <aside>
                    <Card title="Thông tin menu">
                        <div className="ui-detail-row"><span>Vị trí</span><div><code>{menu.location}</code></div></div>
                        <div className="ui-detail-row"><span>Hoạt động</span><div><Badge tone={menu.is_active ? 'success' : 'muted'}>{menu.is_active ? 'Có' : 'Không'}</Badge></div></div>
                        <div className="ui-detail-row"><span>Mô tả</span><div>{menu.description || '—'}</div></div>
                    </Card>
                </aside>
            </div>
            {dialog}
        </AdminLayout>
    );
}
