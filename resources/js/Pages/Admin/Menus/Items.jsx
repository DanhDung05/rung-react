import React, { useMemo, useState } from 'react';
import { router } from '@inertiajs/react';
import { Plus, Trash2, ArrowUp, ArrowDown, Save, CornerDownRight } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Input from '../../../Components/UI/Input';
import Select from '../../../Components/UI/Select';
import Checkbox from '../../../Components/UI/Checkbox';
import Badge from '../../../Components/UI/Badge';
import { routes } from '../../../lib/routes';

let sequence = 0;
const nextKey = () => `new-${++sequence}`;

export default function MenusItems({ menu }) {
    const [items, setItems] = useState(() =>
        (menu.all_items || []).map((item) => ({
            key: `id-${item.id}`,
            label: item.label ?? '',
            url: item.url ?? '',
            route_name: item.route_name ?? '',
            target: item.target ?? '_self',
            is_active: !!item.is_active,
            parent_key: item.parent_id ? `id-${item.parent_id}` : '',
        }))
    );
    const [processing, setProcessing] = useState(false);

    const options = useMemo(
        () => items.map((item) => ({ value: item.key, label: item.label || '(Chưa đặt tên)' })),
        [items]
    );

    const update = (key, patch) => setItems((current) => current.map((item) => (item.key === key ? { ...item, ...patch } : item)));

    const add = () =>
        setItems((current) => [
            ...current,
            { key: nextKey(), label: '', url: '', route_name: '', target: '_self', is_active: true, parent_key: '' },
        ]);

    const remove = (key) =>
        setItems((current) =>
            current
                .filter((item) => item.key !== key)
                .map((item) => (item.parent_key === key ? { ...item, parent_key: '' } : item))
        );

    const move = (index, direction) => {
        const target = index + direction;
        if (target < 0 || target >= items.length) return;
        const next = [...items];
        [next[index], next[target]] = [next[target], next[index]];
        setItems(next);
    };

    const save = () => {
        const indexByKey = new Map(items.map((item, index) => [item.key, index]));

        const payload = items.map((item) => ({
            label: item.label,
            url: item.url || null,
            route_name: item.route_name || null,
            target: item.target || '_self',
            is_active: item.is_active ? 1 : 0,
            parent_key: item.parent_key ? indexByKey.get(item.parent_key) ?? null : null,
        }));

        setProcessing(true);
        router.put(routes.admin.menus.itemsSave(menu.id), { items: payload }, {
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

    return (
        <AdminLayout
            title={`Cấu trúc menu: ${menu.name}`}
            description="Thêm, sửa, sắp xếp và phân cấp các mục menu."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Menu', href: routes.admin.menus.index }, { label: menu.name, href: routes.admin.menus.show(menu.id) }, { label: 'Items' }]}
            actions={
                <>
                    <Button href={routes.admin.menus.index} variant="outline">← Danh sách</Button>
                    <Button type="button" variant="outline" icon={<Plus size={15} />} onClick={add}>Thêm item</Button>
                    <Button type="button" icon={<Save size={15} />} loading={processing} onClick={save}>Lưu cấu trúc</Button>
                </>
            }
        >
            <Card title={`${items.length} mục`} description="Kéo thứ tự bằng nút mũi tên; chọn “Mục cha” để tạo menu nhiều cấp.">
                {items.length === 0 && <p className="ui-hint">Chưa có item nào. Bấm “Thêm item”.</p>}

                {items.map((item, index) => (
                    <div className="ui-repeater" key={item.key}>
                        <div className="ui-repeater__head">
                            <strong>
                                #{index + 1} {item.parent_key && <Badge tone="info"><CornerDownRight size={11} /> Cấp con</Badge>}
                            </strong>
                            <div className="ui-repeater__actions">
                                <button type="button" className="ui-icon-btn" onClick={() => move(index, -1)} aria-label="Lên"><ArrowUp size={14} /></button>
                                <button type="button" className="ui-icon-btn" onClick={() => move(index, 1)} aria-label="Xuống"><ArrowDown size={14} /></button>
                                <button type="button" className="ui-icon-btn ui-icon-btn--danger" onClick={() => remove(item.key)} aria-label="Xóa"><Trash2 size={14} /></button>
                            </div>
                        </div>

                        <div className="ui-form-grid ui-form-grid--3">
                            <Input label="Tên hiển thị" value={item.label} onChange={(e) => update(item.key, { label: e.target.value })} />
                            <Input label="URL" value={item.url} hint="Dùng khi không có route name" onChange={(e) => update(item.key, { url: e.target.value })} />
                            <Input label="Route name" value={item.route_name} hint="Ví dụ: tours.index" onChange={(e) => update(item.key, { route_name: e.target.value })} />
                            <Select
                                label="Mục cha"
                                value={item.parent_key}
                                placeholder="— Cấp gốc —"
                                options={options.filter((option) => option.value !== item.key)}
                                onChange={(e) => update(item.key, { parent_key: e.target.value })}
                            />
                            <Select
                                label="Cách mở"
                                value={item.target}
                                placeholder={null}
                                options={[{ value: '_self', label: 'Cùng tab' }, { value: '_blank', label: 'Tab mới' }]}
                                onChange={(e) => update(item.key, { target: e.target.value })}
                            />
                            <div className="ui-form-grid__checkbox">
                                <Checkbox label="Hiển thị" checked={item.is_active} onChange={(e) => update(item.key, { is_active: e.target.checked })} />
                            </div>
                        </div>
                    </div>
                ))}
            </Card>
        </AdminLayout>
    );
}
