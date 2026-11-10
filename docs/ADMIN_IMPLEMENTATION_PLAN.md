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
| **B1** | Tour | 5 | ⏳ |
| **B2** | Bài viết + Tag | 10 | ⏳ |
| **B3** | Điểm đến + Danh mục | 10 | ⏳ |
| **B4** | Trang tĩnh + FAQ + Slider | 15 | ⏳ |
| **C** | Liên hệ + Đánh giá | 4 | ⏳ |
| **D** | Media + Menu | 8 | ⏳ |
| **E** | Người dùng + Cài đặt | 6 | ⏳ |
| **F** | Rà soát, responsive, đồng bộ | — | ⏳ |

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
| Trang Admin cần hoàn thiện | 58 (+1 Dashboard nâng cấp) |
| Đã hoàn thiện | 1 / 59 (Dashboard) |
| Còn lại | 58 |
| Test Admin | 3 |
| Build | ✅ |

### Nhật ký nhóm

| Nhóm | Commit | Test | Build | Ghi chú |
|---|---|---|---|---|
| A | *(sẽ ghi)* | — | — | Dashboard dùng đủ 11 prop |
