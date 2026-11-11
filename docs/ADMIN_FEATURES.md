# ADMIN FEATURES — Rừng Adventure

Cập nhật theo code tại commit `9f03af2`. Route nằm dưới prefix `/admin`, middleware `admin`.

| Module | Route chính | Controller | Trạng thái | Ảnh hưởng Public |
|---|---|---|---|---|
| Dashboard | `/admin/dashboard` | DashboardController | Hoạt động | — |
| Tour | `admin/tour` | Admin\TourController | CRUD + thùng rác + khôi phục/xóa vĩnh viễn | `/tour`, `/tour/{slug}`, tour liên quan, Home |
| Destination | `admin/diem-den` | Admin\DestinationController | CRUD + thùng rác | `/diem-den`, `/diem-den/{slug}` |
| Category | `admin/danh-muc` | Admin\CategoryController | CRUD + thùng rác | Lọc tour/bài viết, menu |
| Post | `admin/bai-viet` | Admin\PostController | CRUD + thùng rác | `/bai-viet`, danh mục, tag, Home |
| Tag | `admin/tag` | Admin\TagController | CRUD | Trang tag bài viết |
| Page | `admin/trang-tinh` | Admin\PageController | CRUD + thùng rác | `/gioi-thieu`, `/trang-tinh/{slug}` |
| FAQ | `admin/faq` | Admin\FaqController | CRUD + thùng rác | `/faq` |
| Slider | `admin/slider` | Admin\SliderController | CRUD + thùng rác; upload ảnh | Hero trang chủ |
| Menu | `admin/menu` | Admin\MenuController | CRUD menu + items (2 cấp) | Header/Footer navigation |
| Media | `admin/media` | Admin\MediaController | Xem, thùng rác, xóa | Ảnh dùng trong nội dung |
| Contact | `admin/lien-he` | Admin\ContactController | Xem, đổi trạng thái | Không (chỉ nhận form) |
| Review | `admin/danh-gia` | Admin\ReviewController | Duyệt, xóa | Đánh giá trên Tour/Show |
| User | `admin/tai-khoan` | Admin\UserController | CRUD, khóa/mở, đổi role | Không |
| Settings | `admin/cau-hinh` | Admin\SettingController | Sửa nhóm cấu hình | Toàn site |
| Nội dung trang chủ | `admin/noi-dung-trang-chu` | Admin\HomeSectionController | Sửa Gallery + override | Section Thư viện |
| Nội dung giao diện | `admin/noi-dung-giao-dien/{page}` | Admin\UiTextController | Sửa text/label (Tour Index) | Trang Tour |
| Visual Editor | `admin/visual-editor` | Admin\VisualEditorController | Draft/publish/restore | Trang chủ (13 phần tử) |

## Settings — nhóm cấu hình

| Nhóm | Nội dung | Ảnh hưởng |
|---|---|---|
| general | tên, mô tả, logo, favicon | Header/Footer/SEO |
| contact | email, phone, địa chỉ, giờ làm việc, email thông báo | Footer, trang Liên hệ |
| social | facebook, instagram, youtube, tiktok | Footer |
| seo | title/description/keywords mặc định | Thẻ meta |
| hero | 6 ảnh hero theo trang | `PageHero` các trang con |
| about | nội dung giới thiệu | Home, Giới thiệu |
| palette | 6 màu | CSS vars `--rp-*` |
| typography | 10 nhóm × 5 thuộc tính | Heading/body/lead/eyebrow/breadcrumb/footer |
| appearance | 2 màu nền | `--rp-cream`, `--rp-white` |
| theme (ẩn một phần) | primary/secondary | Nút, badge |

## Giới hạn đã biết

- Media chưa có upload trong trang Media (ảnh upload qua form Tour/Slider/Settings).
- Review chỉ duyệt/xóa, không tạo trong Admin.
- Menu hỗ trợ 2 cấp.
- Visual Editor và UI Text chưa phủ toàn bộ trang.
