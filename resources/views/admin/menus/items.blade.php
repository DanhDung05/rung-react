@extends('layouts.admin')

@section('content')

@php
    /**
     * Sắp xếp items thành cấu trúc cây (cha trước, con ngay sau cha, cháu ngay sau con).
     * Trả về mảng phẳng với key = index gốc cần thiết cho JS.
     */
    $allItems = $menu->allItems->values();

    // Nhóm theo parent_id
    $byParent = [];
    foreach ($allItems as $index => $item) {
        $parentId = $item->parent_id ?? 0;
        $byParent[$parentId][] = ['index' => $index, 'item' => $item];
    }

    // Đệ quy flatten
    $flatten = function ($parentId, $depth) use (&$flatten, &$byParent) {
        $result = [];
        if (!isset($byParent[$parentId])) return $result;

        foreach ($byParent[$parentId] as $entry) {
            $result[] = [
                'index' => $entry['index'],
                'item'  => $entry['item'],
                'depth' => $depth,
            ];
            $result = array_merge($result, $flatten($entry['item']->id, $depth + 1));
        }
        return $result;
    };

    $ordered = $flatten(0, 1);

    // Chuẩn bị dữ liệu cho JS
    $itemsData = [];
    foreach ($ordered as $entry) {
        $item = $entry['item'];

        // Tìm index gốc của cha trong $allItems
        $parentOriginalIndex = null;
        if ($item->parent_id) {
            $parentOriginalIndex = $allItems->search(fn($i) => $i->id === $item->parent_id);
            if ($parentOriginalIndex === false) $parentOriginalIndex = null;
        }

        $itemsData[] = [
            'label'        => $item->label,
            'url'          => $item->url,
            'route_name'   => $item->route_name,
            'target'       => $item->target,
            'is_active'    => (bool) $item->is_active,
            'parent_key'   => $parentOriginalIndex,
            'original_idx' => $entry['index'],
            'depth'        => $entry['depth'],
        ];
    }

    if (empty($itemsData)) {
        $itemsData = [
            ['label' => '', 'url' => '', 'route_name' => '', 'target' => '_self', 'is_active' => true, 'parent_key' => null, 'original_idx' => 0, 'depth' => 1],
        ];
    }
@endphp

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Items của menu</h2>
        <p class="text-sm text-text-muted mt-1">
            Menu: <span class="font-medium">{{ $menu->name }}</span> ({{ $menu->location }})
        </p>
    </div>

    <x-button href="{{ route('admin.menus.index') }}" variant="outline">← Quay lại</x-button>
</div>

@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
@endif

<form action="{{ route('admin.menus.items.save', $menu) }}" method="POST" id="menu-items-form">
    @csrf
    @method('PUT')

    <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-heading text-lg font-semibold">Danh sách item</h3>
            <div class="flex items-center gap-2">
                <button type="button" id="btn-expand-all"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium
                               text-text-muted bg-bg-subtle hover:bg-bg-subtle/70 transition-base">
                    Mở tất cả
                </button>
                <button type="button" id="btn-collapse-all"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium
                               text-text-muted bg-bg-subtle hover:bg-bg-subtle/70 transition-base">
                    Thu gọn tất cả
                </button>
                <button type="button" id="btn-add-item"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium
                               text-primary bg-primary-light hover:bg-primary hover:text-text-inverse transition-base">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Thêm item cấp 1
                </button>
            </div>
        </div>

        <p class="text-sm text-text-muted mb-4">
            <strong>Hướng dẫn:</strong> Bấm <strong>▼</strong> để mở rộng và chỉnh sửa thông tin. Bấm <strong>+ Con</strong> để thêm menu con. Menu hỗ trợ tối đa 3 cấp.
        </p>

        <div id="items-container" class="space-y-2">
            {{-- Items sẽ được render bằng JS --}}
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-border">
        <x-button href="{{ route('admin.menus.index') }}" variant="outline">Hủy</x-button>
        <x-button type="submit" variant="primary">Lưu cấu trúc menu</x-button>
    </div>
</form>

{{-- Template cho 1 item --}}
<template id="item-template">
    <div class="item-row border border-border rounded-lg bg-bg" data-index="__INDEX__">

        {{-- Header row — luôn hiển thị --}}
        <div class="item-header flex items-center justify-between gap-3 p-3 cursor-pointer hover:bg-bg-subtle/50 transition-base">
            <div class="flex items-center gap-2 flex-wrap min-w-0 flex-1">
                <button type="button" class="btn-toggle text-text-muted hover:text-primary transition-base flex-shrink-0">
                    <svg class="w-4 h-4 transform transition-transform toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <span class="item-depth-badge inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold flex-shrink-0"></span>

                <span class="item-title font-medium text-sm truncate">
                    <span class="item-label-display">(Chưa có tên)</span>
                </span>

                <span class="item-parent-label text-xs text-text-muted truncate"></span>

                <span class="item-inactive-badge hidden inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 flex-shrink-0">
                    Ẩn
                </span>
            </div>

            <div class="flex items-center gap-1 flex-shrink-0">
                <button type="button"
                        class="btn-add-child text-primary hover:bg-primary/10 px-2 py-1 rounded text-xs font-medium transition-base inline-flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Con
                </button>
                <button type="button"
                        class="btn-remove text-danger hover:bg-danger/10 p-1.5 rounded transition-base"
                        title="Xóa item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Body — chỉ hiện khi mở rộng --}}
        <div class="item-body hidden border-t border-border p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-text-muted mb-1">Tên hiển thị *</label>
                    <input type="text"
                           name="items[__INDEX__][label]"
                           class="field-label block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-1 focus:ring-primary"
                           placeholder="Trang chủ">
                </div>

                <div>
                    <label class="block text-xs font-medium text-text-muted mb-1">Cách mở link</label>
                    <select name="items[__INDEX__][target]"
                            class="field-target block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                                   focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="_self">Cùng tab</option>
                        <option value="_blank">Tab mới</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-text-muted mb-1">URL (nếu có)</label>
                    <input type="text"
                           name="items[__INDEX__][url]"
                           class="field-url block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-1 focus:ring-primary"
                           placeholder="/tours">
                </div>

                <div>
                    <label class="block text-xs font-medium text-text-muted mb-1">Route name (nếu có)</label>
                    <input type="text"
                           name="items[__INDEX__][route_name]"
                           class="field-route_name block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-1 focus:ring-primary"
                           placeholder="tours.index">
                </div>

                <div>
                    <label class="block text-xs font-medium text-text-muted mb-1">Item cha</label>
                    <select name="items[__INDEX__][parent_key]"
                            class="field-parent_key block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                                   focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="">— Không có (cấp 1) —</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox"
                               name="items[__INDEX__][is_active]"
                               value="1"
                               class="field-is_active rounded border-border text-primary focus:ring-primary">
                        <span class="text-sm">Hiển thị</span>
                    </label>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
(function() {
    'use strict';

    const initialItems = @json($itemsData);

    const container      = document.getElementById('items-container');
    const template       = document.getElementById('item-template');
    const addBtn         = document.getElementById('btn-add-item');
    const expandAllBtn   = document.getElementById('btn-expand-all');
    const collapseAllBtn = document.getElementById('btn-collapse-all');

    let items = initialItems.map(i => ({ ...i, expanded: false }));

    /* ============================================================
       TIỆN ÍCH
       ============================================================ */

    function getDepth(index, guard) {
        guard = guard || 0;
        if (guard > 10) return 1;
        const item = items[index];
        if (!item || item.parent_key === null || item.parent_key === undefined) return 1;
        return 1 + getDepth(item.parent_key, guard + 1);
    }

    function getParentLabel(index) {
        const item = items[index];
        if (!item || item.parent_key === null || item.parent_key === undefined) return '';
        const parent = items[item.parent_key];
        return parent ? (parent.label || `Item ${item.parent_key + 1}`) : '';
    }

    function isDescendantOf(candidateIndex, ancestorIndex) {
        let current = candidateIndex;
        let guard = 0;
        while (current !== null && current !== undefined && guard < 20) {
            if (current === ancestorIndex) return true;
            current = items[current]?.parent_key ?? null;
            guard++;
        }
        return false;
    }

    /* ============================================================
       RENDER
       ============================================================ */

    function render() {
        container.innerHTML = '';

        items.forEach((item, index) => {
            const depth = getDepth(index);

            const html = template.innerHTML.replace(/__INDEX__/g, index);
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html;
            const row = wrapper.firstElementChild;

            // Thụt lề
            row.style.marginLeft = ((depth - 1) * 32) + 'px';

            // Badge cấp
            const badge = row.querySelector('.item-depth-badge');
            badge.textContent = 'Cấp ' + depth;
            if (depth === 1) {
                badge.classList.add('bg-primary-light', 'text-primary');
            } else if (depth === 2) {
                badge.classList.add('bg-warning-light', 'text-warning-dark');
            } else {
                badge.classList.add('bg-danger-light', 'text-danger-dark');
            }

            // Label hiển thị (trên header)
            const labelDisplay = row.querySelector('.item-label-display');
            labelDisplay.textContent = item.label || '(Chưa có tên)';

            // Nhãn cha
            const parentLabel = row.querySelector('.item-parent-label');
            if (item.parent_key !== null && item.parent_key !== undefined) {
                parentLabel.textContent = '└─ con của: ' + getParentLabel(index);
            }

            // Badge "Ẩn"
            const inactiveBadge = row.querySelector('.item-inactive-badge');
            if (!item.is_active) {
                inactiveBadge.classList.remove('hidden');
            }

            // Điền giá trị form
            row.querySelector('.field-label').value = item.label || '';
            row.querySelector('.field-target').value = item.target || '_self';
            row.querySelector('.field-url').value = item.url || '';
            row.querySelector('.field-route_name').value = item.route_name || '';
            row.querySelector('.field-is_active').checked = !!item.is_active;

            // Trạng thái mở/đóng
            const body = row.querySelector('.item-body');
            const toggleIcon = row.querySelector('.toggle-icon');
            if (item.expanded) {
                body.classList.remove('hidden');
                toggleIcon.style.transform = 'rotate(90deg)';
            }

            // Bind sự kiện
            bindFieldEvents(row, index);

            // Nút toggle — bấm cả header
            const toggleHandler = () => {
                items[index].expanded = !items[index].expanded;
                render();
            };
            row.querySelector('.btn-toggle').addEventListener('click', (e) => {
                e.stopPropagation();
                toggleHandler();
            });
            row.querySelector('.item-header').addEventListener('click', (e) => {
                // Không toggle khi bấm nút Con/Xóa
                if (e.target.closest('.btn-add-child') || e.target.closest('.btn-remove')) return;
                if (e.target.closest('.btn-toggle')) return; // đã handle riêng
                toggleHandler();
            });

            // Nút xóa
            row.querySelector('.btn-remove').addEventListener('click', (e) => {
                e.stopPropagation();
                removeItem(index);
            });

            // Nút thêm con
            const addChildBtn = row.querySelector('.btn-add-child');
            if (depth >= 3) {
                addChildBtn.style.display = 'none';
            } else {
                addChildBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    addChild(index);
                });
            }

            container.appendChild(row);
        });

        updateParentDropdowns();
    }

    function bindFieldEvents(row, index) {
        row.querySelector('.field-label').addEventListener('input', (e) => {
            items[index].label = e.target.value;
            // Cập nhật luôn label hiển thị trên header
            row.querySelector('.item-label-display').textContent = e.target.value || '(Chưa có tên)';
            updateParentDropdowns();
        });

        row.querySelector('.field-target').addEventListener('change', (e) => {
            items[index].target = e.target.value;
        });

        row.querySelector('.field-url').addEventListener('input', (e) => {
            items[index].url = e.target.value;
        });

        row.querySelector('.field-route_name').addEventListener('input', (e) => {
            items[index].route_name = e.target.value;
        });

        row.querySelector('.field-parent_key').addEventListener('change', (e) => {
            const val = e.target.value;
            items[index].parent_key = val === '' ? null : parseInt(val, 10);
            // Giữ trạng thái expanded
            render();
        });

        row.querySelector('.field-is_active').addEventListener('change', (e) => {
            items[index].is_active = e.target.checked;
            const inactiveBadge = row.querySelector('.item-inactive-badge');
            if (e.target.checked) {
                inactiveBadge.classList.add('hidden');
            } else {
                inactiveBadge.classList.remove('hidden');
            }
        });
    }

    /* ============================================================
       DROPDOWN PARENT
       ============================================================ */

    function updateParentDropdowns() {
        document.querySelectorAll('.item-row').forEach((row, index) => {
            const parentSelect = row.querySelector('.field-parent_key');
            if (!parentSelect) return;

            const currentItem  = items[index];
            const currentDepth = getDepth(index);

            parentSelect.innerHTML = '<option value="">— Không có (cấp 1) —</option>';

            items.forEach((item, pIndex) => {
                if (pIndex === index) return;
                if (isDescendantOf(pIndex, index)) return;
                if (getDepth(pIndex) >= currentDepth) return;

                const option = document.createElement('option');
                option.value = String(pIndex);
                const indent = '— '.repeat(getDepth(pIndex) - 1);
                option.textContent = indent + (item.label || `Item ${pIndex + 1}`);
                parentSelect.appendChild(option);
            });

            if (currentItem.parent_key !== null && currentItem.parent_key !== undefined) {
                parentSelect.value = String(currentItem.parent_key);
            } else {
                parentSelect.value = '';
            }
        });
    }

    /* ============================================================
       THÊM / XÓA
       ============================================================ */

    function addItem() {
        items.push({
            label: '',
            url: '',
            route_name: '',
            target: '_self',
            is_active: true,
            parent_key: null,
            expanded: true,  // item mới tự mở rộng để nhập liệu ngay
        });
        render();
    }

    function addChild(parentIndex) {
        const newItem = {
            label: '',
            url: '',
            route_name: '',
            target: '_self',
            is_active: true,
            parent_key: parentIndex,
            expanded: true,
        };

        items.splice(parentIndex + 1, 0, newItem);

        items.forEach((item, i) => {
            if (i === parentIndex + 1) return;
            if (item.parent_key !== null && item.parent_key > parentIndex) {
                item.parent_key += 1;
            }
        });

        render();
    }

    function removeItem(index) {
        if (items.length <= 1) {
            alert('Phải có ít nhất 1 item.');
            return;
        }

        if (!confirm('Xóa item này? Các item con (nếu có) sẽ được chuyển thành cấp 1.')) {
            return;
        }

        items.forEach((item) => {
            if (item.parent_key === index) {
                item.parent_key = null;
            } else if (item.parent_key !== null && item.parent_key > index) {
                item.parent_key -= 1;
            }
        });

        items.splice(index, 1);
        render();
    }

    /* ============================================================
       MỞ / THU GỌN TẤT CẢ
       ============================================================ */

    expandAllBtn.addEventListener('click', () => {
        items.forEach(item => item.expanded = true);
        render();
    });

    collapseAllBtn.addEventListener('click', () => {
        items.forEach(item => item.expanded = false);
        render();
    });

    /* ============================================================
       KHỞI TẠO
       ============================================================ */

    addBtn.addEventListener('click', addItem);
    render();
})();
</script>

@endsection