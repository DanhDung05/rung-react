# RUNG ADVENTURE — KẾ HOẠCH TRIỂN KHAI

> **Căn cứ:** `docs/PROJECT_AUDIT.md` (Giai đoạn 1)
> **Trạng thái:** ĐỀ XUẤT — chờ phê duyệt trước khi thực hiện Giai đoạn 2
> **Cập nhật:** 10/11/2026

---

## 1. MỤC TIÊU

Xây dựng Rung Adventure thành website du lịch hoàn chỉnh:

1. **Frontend React chuyên nghiệp** — thay thế hoàn toàn `GenericPage` bằng 13 trang thật, giữ đúng thiết kế/nghiệp vụ của bản Blade cũ.
2. **Admin CMS đầy đủ** — 58 trang quản trị thật, CRUD hoàn chỉnh, Media Library có upload.
3. **Backend Laravel ổn định** — không viết lại, chỉ bổ sung khi cần.
4. **Version Management + Changelog + What's New + `/cap-nhat`.**
5. **Activity Log / Audit Log** đầy đủ before/after.
6. **Dashboard nâng cao.**
7. **Responsive và SEO tốt.**

---

## 2. NGUYÊN TẮC THỰC HIỆN

| # | Nguyên tắc | Cách áp dụng trong kế hoạch |
|---|---|---|
| 1 | Đọc source trước khi sửa | Đã hoàn tất ở Giai đoạn 1; mỗi đợt vẫn đọc lại module liên quan |
| 2 | Không xóa dữ liệu cũ | Chỉ `migrate` thêm bảng mới; **cấm** `migrate:fresh`, `db:wipe`, import đè |
| 3 | Không chạy `migrate:fresh` | Migration mới chỉ `Schema::create` bảng mới, không sửa bảng cũ trừ khi có migration riêng + backup |
| 4 | Không đổi framework | Giữ Laravel 10 / React 18 / Inertia 2 / Vite 5 / Tailwind 3 |
| 5 | Không viết lại backend | Dùng lại 26 controller, 25 FormRequest, 16 model, ImageService, CacheHelper |
| 6 | Không chức năng giả / dữ liệu hardcode | Mọi số liệu lấy từ DB; nav/footer/settings lấy từ `settings` + `menus` |
| 7 | Không để GenericPage thay trang thật | Xóa fallback sau khi đủ 74 component; trước đó thêm **cảnh báo dev** |
| 8 | Không xóa `app.blade.php` | Root view giữ nguyên, chỉ bổ sung meta động |
| 9 | Mọi thay đổi kiểm thử được | Mỗi đợt có checklist test thủ công + test tự động ở Phase 6 |
| 10 | Source luôn chạy được | Mỗi đợt kết thúc bằng `npm run build` + smoke test 5 route |

**Quy ước chất lượng bổ sung:**
- Không thêm dependency mới nếu thư viện đã có trong `package.json` làm được (tránh phình bundle).
- Mỗi page mới phải dùng layout + component chung, không tự dựng header/sidebar.
- Không hardcode tiếng Việt trong JSX nếu chuỗi thuộc cấu hình (tên site, hotline, địa chỉ).

---

## 3. CHIẾN LƯỢC TỔNG THỂ

Thứ tự **bắt buộc** (không đảo):

```
GĐ0  Ổn định môi trường  ─────────────────────────────┐
                                                      │
GĐ2  NỀN TẢNG REACT (layout, design system, props) ◄──┘
       │
       ├─ 2A Admin shell + Dashboard
       ├─ 2B Admin CRUD 14 module
       ├─ 2C Media Library
       └─ 2D Frontend 13 trang
             │
GĐ3  CMS nâng cao (RBAC, dashboard, settings, UX)
             │
GĐ4  Version Management + Changelog + /cap-nhat
             │
GĐ5  Activity Log
             │
GĐ6  Test + Build + Deploy
```

**Lý do thứ tự:** nếu làm 71 trang trước khi có layout, mỗi trang sẽ tự dựng khung riêng như hiện tại → phải sửa lại toàn bộ. Nền tảng phải đi trước.

**Nguyên tắc chia đợt:** mỗi đợt là một đơn vị bàn giao được — build thành công, có route chạy được, có checklist test.

---

## 4. GIAI ĐOẠN 0 — ỔN ĐỊNH MÔI TRƯỜNG (bắt buộc trước GĐ2)

> Đây **không phải** thay đổi nghiệp vụ. Chỉ tạo file cấu hình và sửa lỗi hạ tầng.

| # | Việc | Lệnh / cách làm | Rủi ro | Hoàn nguyên |
|---|---|---|---|---|
| 0.1 | Tạo `.env` | `Copy-Item .env.example .env` | Không | Xóa `.env` |
| 0.2 | Sinh `APP_KEY` | `php artisan key:generate` | Không | Sinh lại |
| 0.3 | Sửa `.env`: `APP_NAME`, `APP_URL`, `DB_*`, `MAIL_*`, `APP_DEBUG=false` khi test production | Sửa tay | Thấp | File backup |
| 0.4 | Hoàn tất autoload | `composer dump-autoload` | Thấp | — |
| 0.5 | Tạo `storage/logs` + quyền ghi | `New-Item storage/logs -ItemType Directory` | Không | — |
| 0.6 | Clear cache cũ | `php artisan cache:clear`, `view:clear` | **Xóa cache cũ** (chứa PII + hash) — đúng mong muốn | — |
| 0.7 | Sửa `public/storage` | Xóa thư mục copy → `php artisan storage:link` | **Cao**: thư mục copy chứa 17 file. Phải **backup** `storage/app/public` trước | Copy lại |
| 0.8 | Cài JS deps | `npm.cmd install` (dùng `npm.cmd`, không `npm.ps1`) | Trung bình: Node 26 vs Vite 5 | Xóa `node_modules` |
| 0.9 | Build thử | `npm.cmd run build` | Trung bình | Xóa `public/build` |
| 0.10 | Smoke test | `php artisan serve` + mở `/`, `/dang-nhap`, `/admin/dashboard` | — | — |

**Tiêu chí hoàn thành GĐ0:**
- [ ] `php artisan about` chạy không lỗi.
- [ ] `GET /` trả 200 và render `Home` (không phải GenericPage).
- [ ] `GET /dang-nhap` trả 200, đăng nhập được bằng tài khoản admin có sẵn.
- [ ] Upload thử 1 ảnh → xuất hiện trong `public/storage`.
- [ ] `npm run build` thành công, `public/build/manifest.json` tồn tại.

**Quyết định cần chủ dự án xác nhận:**
- Có được xóa `public/storage` (bản copy) sau khi backup không?
- Có được tạo `.env` và `node_modules` trong workspace không?

---

## 5. GIAI ĐOẠN 2 — HOÀN THIỆN REACT

**Khối lượng:** 71 page + 1 Media Library + tầng nền.
**Ước lượng:** 41–54 dev-day.

### 5.1. Đợt 2.0 — Tầng nền (foundation)

> Không làm đợt này thì mọi trang sau sẽ phân mảnh.

#### 5.1.1. Layout

| File | Vai trò |
|---|---|
| `resources/js/Layouts/AppLayout.jsx` | Frontend: header + nav + footer + flash + scroll-to-top |
| `resources/js/Layouts/AdminLayout.jsx` | Sidebar (collapsible, mobile drawer) + topbar + breadcrumb + flash |
| `resources/js/Layouts/AuthLayout.jsx` | Trang đăng nhập |
| `resources/js/Layouts/Partials/Header.jsx` | Nav từ `menus.main`, active state theo URL |
| `resources/js/Layouts/Partials/Footer.jsx` | Cột từ `menus.footer` + settings contact/social |
| `resources/js/Layouts/Partials/AdminSidebar.jsx` | Menu admin theo quyền (ẩn module không có quyền) |
| `resources/js/Layouts/Partials/AdminTopbar.jsx` | User dropdown, thông báo liên hệ mới, link website |
| `resources/js/Layouts/Partials/Breadcrumb.jsx` | Breadcrumb theo route |

#### 5.1.2. Design system (component dùng chung)

```
resources/js/Components/
├── UI/
│   ├── Button.jsx        (variant: primary|secondary|danger|ghost, size, loading, icon)
│   ├── Input.jsx         (label, error, hint, prefix/suffix icon)
│   ├── Textarea.jsx
│   ├── Select.jsx
│   ├── Checkbox.jsx / Radio.jsx / Switch.jsx
│   ├── Badge.jsx         (status màu theo map)
│   ├── Card.jsx / Panel.jsx
│   ├── Modal.jsx         (focus trap, ESC, overlay)
│   ├── Drawer.jsx
│   ├── Dropdown.jsx
│   ├── Tabs.jsx
│   ├── Pagination.jsx    (dùng props Laravel paginator)
│   ├── EmptyState.jsx
│   ├── Spinner.jsx
│   ├── ConfirmDialog.jsx (xác nhận xóa)
│   ├── FlashMessage.jsx  (success/error từ Inertia flash)
│   └── Toast.jsx
├── Form/
│   ├── FormField.jsx     (wrapper label + error)
│   ├── FormActions.jsx   (Hủy / Lưu / Lưu & tiếp tục)
│   ├── ImageUpload.jsx   (preview, kéo thả, xóa)
│   ├── MultiImageUpload.jsx (gallery, sắp xếp, xóa từng ảnh)
│   ├── RichTextEditor.jsx (TinyMCE 8 đã có trong package.json)
│   ├── SlugInput.jsx     (tự sinh từ title, khóa được)
│   ├── SeoFields.jsx     (meta_title, meta_description, preview Google)
│   ├── StatusField.jsx   (draft/published + published_at)
│   ├── SortOrderField.jsx
│   └── TagPicker.jsx     (chọn nhiều tag)
├── Data/
│   ├── DataTable.jsx     (cột cấu hình, sort, chọn dòng, empty state)
│   ├── FilterBar.jsx     (keyword, status, category, reset)
│   ├── SearchInput.jsx   (debounce, giữ query string)
│   ├── TrashBanner.jsx
│   └── BulkActions.jsx
├── Media/
│   ├── MediaPicker.jsx   (modal chọn ảnh từ thư viện)
│   └── MediaGrid.jsx
└── Seo/
    └── Seo.jsx           (Head: title, description, canonical, OG, Twitter, JSON-LD)
```

#### 5.1.3. Tiện ích

| File | Nội dung |
|---|---|
| `resources/js/lib/routes.js` | Hàm sinh URL cho toàn bộ route (tránh hardcode chuỗi rải rác). Không thêm Ziggy |
| `resources/js/lib/format.js` | `money()`, `date()`, `datetime()`, `number()`, `excerpt()`, `stripHtml()` |
| `resources/js/lib/constants.js` | Nhãn tiếng Việt cho status/difficulty/target/role/collection |
| `resources/js/lib/validation.js` | Hiển thị lỗi Laravel theo `errors` |
| `resources/js/hooks/useFlash.js` | Toast từ `flash` |
| `resources/js/hooks/useConfirm.js` | Mở ConfirmDialog |
| `resources/js/hooks/useQuery.js` | Đồng bộ filter với query string Inertia |
| `resources/js/hooks/useDebounce.js` | Debounce search |

#### 5.1.4. Shared props (quan trọng)

Mở rộng `HandleInertiaRequests::share()`:

```php
'settings' => fn () => [
    'site_name'        => Setting::get('site_name', 'Rung Adventure'),
    'site_description' => Setting::get('site_description'),
    'site_logo'        => ...,   // URL đầy đủ
    'site_favicon'     => ...,
    'contact_email'    => ...,
    'contact_phone'    => ...,
    'contact_address'  => ...,
    'social'           => [...],
    'seo_default_title' => ...,
    'seo_default_description' => ...,
],
'theme' => fn () => [ 'primary' => ..., 'secondary' => ..., 'h1' => [...], ... ],
'navigation' => fn () => [ 'main' => ..., 'footer' => ... ],  // từ Menu + MenuItem
'flash' => [ 'success', 'error', 'info' ],
'auth.user' => [ id, name, email, role, avatar_url ],
```

**Lưu ý kỹ thuật:** `Setting::get()` đã cache 1 giờ → chi phí gần như bằng 0. Menu nên cache theo `location` + xóa cache khi admin lưu menu.

Sau đó layout đọc `settings`/`navigation`/`theme` → **hết hardcode**, và toàn bộ hệ thống Settings/Menu trong admin có tác dụng thật.

#### 5.1.5. Sửa `app.jsx`

- `import.meta.glob` **bỏ `eager: true`** → lazy load theo page, giảm bundle.
- Bổ sung `resolve` có log cảnh báo khi thiếu component (chỉ ở `import.meta.env.DEV`).
- Bọc `<App>` trong layout mặc định theo `layout` name trả về từ controller (hoặc tự chọn theo tiền tố `Admin/`).

#### 5.1.6. Xử lý CSS

- Tách khối CSS khổng lồ ở `resources/css/app.css:23` thành các file có tổ chức:
  `components.css` (button/card/table/form), `layouts.css` (header/footer/admin shell), `auth.css`.
- Import `pages/destination.css` (hiện bị mồ côi).
- Xóa hoặc để trống có chủ đích `pages/admin.css` (0 dòng) sau khi chuyển sang Tailwind/utility.

**Tiêu chí hoàn thành 2.0:**
- [ ] 3 layout hoạt động, `Admin/Dashboard` hiển thị đúng trong `AdminLayout`.
- [ ] Đổi `theme_primary_color` trong admin → màu React đổi theo (sau khi làm Settings ở 5.3).
- [ ] Đổi menu trong admin → nav React đổi theo.
- [ ] Không còn hardcode tên site/hotline trong JSX.
- [ ] Bundle build < 500 KB gzip cho trang chủ (mục tiêu).

**Ước lượng:** 6–8 dev-day.

---

### 5.2. Đợt 2A — Admin shell + Dashboard

| Trang | Component | Props từ controller |
|---|---|---|
| Tổng quan | `Admin/Dashboard.jsx` (viết lại) | `stats`, `trash`, `topTours`, `topPosts`, `chartContacts`, `maxContact`, `latestContacts`, `latestTours`, `latestPosts`, `tourByDifficulty`, `menus` |

**Nội dung Dashboard (dùng hết props backend đã tính):**
- 8 stat card (tour, bài viết, điểm đến, liên hệ mới, người dùng, danh mục, trang tĩnh, slider).
- Biểu đồ 7 ngày liên hệ (SVG/CSS thuần, không thêm chart lib).
- Top 5 tour/bài viết xem nhiều.
- 5 liên hệ mới nhất + link xử lý.
- Cảnh báo thùng rác (tour/post/contact).
- Phân bố độ khó tour.
- Danh sách menu + số item.

**Tiêu chí:** mọi prop backend trả về đều được dùng (hoặc bỏ khỏi controller nếu không cần) — không để query thừa.

**Ước lượng:** 2 dev-day.

---

### 5.3. Đợt 2B — Admin CRUD 14 module

**Pattern chung cho mỗi module:**

```
Admin/<Module>/Index.jsx   → DataTable + FilterBar + nút Tạo + hành động (Sửa/Xóa/Thùng rác)
Admin/<Module>/Create.jsx  → <Module>Form (mode create)
Admin/<Module>/Edit.jsx    → <Module>Form (mode edit)
Admin/<Module>/Show.jsx    → chi tiết + metadata + hành động
Admin/<Module>/Trash.jsx   → danh sách đã xóa + Khôi phục + Xóa vĩnh viễn
Admin/<Module>/Partials/Form.jsx  → form dùng chung create/edit
```

Dùng `useForm` của Inertia, submit `post`/`put` (hỗ trợ `forceFormData` khi có file), hiển thị `errors`, disable khi `processing`, redirect theo `flash`.

| # | Module | Trang | Trường form chính | Hành động đặc biệt |
|---|---|---|---|---|
| 1 | Tours | Index, Create, Edit, Show, Trash | category_id, name, slug, excerpt, content (rich), featured_image, gallery[], duration_days/nights, distance_km, altitude_min/max, difficulty, price_reference, start/end_point, best_time, notes, meta, is_featured, status, published_at, sort_order, destination_ids[], itineraries[] (repeater) | Gallery upload, repeater lịch trình, xóa cứng có kiểm tra |
| 2 | Destinations | 5 trang | parent_id, name, slug, description, content, image, region, province, lat/lng, altitude, meta, is_featured, sort_order, status, published_at | Chọn cha (loại chính nó), xóa cứng chặn nếu có tour/con |
| 3 | Categories | 5 trang | type (tour/post), parent_id, name, slug, description, image, meta, sort_order, is_active | Lọc theo type |
| 4 | Posts | 5 trang | user_id (tác giả), category_id, title, slug, excerpt, content, featured_image, meta, is_featured, status, published_at, tag_ids[] | TagPicker; xóa cứng chặn nếu còn tag |
| 5 | Pages | 5 trang | title, slug, content, featured_image, template, meta, status, published_at | |
| 6 | Sliders | 5 trang | title, subtitle, image, link_url, link_target, position, sort_order, is_active, start_at, end_at | Preview ảnh |
| 7 | Tags | 5 trang | name, slug, description | |
| 8 | FAQs | 5 trang | category, question, answer, sort_order, is_active | Nhóm theo category |
| 9 | Menus | Index, Create, Edit, Show, **Items**, Trash | name, location, description, is_active | **Trang Items**: kéo thả cây 3 cấp, thêm/xóa/sửa item, chọn route hoặc URL |
| 10 | Users | Index, Create, Edit, Show | name, email, password (+confirm), role, is_active, avatar | **Chỉ admin** (GĐ3); toggle active; không tự vô hiệu hóa |
| 11 | Settings | Index, Edit | Theo `config/settings.php` (7 group, 45 field) | Render field động theo `type`: string/text/boolean/number/color/image/select |
| 12 | Media | Index, Trash | (xem 5.4) | Upload, chọn nhiều, xóa mềm, khôi phục |
| 13 | Contacts | Index, Show | — | Đổi trạng thái new/read/replied/archived |
| 14 | Reviews | Index, Trash | — | Duyệt / Từ chối / Xóa |

**Chi tiết đáng chú ý:**

- **Tour form** là phức tạp nhất: 423 dòng Blade cũ (`admin/tours/partials/form.blade.php`) là tài liệu tham chiếu. Có repeater lịch trình (thêm/xóa/sắp xếp ngày), gallery nhiều ảnh, TinyMCE cho `content` và `notes`.
- **Menu Items** (542 dòng Blade cũ) là trang khó thứ hai: cần quản lý cây, `parent_key` theo index, gửi mảng `items[]` đúng format `SaveMenuItemsRequest`.
- **Settings** không cần viết 45 form tay — render động từ `groupConfig.fields`, map `type` → component. Đây là chỗ tiết kiệm nhiều công nhất.
- **Posts** yêu cầu `user_id` — form phải có select tác giả (backend yêu cầu `required|exists:users,id`).

**Tiêu chí hoàn thành 2B:**
- [ ] 58 trang admin render đúng, không còn `GenericPage`.
- [ ] Mỗi module: tạo → sửa → xóa mềm → khôi phục → xóa cứng đều chạy.
- [ ] Validation hiển thị đúng lỗi tiếng Việt từ FormRequest.
- [ ] Upload ảnh hoạt động, ảnh cũ được thay/xóa đúng.
- [ ] Filter + phân trang giữ query string.
- [ ] Responsive: sidebar drawer ở < 1024px, bảng scroll ngang ở < 768px.

**Ước lượng:** 20–26 dev-day.

---

### 5.4. Đợt 2C — Media Library

**Hiện trạng:** chỉ đọc + xóa. Cần bổ sung backend tối thiểu.

**Backend cần thêm (nhỏ, không viết lại):**

| Việc | File | Ghi chú |
|---|---|---|
| Endpoint upload | `Admin\MediaController@store` + route `POST admin/media` | Dùng `ImageService`, validate `image|mimes:jpg,jpeg,png,webp|max:10240` |
| Cập nhật metadata | `@update` + route `PATCH admin/media/{media}` | alt_text, caption, collection, sort_order |
| Lọc nâng cao | sửa `@index` | theo collection, type, khoảng ngày, keyword |
| Chọn nhiều | `@bulkDestroy` | xóa mềm hàng loạt |

**Frontend:**
- `Admin/Media/Index.jsx`: grid ảnh (24/trang), drag-drop upload, chọn nhiều, sửa alt/caption inline, copy URL, xóa mềm.
- `Admin/Media/Trash.jsx`: khôi phục / xóa vĩnh viễn (đã có backend).
- `MediaPicker` component: modal chọn ảnh dùng lại trong Tour/Post/Page/Slider.
- Hiển thị cảnh báo **orphan files** (file trên đĩa không có record) — chỉ báo cáo, không tự xóa.

**Tiêu chí:** upload → thấy trong grid → chọn từ MediaPicker → gắn vào bài viết → hiển thị ngoài frontend.

**Ước lượng:** 3–4 dev-day.

---

### 5.5. Đợt 2D — Frontend 13 trang

| # | Trang | Component | Nguồn dữ liệu | Tham chiếu Blade |
|---|---|---|---|---|
| 1 | Trang chủ | `Home.jsx` (viết lại) | `sliders`, `featuredTours`, `featuredDestinations`, `latestPosts` | `home.blade.php` + 8 partial |
| 2 | Danh sách tour | `Tour/Index.jsx` | `tours` (paginate), `categories`, `difficulties` | `tour/index.blade.php` + filter |
| 3 | Chi tiết tour | `Tour/Show.jsx` | `tour` (category, destinations, itineraries), `relatedTours` | `tour/show.blade.php` (324 dòng) |
| 4 | Danh sách điểm đến | `Destination/Index.jsx` | `destinations`, `regions` | `destination/index.blade.php` |
| 5 | Chi tiết điểm đến | `Destination/Show.jsx` | `destination` (parent, children, tours) | `destination/show.blade.php` (219 dòng) |
| 6 | Danh sách bài viết | `Blog/Index.jsx` | `posts`, `categories`, `recentPosts`, `popularTags` | `blog/index.blade.php` |
| 7 | Bài viết theo danh mục | `Blog/Category.jsx` | `category`, `posts`, sidebar | `blog/partials/category.blade.php` |
| 8 | Bài viết theo tag | `Blog/Tag.jsx` | `tag`, `posts`, sidebar | `blog/partials/tag.blade.php` |
| 9 | Chi tiết bài viết | `Blog/Show.jsx` | `post`, `relatedPosts`, `recentPosts`, `popularTags` | `blog/show.blade.php` |
| 10 | Giới thiệu | `Pages/About.jsx` | `page` + settings `about_*` | `pages/about.blade.php` |
| 11 | Liên hệ | `Pages/Contact.jsx` | settings contact | `pages/contact.blade.php` |
| 12 | FAQ | `Pages/Faq.jsx` | `faqs` (groupBy category) | `pages/faq.blade.php` |
| 13 | Tìm kiếm | `Search/Index.jsx` | `keyword`, `type`, `results`, `total` | `search/index.blade.php` |
| + | Trang tĩnh | `Pages/Show.jsx` | `page` | `pages/show.blade.php` |

**Yêu cầu chung cho frontend:**
- `Seo.jsx` cho mọi trang: title, description, canonical, OG, Twitter.
- JSON-LD: `Product`/`TouristTrip` (tour), `TouristDestination` (điểm đến), `Article` (bài viết), `FAQPage` (FAQ), `BreadcrumbList`.
- Hero image lấy từ settings `hero_image_*` hoặc slider — **không dùng Unsplash hardcode**.
- Form liên hệ: `useForm`, hiển thị success/error, chống double submit.
- Form đánh giá tour (đã có route `tours.review.store`): yêu cầu đăng nhập, chọn sao, bình luận.
- Ảnh: `loading="lazy"`, kích thước cố định để tránh CLS.
- Responsive: 360 / 768 / 1024 / 1440.
- Không render HTML thô từ `content` mà chưa sanitize (xem rủi ro R8).

**Tiêu chí:** 13 trang + trang tĩnh hiển thị đúng dữ liệu thật, không có `GenericPage`, đủ meta SEO, Lighthouse mobile ≥ 80 sau GĐ6.

**Ước lượng:** 12–16 dev-day.

---

### 5.6. Đợt 2E — Dọn dẹp & khóa fallback

- Xóa `GenericPage` fallback khỏi `app.jsx` (hoặc giữ làm 404 component).
- Thêm trang lỗi Inertia: `Errors/NotFound.jsx`, `Errors/Forbidden.jsx`, `Errors/ServerError.jsx`, `Errors/Expired.jsx`.
- Cập nhật `Handler.php` để trả Inertia cho lỗi 404/403/419/500 khi request là Inertia.
- Sửa `errors/404.blade.php` không còn gọi `@vite('resources/js/app.js')` (hoặc chuyển hẳn sang Inertia).
- Ngừng render toàn bộ Blade cũ (không xóa — quyết định xóa ở GĐ6).

**Ước lượng:** 1–2 dev-day.

---

## 6. GIAI ĐOẠN 3 — CMS NÂNG CAO

**Ước lượng:** 8–10 dev-day.

### 6.1. Phân quyền (RBAC) — ưu tiên P0

| Việc | Chi tiết |
|---|---|
| Bảng `roles`, `permissions`, `role_user`? | **Đề xuất tối giản:** giữ cột `role` enum hiện có (`admin`, `editor`, `user`), bổ sung `Permission` dạng Gate + ma trận quyền trong `config/permissions.php`. Không tạo bảng mới nếu chưa cần |
| Policy | `TourPolicy`, `PostPolicy`, `DestinationPolicy`, `CategoryPolicy`, `PagePolicy`, `SliderPolicy`, `TagPolicy`, `FaqPolicy`, `MenuPolicy`, `MediaPolicy`, `ContactPolicy`, `ReviewPolicy`, `UserPolicy`, `SettingPolicy` |
| Gate | `manage-users`, `manage-settings`, `manage-menus`, `view-activity-log`, `publish-content`, `delete-content` |
| Middleware | `can:` trên route admin; `EnsureUserIsAdmin` chỉ `admin` cho module nhạy cảm |
| Chống leo thang | `UserPolicy@update` cấm đổi `role` nếu không phải admin; cấm tự đổi role; cấm tự vô hiệu hóa (đã có) |
| UI | Ẩn menu/nút theo quyền; trang 403 thân thiện |

**Mapping đề xuất:**

| Module | admin | editor |
|---|---|---|
| Tour, Post, Destination, Category, Page, Slider, Tag, FAQ | toàn quyền | toàn quyền (trừ xóa cứng?) |
| Menu | ✅ | ❌ |
| Media | ✅ | ✅ |
| Contacts | ✅ | ✅ |
| Reviews | ✅ | ✅ |
| Users | ✅ | ❌ |
| Settings | ✅ | ❌ |
| Activity Log | ✅ | chỉ của mình |
| Version/Changelog | ✅ | ❌ |

### 6.2. Dashboard nâng cao

- Bộ lọc thời gian (7/30/90 ngày).
- Biểu đồ tour/bài viết theo tháng.
- Tỷ lệ liên hệ đã xử lý.
- Top điểm đến theo số tour.
- Hoạt động gần đây (từ Activity Log — phụ thuộc GĐ5, có thể để cuối GĐ5).
- Widget "phiên bản hiện tại" (từ GĐ4).

### 6.3. Quản lý nội dung nâng cao

- Kéo thả sắp xếp (`sort_order`) cho slider, menu item, gallery.
- Nhân bản (duplicate) tour/bài viết.
- Xem trước (preview) bài viết draft bằng signed URL.
- Lịch đăng (published_at) + trạng thái "scheduled" (cần migration enum mới — cân nhắc).
- Tìm kiếm toàn cục trong admin (tour/post/page/contact).

### 6.4. Cài đặt & UX quản trị

- Trang Settings render động theo `config/settings.php` (đã có ở 2B #11) + preview màu/font trực tiếp.
- Theme editor: đổi màu/font → xem trước ngay (CSS variables).
- Quản lý cache: nút "Xóa cache" từng nhóm (dùng `CacheHelper`).
- Thông báo real-time: bật lại `BroadcastServiceProvider`, sửa channel `admin-notifications` thành private + authorize, cấu hình Soketi.
- Command + schedule: dọn thùng rác > 30 ngày, backup DB, warm cache.

---

## 7. GIAI ĐOẠN 4 — VERSION MANAGEMENT

**Ước lượng:** 5–6 dev-day.

### 7.1. Schema (migration mới, chỉ CREATE)

```
versions
  id, version (unique, vd 1.2.0), title, summary, body (longText),
  type (major|minor|patch), status (draft|published), is_current,
  released_at, created_by, timestamps, softDeletes

changelog_entries
  id, version_id (FK), category (added|changed|fixed|removed|security),
  description, sort_order, timestamps

version_user (read tracking cho "What's New")
  id, version_id, user_id, seen_at, unique(version_id, user_id)
```

### 7.2. Chức năng

| Khu vực | Việc |
|---|---|
| Admin | CRUD version, CRUD changelog entry trong version, đặt `is_current`, publish/draft |
| Public | `/cap-nhat` — danh sách phiên bản (timeline), `/cap-nhat/{version}` — chi tiết |
| What's New | Modal hiện lần đầu sau khi user đăng nhập và có version mới hơn `last_seen_version` |
| Widget | Dashboard: "Phiên bản hiện tại", "Số bản phát hành" |
| SEO | Meta + JSON-LD `SoftwareApplication` (nếu phù hợp) hoặc `Article` |
| Sitemap | Thêm `/cap-nhat` và từng version |
| API | `GET /api/versions`, `GET /api/versions/latest` (public, cache 5 phút) |

### 7.3. Tích hợp

- `CHANGELOG.md` root là bản tĩnh (cho dev), DB là bản động (cho người dùng) — **đồng bộ thủ công mỗi release**, ghi rõ trong DEPLOYMENT.
- `config/app.php` thêm `'version' => env('APP_VERSION', '1.0.0')`.

---

## 8. GIAI ĐOẠN 5 — ACTIVITY LOG

**Ước lượng:** 4–5 dev-day.

### 8.1. Schema

```
activity_logs
  id
  event            (created|updated|deleted|restored|force_deleted|login|logout|login_failed|export|setting_updated)
  subject_type, subject_id      (nullable, polymorphic)
  causer_id        (FK users, nullable)
  causer_name, causer_email     (snapshot — không mất khi user bị xóa)
  description      (chuỗi người đọc được)
  properties       (json: old, attributes, ip, user_agent, url, method)
  batch_uuid       (nhóm thao tác trong 1 request)
  created_at
  index (subject_type, subject_id), (causer_id, created_at), (event, created_at)
```

**Quan trọng:** dùng **snapshot** `causer_name/email` thay vì chỉ FK, vì user có soft delete.

### 8.2. Cơ chế ghi

| Cách | Phạm vi | Ưu điểm | Nhược điểm |
|---|---|---|---|
| Observer trên Model | created/updated/deleted/restored/forceDeleted | Tự động, không sửa controller | Bỏ sót thao tác không qua Eloquent (query builder, sync) |
| Middleware | login/logout/failed, request-level | Bắt được mọi request | Không biết before/after |
| Gọi tay trong service | thao tác phức tạp (menu save, settings) | Chính xác | Phải nhớ gọi |

**Đề xuất:** Observer cho 8 model chính (Tour, Post, Destination, Category, Page, Slider, Tag, Faq, Menu, MenuItem, User, Setting) + Event listener cho `Login`/`Logout`/`Failed` + gọi tay trong `MenuController@saveItems`, `SettingController@update`, `MediaController@store/destroy`.

**Before/after:** trong `updating`, lưu `$model->getOriginal()` làm `old`; `$model->getChanges()` làm `attributes`. Ẩn các field nhạy cảm (`password`, `remember_token`) — **bắt buộc**.

### 8.3. Giao diện

- `Admin/ActivityLog/Index.jsx`: bảng (thời gian, người dùng, hành động, đối tượng, IP), filter theo event/user/subject/ngày, phân trang.
- `Admin/ActivityLog/Show.jsx`: diff before/after dạng 2 cột hoặc unified diff.
- Tab "Lịch sử" trong trang chi tiết Tour/Post/User.
- Dashboard widget "Hoạt động gần đây".
- Retention: command `activity-log:prune --days=180`, schedule hàng ngày.

### 8.4. Bảo mật

- Chỉ `admin` xem toàn bộ; `editor` chỉ xem log của chính mình (hoặc không xem).
- Không log mật khẩu, token, dữ liệu thanh toán.
- Log cả `login_failed` + IP để phát hiện brute force.

---

## 9. GIAI ĐOẠN 6 — TEST & DEPLOY

**Ước lượng:** 6–8 dev-day.

### 9.1. Test

| Loại | Nội dung | Số lượng mục tiêu |
|---|---|---|
| Unit | `ImageService`, `CacheHelper`, `Setting`, slug generator | 15–20 |
| Feature — Auth | login đúng/sai, inactive, redirect theo role, logout, rate limit | 10 |
| Feature — CRUD | mỗi module: index/create/store/edit/update/destroy/restore/forceDestroy | 14 × 4 = 56 |
| Feature — Policy | editor không vào users/settings, editor không đổi role | 10 |
| Feature — Media | upload, validate, xóa mềm, xóa cứng + file | 6 |
| Feature — Version | CRUD, publish, what's new | 6 |
| Feature — Activity | ghi log create/update/delete, before/after, ẩn password | 8 |
| Feature — Frontend | 13 route trả 200 + đúng component | 13 |
| Build | `npm run build` không warning chặn | 1 |

**Cấu hình test bắt buộc:**
- Bỏ comment `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:` trong `phpunit.xml`.
- Dùng `RefreshDatabase`.
- `$this->withoutVite()` cho test render Inertia.
- Seed tối thiểu (admin + editor + 1 tour + 1 post).

### 9.2. Kiểm tra bảo mật

- [ ] Editor không thể vào `/admin/tai-khoan`, `/admin/cau-hinh`.
- [ ] Editor không thể đổi role.
- [ ] CSRF hoạt động trên mọi POST/PUT/DELETE.
- [ ] Upload chặn file không phải ảnh; chặn SVG hoặc sanitize.
- [ ] XSS: sanitize HTML từ TinyMCE trước khi render.
- [ ] Rate limit login + contact.
- [ ] `.env` không nằm trong public; `APP_DEBUG=false`.
- [ ] Security headers.
- [ ] Admin routes có `auth` + `can`.

### 9.3. Kiểm tra responsive & hiệu năng

- 360 / 414 / 768 / 1024 / 1440.
- Lighthouse mobile ≥ 80 (Performance), ≥ 90 (SEO/Accessibility).
- Ảnh: WebP + `loading=lazy` + kích thước cố định.
- N+1: dùng Debugbar/`DB::listen` kiểm tra trang chủ, danh sách tour, chi tiết tour.

### 9.4. Build & deploy

Xem `docs/DEPLOYMENT.md`.

---

## 10. KIẾN TRÚC THƯ MỤC ĐÍCH

```
resources/js/
├── app.jsx
├── echo.js
├── Layouts/
│   ├── AppLayout.jsx
│   ├── AdminLayout.jsx
│   ├── AuthLayout.jsx
│   └── Partials/ (Header, Footer, AdminSidebar, AdminTopbar, Breadcrumb, Flash)
├── Components/
│   ├── UI/
│   ├── Form/
│   ├── Data/
│   ├── Media/
│   └── Seo/
├── Pages/
│   ├── Home.jsx
│   ├── Tour/{Index,Show}.jsx
│   ├── Destination/{Index,Show}.jsx
│   ├── Blog/{Index,Category,Tag,Show}.jsx
│   ├── Pages/{About,Contact,Faq,Show}.jsx
│   ├── Search/Index.jsx
│   ├── Auth/Login.jsx
│   ├── Errors/{NotFound,Forbidden,ServerError,Expired}.jsx
│   ├── CapNhat/{Index,Show}.jsx          (GĐ4)
│   └── Admin/
│       ├── Dashboard.jsx
│       ├── Tours/ Posts/ Destinations/ Categories/ Pages/
│       ├── Sliders/ Tags/ Faqs/ Menus/ Users/ Settings/
│       ├── Media/ Contacts/ Reviews/
│       ├── ActivityLog/                   (GĐ5)
│       └── Versions/ Changelogs/          (GĐ4)
├── lib/ (routes, format, constants, validation)
└── hooks/ (useFlash, useConfirm, useQuery, useDebounce)

app/
├── Http/Controllers/Admin/      (giữ nguyên + bổ sung Media::store/update, Version, ActivityLog)
├── Http/Middleware/             (bổ sung Authorize qua Gate)
├── Models/                      (giữ nguyên + Version, ChangelogEntry, ActivityLog)
├── Policies/                    (MỚI — GĐ3)
├── Observers/                   (MỚI — GĐ5)
└── Services/                    (ImageService giữ nguyên, ActivityLogger MỚI)
```

---

## 11. THỨ TỰ THỰC HIỆN & ƯỚC LƯỢNG

| Đợt | Nội dung | Dev-day | Phụ thuộc | Bàn giao |
|---|---|---:|---|---|
| **GĐ0** | Ổn định môi trường | 1–2 | — | App chạy được local |
| **2.0** | Tầng nền React | 6–8 | GĐ0 | Layout + design system + shared props |
| **2A** | Admin shell + Dashboard | 2 | 2.0 | Dashboard thật |
| **2B** | Admin CRUD 14 module | 20–26 | 2.0 | 58 trang admin |
| **2C** | Media Library | 3–4 | 2.0 | Upload + picker |
| **2D** | Frontend 13 trang | 12–16 | 2.0 | Website thật |
| **2E** | Dọn dẹp + error pages | 1–2 | 2B, 2D | Bỏ GenericPage |
| **GĐ3** | CMS nâng cao + RBAC | 8–10 | GĐ2 | Phân quyền, dashboard nâng cao |
| **GĐ4** | Version Management | 5–6 | GĐ3 | `/cap-nhat` |
| **GĐ5** | Activity Log | 4–5 | GĐ3 | Audit log |
| **GĐ6** | Test + build + deploy | 6–8 | tất cả | Production |

**Tổng:** ~68–87 dev-day (khoảng 14–18 tuần cho 1 dev toàn thời gian).

**Đề xuất chia nhỏ để phê duyệt:** mỗi lần duyệt **một đợt** (2.0 → 2A → 2B…), không duyệt cả Giai đoạn 2 một lần. Như vậy có thể dừng/điều chỉnh sau mỗi đợt mà không mất công.

---

## 12. RỦI RO & BIỆN PHÁP

| Rủi ro | Biện pháp |
|---|---|
| Làm 71 trang mà không có layout → phải làm lại | Bắt buộc hoàn thành 2.0 trước, review layout với chủ dự án |
| Mất dữ liệu do migration/import | Cấm `migrate:fresh`; backup DB trước mọi migration; migration chỉ CREATE bảng mới |
| `public/storage` copy gây mất ảnh khi sửa | Backup `storage/app/public` trước khi xóa thư mục copy |
| Node 26 không tương thích Vite 5 | Test `npm install && npm run build` ngay GĐ0; nếu lỗi, dùng Node 20 LTS (không đổi Vite) |
| Laravel 10 EOL | Ghi nhận; không nâng cấp trong phạm vi dự án này; theo dõi advisory; cân nhắc nâng Laravel 11 ở dự án riêng |
| Bundle React phình to | Lazy import page, tách vendor chunk, không thêm chart/UI lib nặng |
| Stored XSS từ TinyMCE/SVG | Sanitize server-side (HTMLPurifier) + chặn SVG hoặc serve với `Content-Disposition` |
| Thiếu test → hồi quy | Mỗi đợt có checklist test thủ công; GĐ6 bổ sung test tự động |

---

## 13. CHECKLIST PHÊ DUYỆT

Trước khi bắt đầu Giai đoạn 2, cần chủ dự án xác nhận:

- [ ] Đồng ý thứ tự: GĐ0 → 2.0 → 2A/2B/2C/2D → 2E.
- [ ] Đồng ý cho tạo `.env`, chạy `key:generate`, `npm install`.
- [ ] Đồng ý backup + xóa `public/storage` (thư mục copy) và tạo symlink.
- [ ] Đồng ý kiến trúc thư mục ở mục 10.
- [ ] Đồng ý dùng `config/permissions.php` + Policy, **không** tạo bảng roles/permissions mới (trừ khi yêu cầu khác).
- [ ] Đồng ý giữ 132 file Blade đến hết GĐ2 để đối chiếu, xóa ở GĐ6.
- [ ] Đồng ý phạm vi Giai đoạn 2 **chỉ** gồm React + Media Library, **chưa** gồm Version/Activity.
- [ ] Chọn mức độ ưu tiên: làm tuần tự (an toàn) hay song song (nhanh nhưng khó kiểm soát).

---

*Kế hoạch này dựa trên số liệu thực tế của source, không phải ước lượng cảm tính. Mọi mốc đều có tiêu chí hoàn thành kiểm chứng được.*

---

## 14. TIẾN ĐỘ THỰC TẾ — cập nhật 10/11/2026

### 14.1. Trạng thái các đợt

| Đợt | Nội dung | Trạng thái | Ghi chú |
|---|---|---|---|
| **GĐ0** | Ổn định môi trường | ✅ **HOÀN THÀNH** | `.env`, `APP_KEY`, `storage/logs`, symlink, `npm install`, build, git |
| **Vá bảo mật** | Chặn editor leo thang đặc quyền | ✅ **HOÀN THÀNH** | Làm **trước** 2.0 theo yêu cầu; 11 test |
| **2.0** | Tầng nền React | ✅ **HOÀN THÀNH** | 3 layout, 14 UI component, shared props, SEO, lazy load |
| 2A | Admin shell + Dashboard | ⏳ Chờ duyệt | |
| 2B | Admin CRUD 14 module | ⏳ | |
| 2C | Media Library | ⏳ | |
| 2D | Frontend 13 trang | ⏳ | |
| 2E | Dọn dẹp + error pages | ⏳ | |
| GĐ3–6 | CMS nâng cao, Version, Activity Log, Test & Deploy | ⏳ | |

### 14.2. Đối chiếu với kế hoạch 2.0

| Hạng mục kế hoạch | Đã làm | Ghi chú |
|---|:---:|---|
| `AppLayout` / `AdminLayout` / `AuthLayout` | ✅ | |
| Partials: Header, Footer, AdminSidebar, AdminTopbar, Breadcrumb | ✅ | Breadcrumb gộp vào `AdminLayout` |
| 20 UI component dự kiến | ✅ 14 | Đủ dùng cho 2A/2B; các component còn lại (Tabs, Drawer, Dropdown, Switch, Radio) sẽ thêm khi module cần |
| `lib/routes.js`, `format.js`, `constants.js`, `validation.js` | ✅ 3/4 | `validation.js` chưa cần — Laravel trả `errors` trực tiếp |
| `hooks`: useFlash, useConfirm, useQuery, useDebounce | ✅ 1/4 | `useConfirm` sẽ làm cùng 2B (khi có nút xóa); `useQuery`/`useDebounce` làm ở 2B (khi có filter) |
| `Seo.jsx` | ✅ | |
| Shared props settings/theme/navigation | ✅ | Có try/catch, cache |
| `app.jsx` lazy + cảnh báo thiếu page | ✅ | |
| Tách CSS khổng lồ trong `app.css` | 🟡 một phần | Đã tách phần React UI sang `react-ui.css`; khối inline cũ vẫn giữ để không phá giao diện hiện tại, sẽ dọn ở 2D |
| Chuyển 4 trang hiện có sang layout | ✅ | Home, GenericPage, Dashboard, Login |
| Build + test | ✅ | 19 test, build exit 0 |

### 14.3. Sai lệch so với kế hoạch (có lý do)

1. **Thêm 1 bước bắt buộc ngoài kế hoạch: vá bảo mật trước 2.0** — theo yêu cầu của chủ dự án.
2. **Thêm `resources/js/app.js` vào Vite input** — ngoài kế hoạch 2.0, nhưng cần để sửa lỗi 404 → 500 (B3/R7).
3. **Hoãn `validation.js`, `useConfirm`, `useQuery`, `useDebounce`** — chưa có nơi dùng; làm cùng 2B để tránh code chết.
4. **Không xóa 132 file Blade** — đúng cam kết, giữ đến hết GĐ2.
5. **Không đổi dependency nào** — `package.json` giữ nguyên; chỉ thêm `package-lock.json` (trước đó chưa có).

### 14.4. Ước lượng còn lại

| Đợt | Dev-day |
|---|---:|
| 2A | 2 |
| 2B | 20–26 |
| 2C | 3–4 |
| 2D | 12–16 |
| 2E | 1–2 |
| GĐ3 | 8–10 |
| GĐ4 | 5–6 |
| GĐ5 | 4–5 |
| GĐ6 | 6–8 |
| **Còn lại** | **61–79 dev-day** |

### 14.5. Điều kiện chuyển sang 2A

- [ ] Chủ dự án kiểm tra giao diện mới (header/footer/sidebar) trên trình duyệt.
- [ ] Xác nhận backup `C:\laragon\www\_backups-rung-react\20261110-115656` vẫn giữ.
- [ ] Phê duyệt phạm vi 2A (dashboard dùng hết 11 prop backend đã tính).
