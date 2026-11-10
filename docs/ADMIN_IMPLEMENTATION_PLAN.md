# RUNG ADVENTURE — KẾ HOẠCH HOÀN THIỆN ADMIN BẰNG REACT

> **Mục tiêu:** chuyển toàn bộ giao diện quản trị sang React + Inertia, dùng backend Laravel hiện có.
> **Bắt đầu:** 10/11/2026 · **Trạng thái:** đang thực hiện
> **Căn cứ:** `docs/PROJECT_AUDIT.md`, `docs/IMPLEMENTATION_PLAN.md`, `php artisan route:list`

---

## 1. KIỂM KÊ ROUTE ADMIN

Tổng: **120 route** (16 module). Trong đó **59 trang có `Inertia::render()`**, 1 trang đã có React (Dashboard) → **58 trang cần xây**.

| # | Module | Prefix | Route | Trang React cần | Đã có |
|---|---|---:|---:|---:|---:|
| 1 | Dashboard | `/admin/dashboard` | 2 | 1 | ✅ 1 |
| 2 | Tour | `/admin/tour` | 10 | 5 | 0 |
| 3 | Điểm đến | `/admin/diem-den` | 10 | 5 | 0 |
| 4 | Danh mục | `/admin/danh-muc` | 10 | 5 | 0 |
| 5 | Bài viết | `/admin/bai-viet` | 10 | 5 | 0 |
| 6 | Tag | `/admin/tag` | 10 | 5 | 0 |
| 7 | Slider | `/admin/slider` | 10 | 5 | 0 |
| 8 | Trang tĩnh | `/admin/trang-tinh` | 10 | 5 | 0 |
| 9 | FAQ | `/admin/faq` | 10 | 5 | 0 |
| 10 | Menu | `/admin/menu` | 12 | 6 | 0 |
| 11 | Người dùng | `/admin/tai-khoan` | 7 | 4 | 0 |
| 12 | Cài đặt | `/admin/cau-hinh` | 3 | 2 | 0 |
| 13 | Liên hệ | `/admin/lien-he` | 3 | 2 | 0 |
| 14 | Đánh giá | `/admin/danh-gia` | 7 | 2 | 0 |
| 15 | Media | `/admin/media` | 5 | 2 | 0 |
| 16 | Upload ảnh (TinyMCE) | `/admin/upload/image` | 1 | 0 (JSON API) | — |
| | **Tổng** | | **120** | **59** | **1** |

### 1.1. Danh sách trang cần xây (58)

| Module | Trang | Props backend |
|---|---|---|
| Tour | Index, Create, Show, Edit, Trash | `tours`, `categories`, `destinations`, `tour` |
| Điểm đến | Index, Create, Show, Edit, Trash | `destinations`, `regions`, `parents`, `destination` |
| Danh mục | Index, Create, Show, Edit, Trash | `categories`, `parents`, `category` |
| Bài viết | Index, Create, Show, Edit, Trash | `posts`, `categories`, `tags`, `authors`, `post` |
| Tag | Index, Create, Show, Edit, Trash | `tags`, `tag` |
| Slider | Index, Create, Show, Edit, Trash | `sliders`, `positions`, `slider` |
| Trang tĩnh | Index, Create, Show, Edit, Trash | `pages`, `page` |
| FAQ | Index, Create, Show, Edit, Trash | `faqs`, `categories`, `faq` |
| Menu | Index, Create, Show, Edit, **Items**, Trash | `menus`, `menu` |
| Người dùng | Index, Create, Show, Edit | `users`, `user` |
| Cài đặt | Index, Edit | `groups`, `group`, `groupConfig`, `current` |
| Liên hệ | Index, Show | `contacts`, `newCount`, `contact` |
| Đánh giá | Index, Trash | `reviews` |
| Media | Index, Trash | `media`, `collections`, `mediableTypes`, `trashedCount` |

---

## 2. PHÂN LOẠI ADMIN / PUBLIC

| Khu vực | Trang Inertia | Đã có | Còn thiếu | Thuộc đợt này |
|---|---:|---:|---:|:---:|
| **Admin** (`Admin/*`) | 59 | 1 | **58** | ✅ CÓ |
| **Public** (`Home`, `Tour/*`, `Destination/*`, `Blog/*`, `Pages/*`, `Search/*`) | 14 | 1 | 13 | ❌ KHÔNG |
| Auth (`Auth/Login`) | 1 | 1 | 0 | ❌ KHÔNG |

→ Trong 71 component thiếu ban đầu: **58 Admin + 13 Public**. Đợt này chỉ làm **58 Admin**.

---

## 3. MODULE KHÔNG CÓ BACKEND

| Module đề bài | Thực tế trong source | Quyết định |
|---|---|---|
| **Quản lý đơn đặt tour (booking)** | **KHÔNG TỒN TẠI**: không có bảng, model, controller, route, migration nào liên quan booking/order/đặt tour | **KHÔNG triển khai** — không tạo module giả. Sẽ báo cáo khi cần bổ sung backend (cần migration + model + route → ngoài phạm vi "không tạo bảng mới") |
| Theme & giao diện | Là group `theme` trong `config/settings.php` (2 màu + 12 field heading) | ✅ Nằm trong module **Cài đặt**, không phải module riêng |

---

## 4. NHÓM TRIỂN KHAI

| Nhóm | Nội dung | Trang | Trạng thái |
|---|---|---:|---|
| **A** | Dashboard | 1 | ✅ Hoàn thành |
| **B1** | Tour | 5 | ✅ Hoàn thành |
| **B2** | Bài viết + Tag | 10 | ✅ Hoàn thành |
| **B3** | Điểm đến + Danh mục | 10 | ✅ Hoàn thành |
| **B4** | Trang tĩnh + FAQ + Slider | 15 | ✅ Hoàn thành |
| **C** | Liên hệ + Đánh giá | 4 | ✅ Hoàn thành |
| **D** | Media + Menu | 8 | ✅ Hoàn thành |
| **E** | Người dùng + Cài đặt | 6 | ✅ Hoàn thành |
| **F** | Rà soát, responsive, đồng bộ | — | ✅ Hoàn thành |

---

## 5. QUY ƯỚC KỸ THUẬT

### 5.1. Cấu trúc file

```
resources/js/Pages/Admin/<Module>/
├── Index.jsx        → bảng + filter + phân trang + hành động
├── Create.jsx       → <Module>Form mode=create
├── Edit.jsx         → <Module>Form mode=edit
├── Show.jsx         → chi tiết + metadata + hành động
├── Trash.jsx        → danh sách đã xóa + khôi phục + xóa vĩnh viễn
└── Partials/Form.jsx → form dùng chung create/edit
```

### 5.2. Component dùng chung (đã có từ GĐ2.0)

`Button`, `Input`, `Textarea`, `Select`, `Checkbox`, `Badge`, `Card`, `Modal`, `Pagination`, `EmptyState`, `Spinner`, `FlashMessage`, `ConfirmDialog`, `FormField`, `Seo`, `ThemeStyles`.

Bổ sung trong đợt này (khi cần, dùng ≥ 2 module):
- `Components/Data/DataTable.jsx` — bảng cấu hình theo cột
- `Components/Data/FilterBar.jsx` — tìm kiếm + lọc, giữ query string
- `Components/Form/ImageField.jsx` — upload + preview
- `Components/Form/SlugField.jsx` — slug tự sinh
- `Components/Form/SeoFields.jsx`
- `Components/Form/StatusField.jsx`
- `hooks/useConfirm.js`

### 5.3. Nguyên tắc

1. Mỗi trang dùng `AdminLayout`, không tự dựng sidebar/topbar.
2. Mọi dữ liệu lấy từ props Inertia (dữ liệu thật), không hardcode thống kê.
3. Submit qua `useForm`; file dùng `forceFormData`.
4. Xóa/hành động nguy hiểm phải qua `ConfirmDialog`.
5. Bảng rỗng → `EmptyState`; submit → `processing`; form lỗi → hiển thị `errors`.
6. Phân quyền kiểm tra ở **backend** (Policy/middleware); UI chỉ ẩn nút, không thay thế.
7. Không sửa route/API nếu không cần. Nếu cần → báo cáo trước.
8. Không xóa Blade, không đụng `app.blade.php`, không đụng Public UI.

---

## 6. TIẾN ĐỘ

| Chỉ số | Giá trị |
|---|---|
| Trang Admin cần hoàn thiện | 59 |
| Đã hoàn thiện | **59 / 59 (100%)** |
| Còn rơi về `GenericPage` | **0** |
| Test Admin | 50 |
| Tổng test toàn dự án | 89 (534 assertion) |
| Build | ✅ exit 0 |

### Nhật ký nhóm

| Nhóm | Commit | Test | Build | Ghi chú |
|---|---|---|---|---|
| A | `96c5770` | 6 | ✅ | Dashboard dùng đủ 11 prop |
| B1 | `f93e921` | 8 | ✅ | Tour + toolkit + TinyMCE self-host |
| B2 | `2711f3f` | 8 | ✅ | Bài viết + Tag |
| B3 | `9b622f2` | 8 | ✅ | Điểm đến + Danh mục |
| B4 | `24eb048` | 9 | ✅ | Trang tĩnh + FAQ + Slider |
| C | `16daff6` | 9 | ✅ | Liên hệ + Đánh giá |
| D | `51cddcf` | 6 | ✅ | Media + Menu (Items editor) |
| E | `827e65d` | 5 | ✅ | Người dùng + Cài đặt |
| F | *(commit này)* | 2 | ✅ | Test bảo đảm 59/59 component tồn tại |

### 6.1. Kết quả cuối

- **59/59 trang Admin** có React component, không trang nào dùng `GenericPage`.
- Test `AdminPagesExistTest` quét controller và chặn hồi quy: nếu thêm `Inertia::render('Admin/...')` mà quên tạo file, test fail ngay.
- Tất cả module dùng chung `AdminLayout`, `DataTable`, `FilterBar`, `Pagination`, `EmptyState`, `ConfirmDialog`, `useConfirm`.
- CRUD đầy đủ: tạo/sửa/xem/xóa mềm/khôi phục/xóa vĩnh viễn (theo đúng backend từng module).
- Phân quyền kiểm tra ở backend: editor bị 403 với Users và Settings.

### 6.2. Khoảng trống đã phát hiện (cần quyết định, KHÔNG tự mở rộng)

| # | Vấn đề | Ảnh hưởng | Đề xuất |
|---|---|---|---|
| 1 | **Không có module "Đơn đặt tour"** — không bảng, model, controller, route | Không thể làm UI | Cần migration + model + controller + route (ngoài phạm vi "giữ nguyên DB"). Chờ phê duyệt |
| 2 | **Media Library không có endpoint upload** | Chỉ xem/xóa/khôi phục được; ảnh mới phải upload qua form nội dung | Thêm `MediaController@store` + route `POST admin/media` (thay đổi nhỏ ở backend) — chờ phê duyệt |
| 3 | `Post` chặn xóa cứng khi còn tag | Người dùng phải gỡ tag trước | Giữ nguyên hành vi backend; đã ghi chú trong UI Trash |
| 4 | Menu Items cấp 3 có thể sai parent (lỗi backend `saveItems`) | Hiếm gặp | Sửa `MenuController@saveItems` ở đợt sau |
| 5 | Không có upload ảnh trong trình soạn thảo khi TinyMCE lỗi tải | Fallback về textarea | Đã có cảnh báo; asset self-host nên bình thường vẫn chạy |
