-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 17, 2026 at 04:16 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rungadventure`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `type` enum('tour','post') COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `type`, `parent_id`, `name`, `slug`, `description`, `image`, `meta_title`, `meta_description`, `sort_order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'tour', NULL, 'Trekking Đà Giang', 'trekking-da-giang', NULL, NULL, NULL, NULL, 0, 1, '2026-10-12 02:43:42', '2026-10-12 02:45:18', NULL),
(3, 'post', NULL, 'Tin tức', 'tin-tuc', NULL, NULL, NULL, NULL, 0, 1, '2026-10-12 03:19:35', '2026-10-12 03:19:35', NULL),
(4, 'tour', NULL, '[SAMPLE] Trekking', 'sample-trekking', 'Danh mục mẫu cho tour du lịch.', NULL, NULL, NULL, 1, 1, '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(5, 'tour', NULL, '[SAMPLE] Cắm trại', 'sample-cam-trai', 'Danh mục mẫu cho tour du lịch.', NULL, NULL, NULL, 2, 1, '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(6, 'tour', NULL, '[SAMPLE] Khám phá', 'sample-kham-pha', 'Danh mục mẫu cho tour du lịch.', NULL, NULL, NULL, 3, 1, '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(7, 'tour', NULL, '[SAMPLE] Team building', 'sample-team-building', 'Danh mục mẫu cho tour du lịch.', NULL, NULL, NULL, 4, 1, '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(8, 'tour', NULL, '[SAMPLE] Tour gia đình', 'sample-tour-gia-dinh', 'Danh mục mẫu cho tour du lịch.', NULL, NULL, NULL, 5, 1, '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(9, 'post', NULL, '[SAMPLE] Cẩm nang', 'sample-cam-nang', 'Danh mục mẫu cho bài viết.', NULL, NULL, NULL, 1, 1, '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(10, 'post', NULL, '[SAMPLE] Kinh nghiệm', 'sample-kinh-nghiem', 'Danh mục mẫu cho bài viết.', NULL, NULL, NULL, 2, 1, '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(11, 'post', NULL, '[SAMPLE] Điểm đến', 'sample-diem-den', 'Danh mục mẫu cho bài viết.', NULL, NULL, NULL, 3, 1, '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(12, 'post', NULL, '[SAMPLE] Tin tức', 'sample-tin-tuc', 'Danh mục mẫu cho bài viết.', NULL, NULL, NULL, 4, 1, '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('new','read','replied','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `ip_address`, `user_agent`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Nguyễn Văn A', 'a@example.com', '0123456789', 'Hỏi về tour Sapa', 'Tôi muốn hỏi về tour Sapa 3 ngày 2 đêm. Còn chỗ không?', 'new', '127.0.0.1', 'Mozilla/5.0', '2026-10-13 01:46:20', '2026-10-13 01:47:19', NULL),
(2, '[SAMPLE] Nguyễn Văn A', 'nguyenvana@example.com', '0901234567', 'Hỏi về tour Sapa', 'Tôi muốn hỏi về tour Sapa 3N2Đ. Còn chỗ không? Giá bao nhiêu?', 'read', '127.0.0.1', 'Seeder', '2026-10-13 20:56:16', '2026-10-16 03:45:08', NULL),
(3, '[SAMPLE] Trần Thị B', 'tranthib@example.com', '0907654321', 'Đặt tour cho nhóm 10 người', 'Tôi muốn đặt tour cho nhóm 10 người vào tháng 12. Có ưu đãi không?', 'read', '127.0.0.1', 'Seeder', '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(4, '[SAMPLE] Lê Văn C', 'levanc@example.com', NULL, 'Hợp tác', 'Tôi muốn hợp tác quảng cáo trên website. Vui lòng liên hệ lại.', 'replied', '127.0.0.1', 'Seeder', '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(5, '[SAMPLE] Phạm Thị D', 'phamthid@example.com', '0912345678', NULL, 'Câu hỏi mẫu không có tiêu đề.', 'new', '127.0.0.1', 'Seeder', '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(6, '[SAMPLE] Hoàng Văn E', 'hoangvane@example.com', '0987654321', 'Phản hồi về tour', 'Tôi đã đi tour Fansipan. Rất tuyệt vời, cảm ơn công ty!', 'archived', '127.0.0.1', 'Seeder', '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `altitude` int DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `parent_id`, `name`, `slug`, `description`, `content`, `image`, `region`, `province`, `latitude`, `longitude`, `altitude`, `meta_title`, `meta_description`, `is_featured`, `sort_order`, `status`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'Hòa Bình', 'hoa-binh', NULL, NULL, NULL, 'Miền Bắc', 'Hòa Bình', NULL, NULL, NULL, NULL, NULL, 0, 0, 'draft', '2026-10-12 09:58:00', '2026-10-12 02:58:21', '2026-10-12 02:58:49', NULL),
(2, NULL, '[SAMPLE] Miền Bắc', 'sample-mien-bac', 'Vùng du lịch mẫu.', NULL, NULL, 'Miền Bắc', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 'published', '2026-10-13 02:17:35', '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(3, 2, '[SAMPLE] Sapa', 'sample-sapa', 'Điểm đến mẫu. [SAMPLE] Sapa', NULL, NULL, 'Miền Bắc', 'Lào Cai', '22.3364000', '103.8438000', 1600, NULL, NULL, 1, 0, 'published', '2026-10-13 02:17:35', '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(4, 2, '[SAMPLE] Hà Giang', 'sample-ha-giang', 'Điểm đến mẫu. [SAMPLE] Hà Giang', NULL, NULL, 'Miền Bắc', 'Hà Giang', '22.8025000', '104.9784000', 1200, NULL, NULL, 1, 1, 'published', '2026-10-13 02:17:35', '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(5, 2, '[SAMPLE] Ninh Bình', 'sample-ninh-binh', 'Điểm đến mẫu. [SAMPLE] Ninh Bình', NULL, NULL, 'Miền Bắc', 'Ninh Bình', '20.2506000', '105.9745000', 100, NULL, NULL, 0, 2, 'published', '2026-10-13 02:17:35', '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(6, 2, '[SAMPLE] Fansipan', 'sample-fansipan', 'Điểm đến mẫu. [SAMPLE] Fansipan', NULL, NULL, 'Miền Bắc', 'Lào Cai', '22.3033000', '103.7750000', 3143, NULL, NULL, 0, 3, 'published', '2026-10-13 02:17:35', '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(7, NULL, '[SAMPLE] Miền Trung', 'sample-mien-trung', 'Vùng du lịch mẫu.', NULL, NULL, 'Miền Trung', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 'published', '2026-10-13 02:17:35', '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(8, 7, '[SAMPLE] Đà Nẵng', 'sample-da-nang', 'Điểm đến mẫu. [SAMPLE] Đà Nẵng', NULL, NULL, 'Miền Trung', 'Đà Nẵng', '16.0544000', '108.2022000', 10, NULL, NULL, 1, 0, 'published', '2026-10-13 02:17:35', '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(9, 7, '[SAMPLE] Hội An', 'sample-hoi-an', 'Điểm đến mẫu. [SAMPLE] Hội An', NULL, NULL, 'Miền Trung', 'Quảng Nam', '15.8801000', '108.3380000', 5, NULL, NULL, 1, 1, 'published', '2026-10-13 02:17:35', '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(10, 7, '[SAMPLE] Huế', 'sample-hue', 'Điểm đến mẫu. [SAMPLE] Huế', NULL, NULL, 'Miền Trung', 'Thừa Thiên Huế', '16.4637000', '107.5909000', 15, NULL, NULL, 0, 2, 'published', '2026-10-13 02:17:35', '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(11, NULL, '[SAMPLE] Miền Nam', 'sample-mien-nam', 'Vùng du lịch mẫu.', NULL, NULL, 'Miền Nam', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 'published', '2026-10-13 02:17:35', '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(12, 11, '[SAMPLE] Đà Lạt', 'sample-da-lat', 'Điểm đến mẫu. [SAMPLE] Đà Lạt', NULL, NULL, 'Miền Nam', 'Lâm Đồng', '11.9404000', '108.4583000', 1500, NULL, NULL, 1, 0, 'published', '2026-10-13 02:17:35', '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL),
(13, 11, '[SAMPLE] Phú Quốc', 'sample-phu-quoc', 'Điểm đến mẫu. [SAMPLE] Phú Quốc', NULL, NULL, 'Miền Nam', 'Kiên Giang', '10.2899000', '103.9840000', 50, NULL, NULL, 1, 1, 'published', '2026-10-13 02:17:35', '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `question` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `category`, `question`, `answer`, `sort_order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Đặt tour', '[SAMPLE] Làm sao để đặt tour?', 'Đây là câu trả lời mẫu. Quý khách vui lòng liên hệ hotline để được tư vấn.', 1, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(2, 'Đặt tour', '[SAMPLE] Tôi có thể hủy tour không?', 'Câu trả lời mẫu về chính sách hủy tour.', 2, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(3, 'Thanh toán', '[SAMPLE] Các hình thức thanh toán?', 'Chúng tôi chấp nhận thanh toán qua chuyển khoản và tiền mặt.', 3, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(4, 'Thanh toán', '[SAMPLE] Khi nào cần thanh toán?', 'Quý khách thanh toán trước 3 ngày khởi hành.', 4, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(5, 'Hành trình', '[SAMPLE] Tour có bao gồm ăn uống không?', 'Tùy tour, quý khách xem chi tiết trong phần lịch trình.', 5, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(6, 'Hành trình', '[SAMPLE] Cần mang theo gì khi đi tour?', 'Quý khách nên mang giày thể thao, áo khoác, và các vật dụng cá nhân.', 6, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(7, 'Hành trình', '[SAMPLE] Có hướng dẫn viên không?', 'Tất cả tour đều có hướng dẫn viên chuyên nghiệp.', 7, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(8, 'Khác', '[SAMPLE] Làm sao để liên hệ với chúng tôi?', 'Quý khách có thể gọi hotline hoặc gửi email qua trang Liên hệ.', 8, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(9, 'Khác', '[SAMPLE] Có tổ chức tour riêng không?', 'Có, chúng tôi nhận tổ chức tour riêng theo yêu cầu.', 9, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(10, 'Khác', '[SAMPLE] Có ưu đãi cho nhóm không?', 'Có, quý khách vui lòng liên hệ để được báo giá tốt nhất.', 10, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint UNSIGNED NOT NULL,
  `mediable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mediable_id` bigint UNSIGNED DEFAULT NULL,
  `collection` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int UNSIGNED DEFAULT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `location`, `description`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Menu 1', 'top', NULL, 1, '2026-10-12 22:18:22', '2026-10-13 00:30:41', NULL),
(2, 'Menu 2', 'header', NULL, 1, '2026-10-12 22:23:29', '2026-10-12 22:23:29', NULL),
(3, '[SAMPLE] Menu chính', 'main', 'Menu điều hướng chính', 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(4, '[SAMPLE] Menu footer', 'footer', 'Menu chân trang', 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint UNSIGNED NOT NULL,
  `menu_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` enum('_self','_blank') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `label`, `url`, `route_name`, `target`, `sort_order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 1, NULL, 'Bán phòng', '/ban-phong', 'ban-phong.index', '_self', 0, 1, '2026-10-13 00:30:08', '2026-10-13 00:30:08', NULL),
(9, 4, NULL, 'Giới thiệu', '/gioi-thieu', NULL, '_self', 1, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(10, 4, NULL, 'Liên hệ', '/lien-he', NULL, '_self', 2, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(11, 4, NULL, 'Câu hỏi thường gặp', '/faq', NULL, '_self', 3, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(12, 4, NULL, 'Điều khoản', '/trang-tinh/dieu-khoan', NULL, '_self', 4, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(13, 4, NULL, 'Chính sách bảo mật', '/trang-tinh/chinh-sach-bao-mat', NULL, '_self', 5, 1, '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(123, 3, NULL, 'Trang chủ', '/', NULL, '_self', 0, 1, '2026-10-16 03:18:32', '2026-10-16 03:18:32', NULL),
(124, 3, NULL, 'Hành trình', '/tour', NULL, '_self', 1, 1, '2026-10-16 03:18:32', '2026-10-16 03:18:32', NULL),
(125, 3, NULL, 'Liên hệ', '/lien-he', NULL, '_self', 4, 1, '2026-10-16 03:18:32', '2026-10-16 03:18:32', NULL),
(126, 3, NULL, 'Điểm đến', '/diem-den', NULL, '_self', 5, 1, '2026-10-16 03:18:32', '2026-10-16 03:18:32', NULL),
(127, 3, NULL, 'Tin tức', '/bai-viet', NULL, '_self', 6, 1, '2026-10-16 03:18:32', '2026-10-16 03:18:32', NULL),
(128, 3, NULL, 'Giới thiệu', '/gioi-thieu', NULL, '_self', 7, 1, '2026-10-16 03:18:32', '2026-10-16 03:18:32', NULL),
(129, 3, 124, 'Tour Trekking', '/tour/trekking', NULL, '_self', 2, 1, '2026-10-16 03:18:32', '2026-10-16 03:18:32', NULL),
(130, 3, 129, 'Trekking Đà Giang', '/tour/trekking-da-giang', NULL, '_self', 3, 1, '2026-10-16 03:18:32', '2026-10-16 03:18:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_10_09_094959_create_categories_table', 1),
(6, '2026_10_09_094959_create_destinations_table', 1),
(7, '2026_10_09_095000_create_media_table', 1),
(8, '2026_10_09_095000_create_tags_table', 1),
(9, '2026_10_09_095001_create_faqs_table', 1),
(10, '2026_10_09_095001_create_menus_table', 1),
(11, '2026_10_09_095002_create_settings_table', 1),
(12, '2026_10_09_095002_create_sliders_table', 1),
(13, '2026_10_09_095003_create_contacts_table', 1),
(14, '2026_10_09_095328_create_pages_table', 1),
(15, '2026_10_09_100313_create_tours_table', 1),
(16, '2026_10_09_100340_create_tour_itineraries_table', 1),
(17, '2026_10_09_100402_create_tour_destination_table', 1),
(18, '2026_10_09_100427_create_posts_table', 1),
(19, '2026_10_09_100452_create_post_tag_table', 1),
(20, '2026_10_09_101021_create_menu_items_table', 2),
(21, '2026_10_13_083822_update_settings_type_enum', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `featured_image`, `template`, `meta_title`, `meta_description`, `status`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Giới thiệu', 'gioi-thieu', 'Nguồn đối chiếu: thông tin dòng máy Inspiron, danh mục đời máy Dell và các thế hệ CPU. \r\n\r\nWikipedia\r\n+2\r\n\r\n\r\n\r\nQuan trọng: Năm ra mắt của mẫu máy không nhất thiết là năm chiếc máy cụ thể được sản xuất. Ví dụ, một chiếc Latitude 5591 có thể được sản xuất vào năm 2019 dù dòng máy ra mắt năm 2018.\r\nMuốn biết chính xác năm sản xuất của từng chiếc, bạn cần kiểm tra Service Tag ở đáy máy hoặc trong BIOS, sau đó tra thông tin bảo hành/cấu hình trên trang hỗ trợ Dell. Với laptop đã qua sử dụng, cũng nên kiểm tra pin, nhiệt độ và tình trạng linh kiện vì tuổi máy thực tế ảnh hưởng lớn đến độ bền.', 'media/pages/2026/10/DsnVjAgBhrEpcu37OVaO.webp', 'fullwidth', NULL, NULL, 'published', '2026-10-13 03:45:00', '2026-10-12 20:45:19', '2026-10-12 20:45:19', NULL),
(2, '[SAMPLE] Giới thiệu', 'sample-gioi-thieu', '<h2>Về Rung Adventure</h2><p>Đây là nội dung mẫu của trang giới thiệu. Vui lòng thay đổi nội dung thật.</p>', NULL, 'default', NULL, NULL, 'published', '2026-10-13 20:56:16', '2026-10-13 20:56:16', '2026-10-13 20:56:16', NULL),
(3, '[SAMPLE] Liên hệ', 'lien-he', '<h2>Liên hệ với chúng tôi</h2><p>Đây là nội dung mẫu của trang liên hệ.</p>', NULL, 'default', NULL, NULL, 'published', '2026-10-13 20:56:16', '2026-10-13 20:56:16', '2026-10-14 01:07:36', NULL),
(4, '[SAMPLE] Điều khoản sử dụng', 'dieu-khoan-su-dung', '<h2>Điều khoản sử dụng</h2><p>Nội dung mẫu của trang điều khoản.</p>', NULL, 'default', NULL, NULL, 'published', '2026-10-13 20:56:16', '2026-10-13 20:56:16', '2026-10-14 01:07:35', NULL),
(5, '[SAMPLE] Chính sách bảo mật', 'chinh-sach-bao-mat', '<h2>Chính sách bảo mật</h2><p>Nội dung mẫu của trang chính sách bảo mật.</p>', NULL, 'default', NULL, NULL, 'published', '2026-10-13 20:56:16', '2026-10-13 20:56:16', '2026-10-14 01:07:35', NULL),
(6, '[SAMPLE] Chính sách hoàn tiền', 'chinh-sach-hoan-tien', '<h2>Chính sách hoàn tiền</h2><p>Nội dung mẫu của trang chính sách hoàn tiền.</p>', NULL, 'default', NULL, NULL, 'published', '2026-10-13 20:56:16', '2026-10-13 20:56:16', '2026-10-14 01:07:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `view_count` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `category_id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `meta_title`, `meta_description`, `is_featured`, `status`, `published_at`, `view_count`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 3, 'Tin tức 1', 'tin-tuc-1', 'Hello', 'Hellooooooooo', NULL, NULL, NULL, 0, 'published', '2026-10-12 10:21:00', 0, '2026-10-12 03:21:09', '2026-10-12 03:21:30', NULL),
(2, 1, 10, '[SAMPLE] 10 điều cần biết trước khi trekking Sapa', 'sample-10-dieu-can-biet-truoc-khi-trekking-sapa', 'Những lưu ý quan trọng giúp chuyến trekking Sapa của bạn an toàn và trọn vẹn.', 'Nội dung chi tiết của bài viết mẫu. Đây là dữ liệu demo.', NULL, '[SAMPLE] 10 điều cần biết trước khi trekking Sapa', NULL, 1, 'published', '2026-10-13 02:19:31', 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(3, 1, 10, '[SAMPLE] Kinh nghiệm chinh phục Fansipan lần đầu', 'sample-kinh-nghiem-chinh-phuc-fansipan-lan-dau', 'Hướng dẫn chi tiết cho người lần đầu chinh phục đỉnh Fansipan.', 'Nội dung chi tiết của bài viết mẫu. Đây là dữ liệu demo.', NULL, '[SAMPLE] Kinh nghiệm chinh phục Fansipan lần đầu', NULL, 1, 'published', '2026-10-13 02:19:31', 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(4, 1, 11, '[SAMPLE] Hà Giang — mùa hoa tam giác mạch', 'sample-ha-giang-mua-hoa-tam-giac-mach', 'Khám phá Hà Giang mùa hoa tam giác mạch đẹp như tranh.', 'Nội dung chi tiết của bài viết mẫu. Đây là dữ liệu demo.', NULL, '[SAMPLE] Hà Giang — mùa hoa tam giác mạch', NULL, 1, 'published', '2026-10-13 02:19:31', 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(5, 1, 9, '[SAMPLE] Top 5 địa điểm cắm trại gần Hà Nội', 'sample-top-5-dia-diem-cam-trai-gan-ha-noi', 'Những địa điểm cắm trại lý tưởng cho cuối tuần.', 'Nội dung chi tiết của bài viết mẫu. Đây là dữ liệu demo.', NULL, '[SAMPLE] Top 5 địa điểm cắm trại gần Hà Nội', NULL, 0, 'published', '2026-10-13 02:19:31', 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(6, 1, 9, '[SAMPLE] Ẩm thực đường phố Đà Lạt', 'sample-am-thuc-duong-pho-da-lat', 'Khám phá ẩm thực đường phố Đà Lạt — ngon, rẻ, đặc sắc.', 'Nội dung chi tiết của bài viết mẫu. Đây là dữ liệu demo.', NULL, '[SAMPLE] Ẩm thực đường phố Đà Lạt', NULL, 0, 'published', '2026-10-13 02:19:31', 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(7, 1, 9, '[SAMPLE] Văn hóa dân tộc vùng cao phía Bắc', 'sample-van-hoa-dan-toc-vung-cao-phia-bac', 'Tìm hiểu văn hóa các dân tộc thiểu số vùng cao phía Bắc.', 'Nội dung chi tiết của bài viết mẫu. Đây là dữ liệu demo.', NULL, '[SAMPLE] Văn hóa dân tộc vùng cao phía Bắc', NULL, 0, 'published', '2026-10-13 02:19:31', 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(8, 1, 9, '[SAMPLE] Nhiếp ảnh phong cảnh — bí quyết chụp đẹp', 'sample-nhiep-anh-phong-canh-bi-quyet-chup-dep', 'Chia sẻ bí quyết chụp ảnh phong cảnh đẹp cho người mới.', 'Nội dung chi tiết của bài viết mẫu. Đây là dữ liệu demo.', NULL, '[SAMPLE] Nhiếp ảnh phong cảnh — bí quyết chụp đẹp', NULL, 0, 'published', '2026-10-13 02:19:31', 1, '2026-10-13 02:19:31', '2026-10-14 00:51:37', NULL),
(9, 1, 12, '[SAMPLE] Tin tức: Rung Adventure ra mắt website mới', 'sample-tin-tuc-rung-adventure-ra-mat-website-moi', 'Chúng tôi vừa ra mắt website mới với nhiều tính năng hiện đại.', 'Nội dung chi tiết của bài viết mẫu. Đây là dữ liệu demo.', NULL, '[SAMPLE] Tin tức: Rung Adventure ra mắt website mới', NULL, 0, 'published', '2026-10-13 02:19:31', 1, '2026-10-13 02:19:31', '2026-10-14 01:50:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post_tag`
--

CREATE TABLE `post_tag` (
  `post_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_tag`
--

INSERT INTO `post_tag` (`post_id`, `tag_id`, `created_at`, `updated_at`) VALUES
(2, 3, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(2, 5, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(2, 8, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(3, 3, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(3, 8, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(4, 6, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(4, 12, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(5, 4, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(5, 9, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(6, 7, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(6, 10, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(7, 9, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(7, 11, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(8, 9, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(8, 12, '2026-10-13 02:19:31', '2026-10-13 02:19:31');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `type` enum('string','text','boolean','number','json','color','image','select') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(34, 'site_name', 'Rừng Adventure', 'general', 'string', 'Tên website', '2026-10-13 22:01:08', '2026-10-14 02:42:34', NULL),
(35, 'site_description', 'Khám phá những hành trình trekking tuyệt vời.', 'general', 'text', 'Mô tả ngắn', '2026-10-13 22:01:08', '2026-10-13 22:01:08', NULL),
(36, 'site_logo', NULL, 'general', 'string', 'Logo', '2026-10-13 22:01:08', '2026-10-13 22:01:08', NULL),
(37, 'site_favicon', NULL, 'general', 'string', 'Favicon', '2026-10-13 22:01:08', '2026-10-13 22:01:08', NULL),
(38, 'contact_email', 'rungadventure@gmail.com', 'contact', 'string', 'Email', '2026-10-13 22:01:08', '2026-10-14 02:43:27', NULL),
(39, 'contact_phone', '0376 344 375', 'contact', 'string', 'Số điện thoại', '2026-10-13 22:01:08', '2026-10-14 02:43:27', NULL),
(40, 'contact_address', 'Số 627 Vũ Tông Phan, Khương Đình, Thanh Xuân, Hà Nội', 'contact', 'string', 'Địa chỉ', '2026-10-13 22:01:08', '2026-10-14 02:43:27', NULL),
(41, 'social_facebook', 'https://www.facebook.com/rungadventure', 'social', 'string', 'Facebook', '2026-10-13 22:01:08', '2026-10-14 02:47:09', NULL),
(42, 'social_instagram', 'https://www.instagram.com/rungadventure', 'social', 'string', 'Instagram', '2026-10-13 22:01:08', '2026-10-14 02:47:09', NULL),
(43, 'social_youtube', NULL, 'social', 'string', 'YouTube', '2026-10-13 22:01:08', '2026-10-13 22:01:08', NULL),
(44, 'social_tiktok', 'https://www.tiktok.com/@rungadventure', 'social', 'string', 'TikTok', '2026-10-13 22:01:08', '2026-10-14 02:47:09', NULL),
(45, 'seo_default_title', 'Rung Adventure — Khám phá Việt Nam', 'seo', 'string', 'Meta title mặc định', '2026-10-13 22:01:08', '2026-10-13 22:01:08', NULL),
(46, 'seo_default_description', 'Khám phá những hành trình trekking tuyệt vời cùng Rung Adventure.', 'seo', 'text', 'Meta description mặc định', '2026-10-13 22:01:08', '2026-10-13 22:01:08', NULL),
(47, 'seo_keywords', NULL, 'seo', 'string', 'Từ khóa SEO', '2026-10-13 22:01:08', '2026-10-13 22:01:08', NULL),
(48, 'google_analytics_id', NULL, 'seo', 'string', 'Google Analytics ID', '2026-10-13 22:01:08', '2026-10-13 22:01:08', NULL),
(49, 'theme_primary_color', '#307a55', 'theme', 'color', 'Màu chính', '2026-10-13 22:01:08', '2026-10-13 22:05:57', NULL),
(50, 'theme_secondary_color', '#f97316', 'theme', 'color', 'Màu phụ', '2026-10-13 22:01:08', '2026-10-13 22:05:57', NULL),
(51, 'theme_h1_color', '#307a55', 'theme', 'color', 'H1 — Màu chữ', '2026-10-13 22:01:08', '2026-10-13 22:30:34', NULL),
(52, 'theme_h1_font', 'Plus Jakarta Sans', 'theme', 'select', 'H1 — Font chữ', '2026-10-13 22:01:08', '2026-10-13 22:05:57', NULL),
(53, 'theme_h2_color', '#307a55', 'theme', 'color', 'H2 — Màu chữ', '2026-10-13 22:01:08', '2026-10-14 00:53:15', NULL),
(54, 'theme_h2_font', 'Plus Jakarta Sans', 'theme', 'select', 'H2 — Font chữ', '2026-10-13 22:01:08', '2026-10-13 22:05:57', NULL),
(55, 'theme_h3_color', '#0f172a', 'theme', 'color', 'H3 — Màu chữ', '2026-10-13 22:01:08', '2026-10-13 22:05:57', NULL),
(56, 'theme_h3_font', 'Plus Jakarta Sans', 'theme', 'select', 'H3 — Font chữ', '2026-10-13 22:01:08', '2026-10-13 22:05:57', NULL),
(57, 'theme_h4_color', '#0f172a', 'theme', 'color', 'H4 — Màu chữ', '2026-10-13 22:01:09', '2026-10-13 22:05:57', NULL),
(58, 'theme_h4_font', 'Plus Jakarta Sans', 'theme', 'select', 'H4 — Font chữ', '2026-10-13 22:01:09', '2026-10-13 22:05:57', NULL),
(59, 'theme_h5_color', '#0f172a', 'theme', 'color', 'H5 — Màu chữ', '2026-10-13 22:01:09', '2026-10-13 22:05:57', NULL),
(60, 'theme_h5_font', 'Plus Jakarta Sans', 'theme', 'select', 'H5 — Font chữ', '2026-10-13 22:01:09', '2026-10-13 22:05:57', NULL),
(61, 'theme_h6_color', '#0f172a', 'theme', 'color', 'H6 — Màu chữ', '2026-10-13 22:01:09', '2026-10-13 22:05:57', NULL),
(62, 'theme_h6_font', 'Plus Jakarta Sans', 'theme', 'select', 'H6 — Font chữ', '2026-10-13 22:01:09', '2026-10-13 22:05:57', NULL),
(63, 'about_hero_title', 'Về Rừng Adventure', 'about', 'string', 'Hero — Tiêu đề', '2026-10-14 01:15:57', '2026-10-14 01:15:57', NULL),
(64, 'about_hero_subtitle', 'Hành trình khám phá thiên nhiên Việt Nam', 'about', 'text', 'Hero — Phụ đề', '2026-10-14 01:15:57', '2026-10-14 01:15:57', NULL),
(65, 'about_story_title', 'Câu chuyện của chúng tôi', 'about', 'string', 'Câu chuyện — Tiêu đề', '2026-10-14 01:15:57', '2026-10-14 01:15:57', NULL),
(66, 'about_mission_title', 'Sứ mệnh', 'about', 'string', 'Sứ mệnh — Tiêu đề', '2026-10-14 01:15:57', '2026-10-14 01:15:57', NULL),
(67, 'about_mission_content', 'Mang đến những trải nghiệm du lịch bền vững, tôn trọng thiên nhiên và văn hóa địa phương.', 'about', 'text', 'Sứ mệnh — Nội dung', '2026-10-14 01:15:57', '2026-10-14 01:15:57', NULL),
(68, 'about_vision_title', 'Tầm nhìn', 'about', 'string', 'Tầm nhìn — Tiêu đề', '2026-10-14 01:15:57', '2026-10-14 01:15:57', NULL),
(69, 'about_vision_content', 'Trở thành đơn vị tổ chức du lịch trekking hàng đầu Việt Nam vào năm 2030.', 'about', 'text', 'Tầm nhìn — Nội dung', '2026-10-14 01:15:57', '2026-10-14 01:15:57', NULL),
(70, 'about_values_title', 'Giá trị cốt lõi', 'about', 'string', 'Giá trị cốt lõi — Tiêu đề', '2026-10-14 01:15:57', '2026-10-14 01:15:57', NULL),
(71, 'about_values_content', 'An toàn — Bền vững — Tận tâm — Chuyên nghiệp', 'about', 'text', 'Giá trị cốt lõi — Nội dung', '2026-10-14 01:15:57', '2026-10-14 01:15:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_target` enum('_self','_blank') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `position` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'home_hero',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `start_at` timestamp NULL DEFAULT NULL,
  `end_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `title`, `subtitle`, `image`, `link_url`, `link_target`, `position`, `sort_order`, `is_active`, `start_at`, `end_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Rừng Adventure', 'Rừng Adventure 2', 'media/sliders/2026/10/IkN6TH0qEoCWs7g9La0P.webp', '/admin/tour', '_blank', 'home_hero', 0, 1, NULL, NULL, '2026-10-12 20:37:16', '2026-10-12 20:37:16', NULL),
(2, '[SAMPLE] Khám phá Sapa', 'Hành trình trekking 3N2Đ', 'https://via.placeholder.com/1920x800/0ea5e9/ffffff?text=Slider+1', '/tour', '_self', 'home_hero', 1, 1, NULL, NULL, '2026-10-13 20:56:16', '2026-10-14 03:11:29', '2026-10-14 03:11:29'),
(3, '[SAMPLE] Chinh phục Fansipan', 'Mái nhà Đông Dương 3143m', 'https://via.placeholder.com/1920x800/f97316/ffffff?text=Slider+2', '/tour', '_self', 'home_hero', 2, 1, NULL, NULL, '2026-10-13 20:56:16', '2026-10-14 03:11:27', '2026-10-14 03:11:27'),
(4, '[SAMPLE] Hà Giang Loop', 'Cung đường đẹp nhất Việt Nam', 'https://via.placeholder.com/1920x800/16a34a/ffffff?text=Slider+3', '/tour', '_self', 'home_hero', 3, 1, NULL, NULL, '2026-10-13 20:56:16', '2026-10-14 03:11:24', '2026-10-14 03:11:24'),
(5, '[SAMPLE] Cắm trại Đà Lạt', 'Trải nghiệm thiên nhiên', 'https://via.placeholder.com/1920x800/7c3aed/ffffff?text=Slider+4', '/tour', '_self', 'home_hero', 4, 1, NULL, NULL, '2026-10-13 20:56:16', '2026-10-14 03:11:20', '2026-10-14 03:11:20');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'trekking', 'trekking', NULL, '2026-10-12 03:29:35', '2026-10-12 03:29:56', NULL),
(2, 'camping', 'camping', NULL, '2026-10-12 03:29:45', '2026-10-12 03:29:45', NULL),
(3, '[SAMPLE] Trekking', 'sample-trekking', 'Tag mẫu cho bài viết.', '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(4, '[SAMPLE] Cắm trại', 'sample-cam-trai', 'Tag mẫu cho bài viết.', '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(5, '[SAMPLE] Sapa', 'sample-sapa', 'Tag mẫu cho bài viết.', '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(6, '[SAMPLE] Hà Giang', 'sample-ha-giang', 'Tag mẫu cho bài viết.', '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(7, '[SAMPLE] Đà Lạt', 'sample-da-lat', 'Tag mẫu cho bài viết.', '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(8, '[SAMPLE] Kinh nghiệm', 'sample-kinh-nghiem', 'Tag mẫu cho bài viết.', '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(9, '[SAMPLE] Cẩm nang', 'sample-cam-nang', 'Tag mẫu cho bài viết.', '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(10, '[SAMPLE] Ẩm thực', 'sample-am-thuc', 'Tag mẫu cho bài viết.', '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(11, '[SAMPLE] Văn hóa', 'sample-van-hoa', 'Tag mẫu cho bài viết.', '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(12, '[SAMPLE] Nhiếp ảnh', 'sample-nhiep-anh', 'Tag mẫu cho bài viết.', '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_days` int DEFAULT NULL,
  `duration_nights` int DEFAULT NULL,
  `distance_km` decimal(8,2) DEFAULT NULL,
  `altitude_min` int DEFAULT NULL,
  `altitude_max` int DEFAULT NULL,
  `difficulty` enum('easy','moderate','hard','expert') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_reference` bigint UNSIGNED DEFAULT NULL,
  `start_point` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_point` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `best_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `view_count` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`id`, `category_id`, `name`, `slug`, `excerpt`, `content`, `featured_image`, `duration_days`, `duration_nights`, `distance_km`, `altitude_min`, `altitude_max`, `difficulty`, `price_reference`, `start_point`, `end_point`, `best_time`, `notes`, `meta_title`, `meta_description`, `is_featured`, `status`, `published_at`, `sort_order`, `view_count`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'Trekking Đà Giang', 'trekking-da-giang', NULL, NULL, 'media/tours/2026/10/JY0Q9pygxQB54ca2fC9L.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'draft', NULL, 0, 0, '2026-10-12 03:06:56', '2026-10-12 03:09:29', NULL),
(2, 4, '[SAMPLE] Trekking Sapa 3N2Đ', 'sample-trekking-sapa-3n2d', 'Hành trình trekking khám phá Sapa với những thửa ruộng bậc thang tuyệt đẹp.', 'Nội dung chi tiết của tour mẫu. Đây là dữ liệu demo.', NULL, 3, 2, '45.00', 200, 1800, 'moderate', 3500000, 'Hà Nội', 'Sapa', 'Tháng 9 - Tháng 11', 'Lưu ý: Đây là dữ liệu mẫu, không phải tour thật.', NULL, NULL, 1, 'published', '2026-10-13 02:19:31', 0, 2, '2026-10-13 02:19:31', '2026-10-14 03:47:50', NULL),
(3, 4, '[SAMPLE] Chinh phục Fansipan 4N3Đ', 'sample-chinh-phuc-fansipan-4n3d', 'Chinh phục đỉnh Fansipan — mái nhà Đông Dương.', 'Nội dung chi tiết của tour mẫu. Đây là dữ liệu demo.', NULL, 4, 3, '60.00', 300, 3143, 'hard', 5500000, 'Hà Nội', 'Fansipan', 'Tháng 10 - Tháng 3', 'Lưu ý: Đây là dữ liệu mẫu, không phải tour thật.', NULL, NULL, 1, 'published', '2026-10-13 02:19:31', 0, 1, '2026-10-13 02:19:31', '2026-10-14 03:01:59', NULL),
(4, 6, '[SAMPLE] Hà Giang Loop 3N2Đ', 'sample-ha-giang-loop-3n2d', 'Khám phá Hà Giang — mảnh đất địa đầu Tổ quốc.', 'Nội dung chi tiết của tour mẫu. Đây là dữ liệu demo.', NULL, 3, 2, '350.00', 200, 1500, 'moderate', 4200000, 'Hà Giang', 'Hà Giang', 'Tháng 10 - Tháng 11', 'Lưu ý: Đây là dữ liệu mẫu, không phải tour thật.', NULL, NULL, 1, 'published', '2026-10-13 02:19:31', 0, 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(5, 5, '[SAMPLE] Cắm trại Đà Lạt 2N1Đ', 'sample-cam-trai-da-lat-2n1d', 'Cắm trại bên hồ, đốt lửa trại, ngắm sao trời.', 'Nội dung chi tiết của tour mẫu. Đây là dữ liệu demo.', NULL, 2, 1, '15.00', 1400, 1600, 'easy', 1500000, 'Đà Lạt', 'Đà Lạt', 'Quanh năm', 'Lưu ý: Đây là dữ liệu mẫu, không phải tour thật.', NULL, NULL, 0, 'published', '2026-10-13 02:19:31', 0, 1, '2026-10-13 02:19:31', '2026-10-14 00:19:43', NULL),
(6, 6, '[SAMPLE] Khám phá Ninh Bình 2N1Đ', 'sample-kham-pha-ninh-binh-2n1d', 'Tràng An, Tam Cốc, Hang Múa — vẻ đẹp non nước hữu tình.', 'Nội dung chi tiết của tour mẫu. Đây là dữ liệu demo.', NULL, 2, 1, '20.00', 50, 500, 'easy', 1800000, 'Hà Nội', 'Ninh Bình', 'Tháng 2 - Tháng 5', 'Lưu ý: Đây là dữ liệu mẫu, không phải tour thật.', NULL, NULL, 0, 'published', '2026-10-13 02:19:31', 0, 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(7, 4, '[SAMPLE] Trekking Tà Năng 2N1Đ', 'sample-trekking-ta-nang-2n1d', 'Cung trekking đẹp nhất Việt Nam — Tà Năng - Phan Dũng.', 'Nội dung chi tiết của tour mẫu. Đây là dữ liệu demo.', NULL, 2, 1, '25.00', 500, 1300, 'moderate', 2200000, 'Đà Lạt', 'Phan Dũng', 'Tháng 12 - Tháng 3', 'Lưu ý: Đây là dữ liệu mẫu, không phải tour thật.', NULL, NULL, 1, 'published', '2026-10-13 02:19:31', 0, 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(8, 5, '[SAMPLE] Cắm trại Ba Vì 2N1Đ', 'sample-cam-trai-ba-vi-2n1d', 'Cắm trại cuối tuần gần Hà Nội.', 'Nội dung chi tiết của tour mẫu. Đây là dữ liệu demo.', NULL, 2, 1, '10.00', 100, 1100, 'easy', 1200000, 'Hà Nội', 'Ba Vì', 'Quanh năm', 'Lưu ý: Đây là dữ liệu mẫu, không phải tour thật.', NULL, NULL, 0, 'published', '2026-10-13 02:19:31', 0, 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(9, 5, 'TEST 01', 'test-01', 'Đây là đoạn văn mô tả ngắn', 'VÌ SAO RỪNG ADVENTURE CHỌN GIÁ TRỊ KẾT NỐI?\r\nCó lẽ bạn chưa biết, đằng sau những chuyến đi, những hành trình khám phá và những trải nghiệm mà Rừng Adventure mang đến, có một giá trị cốt lõi mà Rừng luôn tâm đắc, đó chính là GIÁ TRỊ KẾT NỐI.\r\nVậy tại sao lại là kết nối?\r\nChúng mình đang sống trong một thời đại mà cuộc sống ngày càng hiện đại, tiện nghi và phát triển. Công nghệ mang đến cho con người rất nhiều giá trị tốt đẹp, giúp chúng mình dễ dàng liên lạc và chia sẻ với nhau hơn. Nhưng bên cạnh đó, đôi khi chúng mình cũng vô tình xa rời thiên nhiên, ít dành thời gian cho những cuộc gặp gỡ trực tiếp và những kết nối thực sự.\r\nChúng mình có thể trò chuyện với rất nhiều người qua mạng xã hội, cập nhật cuộc sống của nhau mỗi ngày, nhưng liệu chúng mình có thực sự hiểu nhau hơn?\r\nĐã bao lâu rồi chúng mình chưa ngồi xuống, lắng nghe một người bằng tất cả sự hiện diện của mình? Đã bao lâu rồi chúng mình chưa dành thời gian hòa mình vào thiên nhiên, cảm nhận một cơn gió, lắng nghe tiếng chim hay đơn giản là để tâm trí được nghỉ ngơi?\r\nVà bạn có nhận thấy rằng đôi khi, giữa một thế giới có quá nhiều cách để liên lạc, chúng mình vẫn cảm thấy cô đơn, lạc lõng, dễ nóng nảy và dường như ít có người thực sự để sẻ chia?\r\nRừng không cho rằng đó là câu chuyện của riêng ai. Có lẽ, ở một thời điểm nào đó, mỗi chúng mình đều từng trải qua những cảm giác như vậy.\r\nRừng hiểu điều đó. Và cũng chính từ những trăn trở ấy, Rừng Adventure lựa chọn KẾT NỐI làm một trong những giá trị cốt lõi của mình.\r\nVới Rừng, những chuyến đi không chỉ đơn thuần là đưa mọi người đến một vùng đất mới, chinh phục một cung đường hay khám phá một cánh rừng. Điều Rừng thực sự mong muốn là tạo ra những cơ hội để chúng mình kết nối lại với những điều đôi khi đã vô tình bỏ quên.\r\n🌳 KẾT NỐI VỚI THIÊN NHIÊN\r\nĐể chúng mình biết sống chậm lại, lắng nghe nhiều hơn và cảm nhận sâu sắc hơn vẻ đẹp của tự nhiên. Để hiểu rằng con người không đứng ngoài thiên nhiên, mà chính là một phần của thiên nhiên. Khi hiểu và yêu thiên nhiên nhiều hơn, chúng mình cũng sẽ có thêm động lực để trân trọng, gìn giữ và bảo vệ những giá trị mà thiên nhiên ban tặng.\r\n🤝 KẾT NỐI CON NGƯỜI VỚI CON NGƯỜI\r\nĐể chúng mình có cơ hội gặp gỡ, sẻ chia và lắng nghe nhau ngoài những màn hình. Để những người xa lạ có thể trở thành bạn bè, những người đồng hành có thể trở thành tri kỷ, và mỗi người đều có cơ hội được là chính mình, được đón nhận và thấu hiểu.\r\nRừng mong rằng, qua mỗi hành trình, chúng mình không chỉ mang về những bức ảnh đẹp hay những kỷ niệm đáng nhớ, mà còn tìm thấy những mối quan hệ chân thành, những người bạn cùng chung niềm tin và những giá trị sống tích cực.\r\n🏡 KẾT NỐI VỚI VĂN HÓA BẢN ĐỊA VÀ LỊCH SỬ\r\nĐể mỗi chuyến đi không chỉ dừng lại ở việc ngắm nhìn cảnh đẹp, mà còn mở ra cơ hội tìm hiểu những câu chuyện của vùng đất, con người, phong tục và những giá trị văn hóa được gìn giữ qua nhiều thế hệ.\r\nĐể chúng mình hiểu hơn về lịch sử, trân trọng những nét đẹp bản địa và biết rằng đằng sau mỗi vùng đất là những câu chuyện, những con người và những giá trị đáng được lắng nghe, gìn giữ và tiếp nối.\r\n💚 VÀ SÂU XA HƠN, ĐÓ CÒN LÀ KẾT NỐI VỚI CHÍNH MÌNH.\r\nKhi tạm rời xa nhịp sống quen thuộc, bước vào thiên nhiên và gặp gỡ những con người mới, có lẽ chúng mình cũng có thêm cơ hội để nhìn lại bản thân, hiểu mình hơn và nhận ra điều gì thực sự có ý nghĩa trong cuộc sống.\r\nĐôi khi, một chuyến đi không khiến chúng mình trở thành một con người hoàn toàn khác, nhưng có thể giúp chúng mình nhìn mọi thứ bằng một góc nhìn khác, bao dung hơn với người khác và cả với chính mình.\r\nVới Rừng, kết nối không có nghĩa là tất cả chúng mình phải giống nhau, phải có cùng suy nghĩ hay cùng một cách sống.\r\nMỗi người đều có những câu chuyện riêng, những nỗi sợ, những điểm mạnh và cả những điều chưa hoàn thiện. Chúng mình có thể khác nhau về xuất phát điểm, quan điểm và cách nhìn nhận cuộc sống.\r\nNhưng nếu mỗi người sẵn lòng bỏ qua những so sánh, giảm bớt những định kiến và tạm gác lại những phán xét, liệu chúng mình có thể nhìn thấy nhiều hơn những điểm chung, những điều tốt đẹp ở nhau hay không?\r\nLiệu chúng mình có thể dành cho nhau thêm một chút kiên nhẫn, một chút lắng nghe và một chút bao dung để hiểu rằng ai cũng đang trên hành trình hoàn thiện chính mình?\r\nRừng tin rằng, khi chúng mình thực sự kết nối với nhau bằng sự chân thành, chúng mình có thể cùng tạo nên những mối quan hệ tốt đẹp hơn, một cộng đồng tích cực hơn và một cuộc sống ý nghĩa, hạnh phúc hơn.\r\nĐó cũng chính là điều Rừng mong muốn vun đắp thông qua mỗi hành trình và từng hoạt động của mình.', 'media/tours/2026/10/EJdJYWx8Sd4s5ZLUQKNi.webp', 1, 0, '3.00', 300, 600, 'moderate', 799000, 'Hà Nội', 'Hòa Bình', '10', NULL, NULL, NULL, 1, 'published', '2026-10-14 10:28:00', 0, 0, '2026-10-14 03:29:11', '2026-10-14 03:48:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tour_destination`
--

CREATE TABLE `tour_destination` (
  `tour_id` bigint UNSIGNED NOT NULL,
  `destination_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tour_destination`
--

INSERT INTO `tour_destination` (`tour_id`, `destination_id`, `created_at`, `updated_at`) VALUES
(2, 3, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(3, 3, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(3, 6, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(4, 4, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(5, 12, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(7, 12, '2026-10-13 02:19:31', '2026-10-13 02:19:31'),
(9, 5, '2026-10-14 03:29:11', '2026-10-14 03:29:11');

-- --------------------------------------------------------

--
-- Table structure for table `tour_itineraries`
--

CREATE TABLE `tour_itineraries` (
  `id` bigint UNSIGNED NOT NULL,
  `tour_id` bigint UNSIGNED NOT NULL,
  `day_number` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `distance_km` decimal(8,2) DEFAULT NULL,
  `altitude` int DEFAULT NULL,
  `meals` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accommodation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tour_itineraries`
--

INSERT INTO `tour_itineraries` (`id`, `tour_id`, `day_number`, `title`, `description`, `distance_km`, `altitude`, `meals`, `accommodation`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 1, 'Hà Nội → Sapa', 'Di chuyển từ Hà Nội đến Sapa, nghỉ ngơi tại homestay.', '0.00', 1600, NULL, NULL, 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(2, 2, 2, 'Trekking bản làng', 'Trekking qua các bản làng dân tộc, tham quan ruộng bậc thang.', '25.00', 1800, NULL, NULL, 1, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(3, 2, 3, 'Sapa → Hà Nội', 'Tham quan chợ địa phương, di chuyển về Hà Nội.', '20.00', 200, NULL, NULL, 2, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(4, 3, 1, 'Hà Nội → Lào Cai', 'Tàu đêm Hà Nội - Lào Cai.', '0.00', 50, NULL, NULL, 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(5, 3, 2, 'Bắt đầu leo Fansipan', 'Trekking từ chân núi lên trạm nghỉ 2200m.', '20.00', 2200, NULL, NULL, 1, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(6, 3, 3, 'Chinh phục đỉnh', 'Lên đỉnh Fansipan 3143m, ngắm bình minh.', '15.00', 3143, NULL, NULL, 2, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(7, 3, 4, 'Xuống núi → Hà Nội', 'Xuống núi, về Hà Nội.', '25.00', 50, NULL, NULL, 3, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(8, 4, 1, 'Hà Giang → Đồng Văn', 'Khởi hành, tham quan các cung đường đèo.', '120.00', 1500, NULL, NULL, 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(9, 4, 2, 'Đồng Văn → Mèo Vạc', 'Chinh phục Mã Pí Lèng, sông Nho Quế.', '130.00', 1000, NULL, NULL, 1, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(10, 4, 3, 'Mèo Vạc → Hà Giang', 'Về lại Hà Giang, kết thúc hành trình.', '100.00', 200, NULL, NULL, 2, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(11, 5, 1, 'Đà Lạt → Hồ Tuyền Lâm', 'Di chuyển đến hồ, dựng trại, đốt lửa trại.', '10.00', 1500, NULL, NULL, 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(12, 5, 2, 'Hồ Tuyền Lâm → Đà Lạt', 'Trekking nhẹ, tham quan thác, về Đà Lạt.', '5.00', 1400, NULL, NULL, 1, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(13, 6, 1, 'Hà Nội → Ninh Bình', 'Tham quan Tràng An, Hang Múa.', '10.00', 500, NULL, NULL, 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(14, 6, 2, 'Tam Cốc → Hà Nội', 'Đi thuyền Tam Cốc, về Hà Nội.', '10.00', 50, NULL, NULL, 1, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(15, 7, 1, 'Khởi hành trekking', 'Trekking qua đồi cỏ, cắm trại giữa rừng.', '15.00', 1300, NULL, NULL, 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(16, 7, 2, 'Về Phan Dũng', 'Trekking xuống Phan Dũng, kết thúc.', '10.00', 500, NULL, NULL, 1, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(17, 8, 1, 'Hà Nội → Ba Vì', 'Dựng trại, đốt lửa, BBQ.', '5.00', 1100, NULL, NULL, 0, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL),
(18, 8, 2, 'Ba Vì → Hà Nội', 'Tham quan vườn quốc gia, về Hà Nội.', '5.00', 100, NULL, NULL, 1, '2026-10-13 02:19:31', '2026-10-13 02:19:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','editor','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'editor',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `avatar`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dũng', 'nguyendanhdung479@gmail.com', NULL, '$2y$12$Syrhc0uFr0LNduHJVtMpVerOOPPNhkjBcsD2a299DaDV1RKorJvca', 'admin', 1, NULL, NULL, '2026-10-13 08:04:08', '2026-10-13 01:08:41', NULL),
(2, 'Nguyễn Văn A', 'admin@dulich.com', NULL, '$2y$12$FfVzpeyWoY2TgJwxEVS9suSjgOJvHOLtgiWf1WE6cMyZDPQlFZ4tC', 'user', 1, NULL, NULL, '2026-10-13 01:18:27', '2026-10-13 02:06:33', NULL),
(3, '[SAMPLE] Editor', 'rungadventure@gmail.com', '2026-10-13 02:17:35', '$2y$12$/LYkFdglEK0z.Rm9LV.CmuU9ZUio7dyVy5U32WJNmiytNEUF9Icui', 'editor', 1, NULL, NULL, '2026-10-13 02:17:35', '2026-10-13 02:17:35', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_type_index` (`type`),
  ADD KEY `categories_parent_id_index` (`parent_id`),
  ADD KEY `categories_is_active_index` (`is_active`),
  ADD KEY `categories_sort_order_index` (`sort_order`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_status_index` (`status`),
  ADD KEY `contacts_created_at_index` (`created_at`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `destinations_slug_unique` (`slug`),
  ADD KEY `destinations_parent_id_index` (`parent_id`),
  ADD KEY `destinations_status_index` (`status`),
  ADD KEY `destinations_is_featured_index` (`is_featured`),
  ADD KEY `destinations_region_index` (`region`),
  ADD KEY `destinations_sort_order_index` (`sort_order`),
  ADD KEY `destinations_published_at_index` (`published_at`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faqs_category_index` (`category`),
  ADD KEY `faqs_is_active_index` (`is_active`),
  ADD KEY `faqs_sort_order_index` (`sort_order`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_mediable_index` (`mediable_type`,`mediable_id`),
  ADD KEY `media_collection_index` (`collection`),
  ADD KEY `media_sort_order_index` (`sort_order`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_location_unique` (`location`),
  ADD KEY `menus_is_active_index` (`is_active`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_menu_id_index` (`menu_id`),
  ADD KEY `menu_items_parent_id_index` (`parent_id`),
  ADD KEY `menu_items_sort_order_index` (`sort_order`),
  ADD KEY `menu_items_is_active_index` (`is_active`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`),
  ADD KEY `pages_status_index` (`status`),
  ADD KEY `pages_published_at_index` (`published_at`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`),
  ADD KEY `posts_user_id_index` (`user_id`),
  ADD KEY `posts_category_id_index` (`category_id`),
  ADD KEY `posts_status_index` (`status`),
  ADD KEY `posts_is_featured_index` (`is_featured`),
  ADD KEY `posts_published_at_index` (`published_at`);

--
-- Indexes for table `post_tag`
--
ALTER TABLE `post_tag`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `post_tag_tag_id_index` (`tag_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`),
  ADD KEY `settings_group_index` (`group`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sliders_position_index` (`position`),
  ADD KEY `sliders_is_active_index` (`is_active`),
  ADD KEY `sliders_sort_order_index` (`sort_order`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_slug_unique` (`slug`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tours_slug_unique` (`slug`),
  ADD KEY `tours_category_id_index` (`category_id`),
  ADD KEY `tours_status_index` (`status`),
  ADD KEY `tours_is_featured_index` (`is_featured`),
  ADD KEY `tours_difficulty_index` (`difficulty`),
  ADD KEY `tours_published_at_index` (`published_at`),
  ADD KEY `tours_sort_order_index` (`sort_order`);

--
-- Indexes for table `tour_destination`
--
ALTER TABLE `tour_destination`
  ADD PRIMARY KEY (`tour_id`,`destination_id`),
  ADD KEY `tour_destination_destination_id_index` (`destination_id`);

--
-- Indexes for table `tour_itineraries`
--
ALTER TABLE `tour_itineraries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_itineraries_tour_id_index` (`tour_id`),
  ADD KEY `tour_itineraries_day_number_index` (`day_number`),
  ADD KEY `tour_itineraries_sort_order_index` (`sort_order`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_index` (`role`),
  ADD KEY `users_is_active_index` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tour_itineraries`
--
ALTER TABLE `tour_itineraries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `destinations`
--
ALTER TABLE `destinations`
  ADD CONSTRAINT `destinations_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `destinations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_tag`
--
ALTER TABLE `post_tag`
  ADD CONSTRAINT `post_tag_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `tours_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tour_destination`
--
ALTER TABLE `tour_destination`
  ADD CONSTRAINT `tour_destination_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_destination_tour_id_foreign` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_itineraries`
--
ALTER TABLE `tour_itineraries`
  ADD CONSTRAINT `tour_itineraries_tour_id_foreign` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
