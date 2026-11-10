# RUNG ADVENTURE — TÀI LIỆU DATABASE

> **Cập nhật:** 10/11/2026 — Giai đoạn 1
> **Nguồn:** 23 file migration + đọc trực tiếp MySQL `rungadventure` (MySQL 8.0.30)
> **Trạng thái migration:** 23/23 đã chạy (batch 1–5)

---

## 1. THÔNG TIN KẾT NỐI

| Thông số | Giá trị mặc định (`.env.example`) |
|---|---|
| Driver | `mysql` |
| Host / Port | `127.0.0.1` / `3306` |
| Database | `rungadventure` |
| Username | `root` |
| Password | (rỗng trên máy local) |
| Charset / Collation | `utf8mb4` / `utf8mb4_unicode_ci` |
| Strict mode | `true` (`config/database.php`) |

> ⚠ Trên máy hiện tại **chưa có `.env`** nên Laravel dùng giá trị mặc định `DB_USERNAME=forge` → kết nối thất bại. Phải tạo `.env` (xem `docs/DEPLOYMENT.md`).

---

## 2. DANH SÁCH BẢNG (23)

| # | Bảng | Nhóm | SoftDelete | Số dòng hiện tại |
|---|---|---|---:|:---:|---:|
| 1 | `users` | Hệ thống | ✅ | 3 |
| 2 | `password_reset_tokens` | Hệ thống | ❌ | 0 |
| 3 | `failed_jobs` | Hệ thống | ❌ | 0 |
| 4 | `personal_access_tokens` | Hệ thống | ❌ | 0 |
| 5 | `jobs` | Hệ thống | ❌ | 0 |
| 6 | `migrations` | Hệ thống | ❌ | 23 |
| 7 | `categories` | Nội dung | ✅ | 11 |
| 8 | `destinations` | Nội dung | ✅ | 13 |
| 9 | `tours` | Nội dung | ✅ | 9 |
| 10 | `tour_itineraries` | Nội dung | ✅ | 18 |
| 11 | `tour_destination` | Liên kết | ❌ | 7 |
| 12 | `posts` | Nội dung | ✅ | 9 |
| 13 | `tags` | Nội dung | ✅ | 12 |
| 14 | `post_tag` | Liên kết | ❌ | 15 |
| 15 | `pages` | Nội dung | ✅ | 6 |
| 16 | `faqs` | Nội dung | ✅ | 10 |
| 17 | `sliders` | Giao diện | ✅ | 5 |
| 18 | `menus` | Giao diện | ✅ | 4 |
| 19 | `menu_items` | Giao diện | ✅ | 14 |
| 20 | `media` | Phương tiện | ✅ | 2 |
| 21 | `contacts` | Tương tác | ✅ | 23 |
| 22 | `reviews` | Tương tác | ✅ | 0 |
| 23 | `settings` | Cấu hình | ✅ | 45 |

**Bảng chưa tồn tại (sẽ tạo ở GĐ4/GĐ5):** `versions`, `changelog_entries`, `version_user`, `activity_logs`, `job_batches`.

---

## 3. SƠ ĐỒ QUAN HỆ (dạng văn bản)

```
users
 ├─1:n─ posts (user_id, cascade delete)
 ├─1:n─ reviews (user_id, cascade delete)
 └─1:n─ activity_logs (causer_id)          [GĐ5]

categories (self: parent_id)
 ├─1:n─ tours (category_id, nullOnDelete)
 ├─1:n─ posts (category_id, nullOnDelete)
 └─1:n─ categories (parent_id)

destinations (self: parent_id)
 └─n:n─ tours  qua tour_destination

tours
 ├─1:n─ tour_itineraries (tour_id, cascade)
 ├─n:n─ destinations (tour_destination)
 ├─1:n─ reviews (tour_id, cascade)
 └─1:n─ media (morph: mediable)

posts
 ├─n:n─ tags (post_tag)
 └─1:n─ media (morph)

pages / sliders / destinations / categories
 └─1:n─ media (morph)      [chỉ Tour & Post dùng thực tế]

menus
 └─1:n─ menu_items (menu_id, cascade)
          └─self parent_id
```

---

## 4. CHI TIẾT TỪNG BẢNG

### 4.1. `users`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| id | bigint unsigned | PK, auto |
| name | varchar(255) | not null |
| email | varchar(255) | **unique**, not null |
| email_verified_at | timestamp | nullable |
| password | varchar(255) | not null, cast `hashed` |
| role | enum(`admin`,`editor`,`user`) | default `user`, index |
| is_active | tinyint(1) | default 1, index |
| avatar | varchar(255) | nullable |
| remember_token | varchar(100) | nullable |
| created_at / updated_at | timestamp | |
| deleted_at | timestamp | soft delete |

**Model:** `User` (HasApiTokens, HasFactory, Notifiable, SoftDeletes). Helper: `isAdmin()`, `isEditor()`. Scope: `active()`, `role()`.

**Ghi chú bảo mật:** password cast `hashed` + controller còn `Hash::make()` → hash 2 lần? **Không**: cast `hashed` chỉ hash nếu giá trị chưa phải hash Bcrypt; `Hash::make()` tạo hash mới nên cast bỏ qua. An toàn nhưng dư thừa.

---

### 4.2. `categories`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| id | bigint | PK |
| type | enum(`tour`,`post`) | not null, index |
| parent_id | bigint | FK → categories.id, **nullOnDelete** |
| name | varchar(255) | not null |
| slug | varchar(255) | **unique toàn cục** |
| description | text | nullable |
| image | varchar(255) | nullable |
| meta_title | varchar(255) | nullable |
| meta_description | text | nullable |
| sort_order | int | default 0, index |
| is_active | tinyint(1) | default 1, index |
| timestamps + deleted_at | | |

**Ràng buộc nghiệp vụ:** slug unique toàn cục ⇒ danh mục tour và danh mục bài viết **không thể** trùng slug. `generateSlug()` thêm `-1`, `-2` khi trùng.

---

### 4.3. `destinations`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| id | bigint | PK |
| parent_id | bigint | FK self, nullOnDelete |
| name | varchar(255) | not null |
| slug | varchar(255) | **unique** |
| description | text | nullable |
| content | longText | nullable |
| image | varchar(255) | nullable |
| region | varchar(100) | nullable, index |
| province | varchar(100) | nullable |
| latitude | decimal(10,7) | nullable |
| longitude | decimal(10,7) | nullable |
| altitude | int | nullable |
| meta_title / meta_description | | nullable |
| is_featured | tinyint(1) | default 0, index |
| sort_order | int | default 0, index |
| status | enum(`draft`,`published`) | default draft, index |
| published_at | timestamp | nullable, index |
| timestamps + deleted_at | | |

**Quan hệ:** `parent()`, `children()`, `tours()` (n:n), `media()` (morph).
**Xóa cứng:** chặn nếu còn tours hoặc children.

---

### 4.4. `tours`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| id | bigint | PK |
| category_id | bigint | FK categories, **nullOnDelete**, index |
| name | varchar(255) | not null |
| slug | varchar(255) | **unique** |
| excerpt | text | nullable |
| content | longText | nullable (HTML từ TinyMCE) |
| featured_image | varchar(255) | nullable |
| duration_days / duration_nights | int | nullable |
| distance_km | decimal(8,2) | nullable |
| altitude_min / altitude_max | int | nullable |
| difficulty | enum(`easy`,`moderate`,`hard`,`expert`) | nullable, index |
| price_reference | bigint unsigned | nullable (VNĐ) |
| start_point / end_point / best_time | varchar(255) | nullable |
| notes | longText | nullable |
| meta_title | varchar(255) | nullable |
| meta_description | text | nullable |
| is_featured | tinyint(1) | default 0, index |
| status | enum(`draft`,`published`) | default draft, index |
| published_at | timestamp | nullable, index |
| sort_order | int | default 0, index |
| view_count | int unsigned | default 0 |
| timestamps + deleted_at | | |

**Quan hệ:** category, destinations (n:n), itineraries (1:n, sắp theo day_number), reviews (chỉ approved), allReviews, media (morph).
**Accessor:** `average_rating`, `reviews_count` (truy vấn riêng — cẩn thận N+1).
**Scope:** `published()`, `featured()`, `byCategory()`, `difficulty()`, `search()`, `ordered()`.

---

### 4.5. `tour_itineraries`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| id | bigint | PK |
| tour_id | bigint | FK tours, **cascade delete**, index |
| day_number | int | not null, index |
| title | varchar(255) | not null |
| description | text | nullable |
| distance_km | decimal(8,2) | nullable |
| altitude | int | nullable |
| meals | varchar(100) | nullable |
| accommodation | varchar(255) | nullable |
| sort_order | int | default 0, index |
| timestamps + deleted_at | | |

⚠ `TourController@update` gọi `forceDelete()` toàn bộ rồi tạo lại → **mất lịch sử**. Khi có Activity Log cần ghi nhận đặc biệt.

---

### 4.6. `tour_destination` (pivot)

| Cột | Kiểu |
|---|---|
| tour_id | FK tours, cascade |
| destination_id | FK destinations, cascade |
| created_at / updated_at | |

PK tổng hợp `(tour_id, destination_id)`, index `destination_id`.

---

### 4.7. `posts`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| id | bigint | PK |
| user_id | bigint | FK users, **cascade delete**, index |
| category_id | bigint | FK categories, nullOnDelete, index |
| title | varchar(255) | not null |
| slug | varchar(255) | **unique** |
| excerpt | text | nullable |
| content | longText | nullable (HTML) |
| featured_image | varchar(255) | nullable |
| meta_title / meta_description | | nullable |
| is_featured | tinyint(1) | default 0, index |
| status | enum(`draft`,`published`) | default draft, index |
| published_at | timestamp | nullable, index |
| view_count | int unsigned | default 0 |
| timestamps + deleted_at | | |

**Quan hệ:** author (User), category, tags (n:n), media (morph).

---

### 4.8. `tags` và `post_tag`

`tags`: id, name(100), slug(100) unique, description, timestamps, deleted_at.

`post_tag`: post_id + tag_id (PK tổng hợp, cascade), timestamps, index tag_id.

---

### 4.9. `pages`

id, title, slug **unique**, content longText, featured_image, template(50) default `default`, meta_title, meta_description, status enum(draft/published), published_at, timestamps, deleted_at.

**Accessor:** `featured_image_url` (xử lý cả URL tuyệt đối).

---

### 4.10. `faqs`

id, category(100) nullable + index, question(500), answer text, sort_order, is_active, timestamps, deleted_at.

**Scope:** `active()`, `byCategory()`, `ordered()`.

---

### 4.11. `sliders`

id, title, subtitle, image(500) **not null**, link_url(500), link_target enum(_self/_blank), position(50) default `home_hero` + index, sort_order, is_active, start_at, end_at, timestamps, deleted_at.

**Scope `active()`:** `is_active=1` AND (`start_at` null OR ≤ now) AND (`end_at` null OR ≥ now).
**Accessor:** `image_url`.

---

### 4.12. `menus` và `menu_items`

`menus`: id, name, location(50) **unique**, description, is_active, timestamps, deleted_at.

`menu_items`:

| Cột | Kiểu |
|---|---|
| id | bigint PK |
| menu_id | FK menus, **cascade** |
| parent_id | FK menu_items self, nullOnDelete |
| label | varchar(255) |
| url | varchar(500) nullable |
| route_name | varchar(100) nullable |
| target | enum(_self,_blank) |
| sort_order | int |
| is_active | bool |
| timestamps + deleted_at | |

**Accessor `resolved_url`:** ưu tiên `route(route_name)` (có try/catch), fallback `url`, cuối cùng `#`.

**Ghi chú:** `MenuController@saveItems` xóa cứng toàn bộ item rồi tạo lại. Với cây > 2 cấp, thứ tự `uasort` chỉ đảm bảo "cha trước con" 1 cấp → **có thể sai quan hệ ở cấp 3** nếu đầu vào không theo thứ tự.

---

### 4.13. `media` (polymorphic)

| Cột | Kiểu | Ghi chú |
|---|---|---|
| id | bigint | PK |
| mediable_type | varchar(255) | nullable (cho phép orphan) |
| mediable_id | bigint unsigned | nullable |
| collection | varchar(100) | default `default`, index |
| file_name | varchar(255) | |
| file_path | varchar(500) | đường dẫn tương đối trên disk `public` |
| mime_type | varchar(100) | nullable |
| file_size | int unsigned | nullable (byte) |
| alt_text | varchar(255) | nullable (SEO/accessibility) |
| caption | varchar(255) | nullable |
| sort_order | int | index |
| timestamps + deleted_at | | |

Index `(mediable_type, mediable_id)`. **Không có FK** (đúng với polymorphic).

**Collection dùng trong code:** `default`, `featured`, `gallery`, `banner`, `content`.
**Bất thường dữ liệu:** bảng chỉ 2 dòng, đĩa 17 file; thiếu thư mục `media/destinations/` dù DB tham chiếu.

---

### 4.14. `contacts`

id, name, email, phone(20), subject, message text, status enum(`new`,`read`,`replied`,`archived`) + index, ip_address(45), user_agent(500), timestamps, deleted_at, index created_at.

**Hành vi:** xem chi tiết tự động chuyển `new` → `read`.

---

### 4.15. `reviews`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| id | bigint | PK |
| tour_id | bigint | FK tours, cascade |
| user_id | bigint | FK users, cascade |
| rating | tinyint unsigned | 1–5, default 5 |
| comment | text | nullable |
| status | enum(`pending`,`approved`,`rejected`) | default pending |
| timestamps + deleted_at | | |

**UNIQUE `(tour_id, user_id)`** — mỗi user 1 review/tour.
⚠ Unique không tính `deleted_at`: user xóa mềm review rồi **không thể đánh giá lại** (vi phạm unique khi insert).

---

### 4.16. `settings`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| id | bigint | PK |
| key | varchar(100) | **unique** |
| value | text | nullable |
| group | varchar(50) | default `general`, index |
| type | enum(`string`,`text`,`boolean`,`number`,`json`,`color`,`image`,`select`) | default string |
| description | varchar(255) | nullable |
| timestamps + deleted_at | | |

**Cơ chế cache:** toàn bộ bảng được cache vào 1 key `settings.all` (TTL 3600s) dưới dạng `['values' => [...], 'groups' => [...]]`. `Setting::get/set/getGroup/flushCache`.
**45 key hiện có**, 7 group: general, contact, social, seo, theme (2 màu + 12 field heading), about (9), hero (6 ảnh).

---

### 4.17. Bảng hệ thống

- `password_reset_tokens`: email PK, token, created_at.
- `failed_jobs`: id, uuid unique, connection, queue, payload, exception, failed_at.
- `personal_access_tokens`: Sanctum chuẩn.
- `jobs`: queue + index, payload, attempts, reserved_at, available_at, created_at.
- `migrations`: id, migration, batch.

---

## 5. RÀNG BUỘC & QUY TẮC TOÀN VẸN

| Quy tắc | Nơi áp dụng |
|---|---|
| Slug unique | categories, destinations, tours, posts, pages, tags |
| Slug tự sinh khi để trống | 6 controller admin (`generateSlug`, có xử lý trùng & `withTrashed`) |
| `published_at` tự set khi status = published | Tour, Post, Destination, Page |
| Xóa mềm mặc định | 15 model |
| Xóa cứng có kiểm tra liên kết | Tour, Post (tag), Destination, Category, Menu, Tag |
| Cascade delete | tour_itineraries, reviews, post_tag, tour_destination, menu_items (theo menu) |
| NullOnDelete | category_id (tours/posts), parent_id (categories/destinations/menu_items) |

**Điểm chưa nhất quán:**
1. `Post` chặn xóa cứng khi còn tag, trong khi `Tour` tự detach destination → trải nghiệm khác nhau.
2. `TourController@forceDestroy` báo `"Đã xóa vĩnh viễn tour \"{id}\""` — dùng ID thay vì tên (lỗi hiển thị).
3. `CategoryController@store` không set `published_at` (categories không có cột này — đúng).
4. `Destination` xóa cứng chặn nếu còn tour, nhưng `tours` là quan hệ n:n → có thể detach thay vì chặn (cân nhắc).

---

## 6. DỮ LIỆU & SAO LƯU

### 6.1. Dump hiện có

| File | Kích thước | Trạng thái |
|---|---:|---|
| `rungadventure.sql` | 72 KB | **CŨ** — 17/10/2026, chỉ 21 migration, **thiếu `jobs` + `reviews`** |
| `settings-dump.sql` | 8.8 KB | Chỉ bảng `settings` |

⚠ **Không dùng `rungadventure.sql` để khôi phục** — sẽ mất 2 bảng và migration record. Nếu cần backup, dump mới từ DB thật.

### 6.2. Quy tắc bắt buộc

1. **Cấm** `php artisan migrate:fresh`, `db:wipe`, `db:seed` trên DB thật.
2. Trước mọi migration mới: `mysqldump rungadventure > backup_YYYYMMDD_HHmm.sql`.
3. Migration mới **chỉ `Schema::create`** bảng mới. Nếu phải sửa bảng cũ (ví dụ thêm cột `last_seen_version` vào users) → migration riêng, có `down()` an toàn.
4. Không sửa trực tiếp dữ liệu bằng SQL tay; dùng admin UI hoặc tinker có log.
5. `DatabaseSeeder` dùng `firstOrCreate` nên **không ghi đè** dữ liệu, nhưng **không chạy seeder trên production** (chứa mật khẩu mặc định).

### 6.3. Seeder — cảnh báo bảo mật

`database/seeders/UserSeeder.php` tạo tài khoản với mật khẩu fallback **hardcode**:
```
ADMIN_PASSWORD (env, mặc định 'dung2005')
EDITOR_PASSWORD (env, mặc định 'dung2005')
```
Nếu seeder được chạy ở môi trường không có `.env` tương ứng → tài khoản admin với mật khẩu đã biết. **Không chạy `db:seed` trên production**; nếu buộc phải chạy, set `ADMIN_PASSWORD` mạnh trước.

---

## 7. QUY ƯỚC CHO BẢNG MỚI (GĐ4, GĐ5)

Mọi bảng mới phải tuân thủ:

```php
$table->id();
// ... cột nghiệp vụ
$table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
$table->timestamps();
$table->softDeletes();
// index cho cột dùng filter/sort
```

**Bắt buộc:**
- Có `down()` xóa bảng.
- Không đặt FK `cascadeOnDelete` cho `created_by`/`causer_id` (dùng `nullOnDelete` để không mất log).
- Cột enum dùng `enum` + default rõ ràng.
- JSON dùng `json` (MySQL 8 hỗ trợ).
- Tên bảng snake_case số nhiều, tên cột snake_case.

### 7.1. Bảng đề xuất GĐ4

```
versions
  id, version varchar(20) unique, title, summary text, body longText,
  type enum(major,minor,patch), status enum(draft,published),
  is_current bool, released_at timestamp, created_by FK nullOnDelete,
  timestamps, softDeletes

changelog_entries
  id, version_id FK cascade, category enum(added,changed,fixed,removed,security),
  description text, sort_order int, timestamps

version_user
  id, version_id FK cascade, user_id FK cascade, seen_at timestamp,
  unique(version_id, user_id)
```

### 7.2. Bảng đề xuất GĐ5

```
activity_logs
  id, event varchar(50), subject_type varchar(255) null, subject_id bigint null,
  causer_id FK users nullOnDelete, causer_name varchar(255), causer_email varchar(255),
  description text, properties json, batch_uuid char(36) null,
  ip_address varchar(45), user_agent varchar(500), created_at timestamp,
  index(subject_type, subject_id), index(causer_id, created_at),
  index(event, created_at), index(created_at)
```

**Quan trọng:** `activity_logs` **không dùng SoftDeletes** (log là bất biến) và **không có `updated_at`**.

---

## 8. TRUY VẤN KIỂM TRA NHANH

```sql
-- Kiểm tra migration đã chạy
SELECT migration, batch FROM migrations ORDER BY id DESC LIMIT 5;

-- Kiểm tra orphan media
SELECT COUNT(*) FROM media WHERE mediable_type IS NULL;

-- Kiểm tra file media thiếu
SELECT id, file_path FROM media
WHERE file_path IS NOT NULL AND file_path NOT LIKE '%destinations%';

-- Kiểm tra bài viết không có tác giả (không thể xảy ra do FK, nhưng nên kiểm)
SELECT COUNT(*) FROM posts p LEFT JOIN users u ON u.id = p.user_id WHERE u.id IS NULL;

-- Kiểm tra review trùng (phải = 0)
SELECT tour_id, user_id, COUNT(*) c FROM reviews
GROUP BY tour_id, user_id HAVING c > 1;

-- Kiểm tra settings thiếu so với config/settings.php (chạy tay)
SELECT `key`, `group`, `type` FROM settings ORDER BY `group`, `key`;
```

---

## 9. LỊCH SỬ MIGRATION

| Batch | Ngày | Migration |
|---|---|---|
| 1 | 09/10/2026 | 19 bảng nền (users → post_tag) |
| 2 | 09/10/2026 | menu_items |
| 3 | 13/10/2026 | update_settings_type_enum (thêm color/image/select) |
| 4 | 17/10/2026 | jobs |
| 5 | 19/10/2026 | reviews |

**Chưa có:** `job_batches` (dù `config/queue.php` khai báo) — nếu dùng `Bus::batch()` phải tạo migration trước.
