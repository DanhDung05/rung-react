# ADMIN FEATURES — Rừng Adventure

Cập nhật: 2026-11-16 · commit `3e7854a` · version `2.2.0`.
Mọi route nằm dưới `/admin`, middleware `admin`. Hướng dẫn thao tác chi tiết: [ADMIN_GUIDE.md](ADMIN_GUIDE.md).

## 1. Danh mục module

| Module | Route chính | Controller | Trạng thái | Ảnh hưởng Public |
|---|---|---|---|---|
| Dashboard | `/admin/dashboard` | DashboardController | Hoạt động | — |
| Tour | `/admin/tour` | TourController | CRUD + thùng rác + khôi phục/xóa vĩnh viễn | `/tour`, `/tour/{slug}`, Home |
| Điểm đến | `/admin/diem-den` | DestinationController | CRUD + thùng rác | `/diem-den`, `/diem-den/{slug}` |
| Danh mục | `/admin/danh-muc` | CategoryController | CRUD + thùng rác | Lọc tour/bài viết, menu |
| Bài viết | `/admin/bai-viet` | PostController | CRUD + thùng rác | `/bai-viet`, danh mục, tag, Home |
| Tag | `/admin/tag` | TagController | CRUD | Trang tag |
| Trang tĩnh | `/admin/trang-tinh` | PageController | CRUD + thùng rác | `/gioi-thieu`, `/trang-tinh/{slug}` |
| FAQ | `/admin/faq` | FaqController | CRUD + thùng rác | `/faq` |
| Slider | `/admin/slider` | SliderController | CRUD + thùng rác + upload ảnh | Hero trang chủ/trang con |
| Menu | `/admin/menu` | MenuController | CRUD menu + item 2 cấp | Header/Footer |
| Media | `/admin/media` | MediaController | Xem/lọc/thùng rác/xóa; **chưa upload trực tiếp** | Ảnh nội dung, carousel |
| Liên hệ | `/admin/lien-he` | ContactController | Xem, đổi trạng thái, xóa | Không (nhận form) |
| Đánh giá | `/admin/danh-gia` | ReviewController | Duyệt/từ chối/xóa | Đánh giá trên Tour |
| Người dùng | `/admin/tai-khoan` | UserController | CRUD, khóa/mở, đổi role (admin only) | Không |
| Cài đặt | `/admin/cau-hinh` | SettingController | Sửa nhóm cấu hình + preview | Toàn site |
| Nội dung trang chủ | `/admin/noi-dung-trang-chu` | HomeSectionController | Gallery + override | Section Thư viện |
| Nội dung giao diện | `/admin/noi-dung-giao-dien/{page}` | UiTextController | Text/label (hiện Tour Index) | Trang Tour |
| Visual Editor | `/admin/visual-editor` | VisualEditorController | Draft/publish/history | Trang chủ |
| Lịch sử cập nhật | `/admin/lich-su-cap-nhat` | ReleaseController | CRUD + timeline + lọc | Không (Admin) |
| Hướng dẫn sử dụng | `/admin/huong-dan` | GuideController | Render Markdown từ `docs/` | Không (Admin) |

## 2. Settings — nhóm cấu hình

| Nhóm | Nội dung | Ảnh hưởng |
|---|---|---|
| general | tên, mô tả, logo, favicon | Header/Footer/SEO |
| contact | email, phone, địa chỉ, giờ làm việc | Footer, trang Liên hệ |
| social | facebook, instagram, youtube, tiktok | Footer |
| seo | title/description/keywords | Meta tags |
| hero | 6 ảnh hero + `hero_overlay_enabled` | PageHero, slider |
| about | nội dung giới thiệu | Home, Giới thiệu |
| palette | **10 màu** (`palette_*`) | CSS vars `--rp-*` |
| typography | 10 nhóm × 5 thuộc tính | Heading/body/lead/eyebrow/breadcrumb/footer |
| theme | `theme_primary_color`, `theme_secondary_color` | Nút/badge Admin |
| appearance | đã ẩn khỏi danh sách (chỉ thông báo cũ) | `bg_*` fallback |

## 3. Tính năng nổi bật

### Visual Editor (trang chủ)
- Chọn thành phần, sửa text/style.
- Bật/tắt 6 section; thêm section quote band; đổi thứ tự bằng kéo-thả/↑↓.
- Sửa ảnh Banner/CTA (URL Media), liên kết nút.
- Draft → Publish; 5 bản lịch sử; khôi phục.

### Thư viện & Carousel
- 20 ảnh WebP import qua `media:import-the-window` vào collection `gallery`.
- `home_gallery_media` chia sẻ ra Home; carousel 3D (coverflow, kéo/swipe, autoplay, thumbnail, modal) khung ảnh ngang 4:3.
- Ảnh curated thay thế ảnh slider/tour trong section Thư viện.

### Giao diện Admin
- Sáng/Tối (localStorage), sidebar thu gọn 76px, sidebar cố định.
- Font Segoe UI weight 700 thật, tránh synthetic bold.
- Logo brand PNG trắng/màu theo nền.

### Lịch sử cập nhật
- Bảng `releases` (version duy nhất), timeline, lọc, chi tiết, CRUD.
- Version hiện tại đọc từ `VERSION`; nội dung nhiều dòng/danh sách.
- Không seed bản ghi; đồng bộ `CHANGELOG.md` theo quy trình [RELEASES.md](RELEASES.md).

### Trợ giúp trong Admin
- Trang `/admin/huong-dan` render trực tiếp Markdown từ `docs/` (ADMIN_GUIDE, ADMIN_FEATURES, RELEASES, DESIGN_TOKENS, ARCHITECTURE).
- Tìm kiếm, mục lục, điều hướng tài liệu; chỉ admin/editor đăng nhập mới xem.

## 4. Giới hạn đã biết

- Media chưa có form upload trực tiếp; ảnh upload qua form Tour/Bài viết/Slider/Cài đặt; import bằng command.
- UI Text mới phủ Tour Index.
- Visual Editor: text áp dụng client-side; ảnh nhập URL (chưa Media picker).
- Review chỉ duyệt/xóa, không tạo trong Admin.
- Menu tối đa 2 cấp.
- B2.2 chưa kiểm thử đủ 8 trang × 3 breakpoint.
- Nhiều tour/điểm đến/bài viết còn thiếu ảnh thật.
- Soketi realtime mặc định tắt (`BROADCAST_DRIVER=log`).
