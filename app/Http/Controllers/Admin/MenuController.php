<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveMenuItemsRequest;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Http\Requests\Admin\UpdateMenuRequest;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Danh sách menus.
     */
    public function index(Request $request)
    {
        $query = Menu::query()->withCount('allItems');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $menus = $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Menus/Index', compact('menus'));
    }

    /**
     * Form tạo mới.
     */
    public function create()
    {
        return Inertia::render('Admin/Menus/Create');
    }

    /**
     * Lưu menu mới.
     */
    public function store(StoreMenuRequest $request)
    {
        $data = $request->validated();

        Menu::create($data);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Đã thêm menu thành công.');
    }

    /**
     * Chi tiết menu.
     */
    public function show(Menu $menu)
    {
        $menu->load(['items.children']);
        $menu->loadCount('allItems');

        return Inertia::render('Admin/Menus/Show', compact('menu'));
    }

    /**
     * Form chỉnh sửa.
     */
    public function edit(Menu $menu)
    {
        return Inertia::render('Admin/Menus/Edit', compact('menu'));
    }

    /**
     * Cập nhật menu.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $data = $request->validated();

        $menu->update($data);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Đã cập nhật menu thành công.');
    }

    /**
     * XÓA MỀM.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return back()->with('success', 'Đã chuyển menu vào thùng rác.');
    }

    /**
     * TRANG QUẢN LÝ ITEMS — form động, đệ quy không giới hạn cấp.
     */
    public function items(Menu $menu)
    {
        // Load TẤT CẢ items (không phân biệt cấp)
        $menu->load(['allItems' => function ($q) {
            $q->orderBy('sort_order');
        }]);

        // Nhóm theo parent_id (0 = root)
        $byParent = [];
        foreach ($menu->allItems as $item) {
            $parentId = $item->parent_id ?? 0;
            $byParent[$parentId][] = $item;
        }

        // Đệ quy flatten: cha → con → cháu → ...
        $flatten = function ($parentId) use (&$flatten, &$byParent) {
            $result = [];
            if (!isset($byParent[$parentId])) return $result;

            foreach ($byParent[$parentId] as $item) {
                $result[] = $item;
                $result = array_merge($result, $flatten($item->id));
            }
            return $result;
        };

        $ordered = $flatten(0);

        // Gán lại vào $menu->allItems để Blade dùng
        $menu->setRelation('allItems', collect($ordered));

        return Inertia::render('Admin/Menus/Items', compact('menu'));
    }

    /**
     * LƯU CẤU TRÚC ITEMS.
     */
    public function saveItems(SaveMenuItemsRequest $request, Menu $menu)
    {
        $items = $request->validated()['items'] ?? [];

        // Sắp xếp: item cấp 1 lên trước, item con sau.
        // uasort() giữ nguyên key của mảng → parent_key trỏ đúng index gốc.
        uasort($items, function ($a, $b) {
            $aIsChild = $a['parent_key'] !== null;
            $bIsChild = $b['parent_key'] !== null;
            if ($aIsChild === $bIsChild) return 0;
            return $aIsChild ? 1 : -1;
        });

        DB::transaction(function () use ($menu, $items) {
            // Xóa hết items cũ
            $menu->allItems()->forceDelete();

            // Map index gốc → id đã tạo
            $createdIds = [];

            foreach ($items as $index => $item) {
                $parentId = null;

                if ($item['parent_key'] !== null) {
                    $parentId = $createdIds[$item['parent_key']] ?? null;
                }

                $created = $menu->allItems()->create([
                    'parent_id'  => $parentId,
                    'label'      => $item['label'],
                    'url'        => $item['url'] ?: null,
                    'route_name' => $item['route_name'] ?: null,
                    'target'     => $item['target'] ?: '_self',
                    'sort_order' => $index,
                    'is_active'  => $item['is_active'],
                ]);

                $createdIds[$index] = $created->id;
            }
        });

        return redirect()
            ->route('admin.menus.items', $menu)
            ->with('success', 'Đã lưu cấu trúc menu thành công.');
    }

    /**
     * THÙNG RÁC.
     */
    public function trash(Request $request)
    {
        $query = Menu::onlyTrashed();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $menus = $query
            ->orderByDesc('deleted_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Menus/Trash', compact('menus'));
    }

    /**
     * KHÔI PHỤC.
     */
    public function restore(int $id)
    {
        $menu = Menu::onlyTrashed()->findOrFail($id);
        $menu->restore();

        return back()->with('success', 'Đã khôi phục menu "' . $menu->name . '".');
    }

    /**
     * XÓA CỨNG — chặn nếu có items.
     */
    public function forceDestroy(int $id)
    {
        $menu = Menu::onlyTrashed()->findOrFail($id);

        $itemsCount = $menu->allItems()->count();

        if ($itemsCount > 0) {
            return back()->with('error',
                'Không thể xóa vĩnh viễn menu "' . $menu->name . '". '
                . 'Đang có ' . $itemsCount . ' item trong menu. '
                . 'Vui lòng xóa hết items trước.'
            );
        }

        $name = $menu->name;
        $menu->forceDelete();

        return back()->with('success', 'Đã xóa vĩnh viễn menu "' . $name . '".');
    }
}