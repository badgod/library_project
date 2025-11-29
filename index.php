<?php
// =========================================================
// FRONT CONTROLLER: index.php (สำหรับ Public/Member)
// =========================================================

// เริ่มต้น Session 
include_once __DIR__ . '/config/session_init.php';
session_start();

require_once __DIR__ . '/config/appconfig.php';
require_once __DIR__ . '/config/connectdb.php';
require_once __DIR__ . '/services/security.php';

// 1. นำเข้าไฟล์ที่จำเป็น (ปรับ Path ไปที่ /app_shared/)
// ใช้ require_once เพื่อหยุดการทำงานหากไฟล์สำคัญหายไป
// require_once __DIR__ . '/app_shared/db_connect.php'; // SHARED PDO CONNECTION
// require_once __DIR__ . '/app_shared/auth.php';       // SHARED AUTH FUNCTIONS
// $pdo object ถูกสร้างและพร้อมใช้งานจาก db_connect.php

// 2. รับ URI และแยก Segment สำหรับ Routing
$script_name = $_SERVER['SCRIPT_NAME']; // เช่น /library_project/index.php
$base_path = str_replace('index.php', '', $script_name); // จะได้ /library_project/

// B. รับ URI ที่ร้องขอ (รวม Query String)
$uri = $_SERVER['REQUEST_URI']; // เช่น /library_project/member/history?id=1

// C. ลบ Base Path และ Query String ออก เพื่อให้ได้ Route ที่แท้จริง
$route_string = parse_url($uri, PHP_URL_PATH);
$route_string = substr($route_string, strlen($base_path)); // ตัด /library_project/ ออก
$route_string = trim($route_string, '/'); // ตัด / หน้าและหลังออก

$segments = explode('/', $route_string);

// กำหนด Route และ Action จาก Segment
$route = $segments[0] ?: 'index'; // หากว่างเปล่า จะได้ 'index'
$action = $segments[1] ?? 'index';

$page_path = '';
$title_page = 'p'; // กำหนด Title หน้าเริ่มต้น

switch ($route) {
    // -----------------------------------------------------------------
    // A. PUBLIC ACCESS PAGES (Root / login / register)
    // -----------------------------------------------------------------
    case 'index':
    case 'index.php':
        // หน้าแรกสาธารณะ (Carousel + Search)
        $title_page = 'หน้าหลัก';
        $page_path = 'pages/home.php';
        break;

    case 'signin':
        $title_page = 'เข้าสู่ระบบ';
        $page_path = 'pages/signin.php';
        break;

    case 'signout':
        require_once 'pages/signout.php';
        exit();
        break;

    case 'books':
        $title_page = 'รายการหนังสือทั้งหมด';
        $page_path = 'pages/books.php';
        break;

    case 'book_detail':
        $title_page = 'รายละเอียดหนังสือ';
        $page_path = 'pages/book_detail.php';
        break;

    case 'how_to_borrow_return':
        $title_page = 'วิธียืม-คืนหนังสือ';
        $page_path = 'pages/how_to_borrow_return.php';
        break;

    case 'about':
        $title_page = 'เกี่ยวกับเรา';
        $page_path = 'pages/about.php';
        break;

    case 'contact':
        $title_page = 'ติดต่อเรา';
        $page_path = 'pages/contact.php';
        break;

        // -----------------------------------------------------------------
        // B. MEMBER ACCESS PAGES (ต้องการการตรวจสอบสิทธิ์)
        // -----------------------------------------------------------------
        checkMemberLogin();

    case 'read_ebook':
        $title_page = 'อ่าน E-Book';
        $page_path = 'pages/read_ebook.php';
        break;

    case 'profile':
        $title_page = 'ข้อมูลส่วนตัว';
        $page_path = 'pages/profile.php';
        break;

    case 'change_password':
        $title_page = 'เปลี่ยนรหัสผ่าน';
        $page_path = 'pages/change_password.php';
        break;

    default:
        // หากไม่ตรงกับ Route ใดๆ ให้แสดงหน้า 404 (Pages/404.php)
        http_response_code(404);
        $page_path = 'pages/404.php';
        break;
}

// =========================================================
// 4. โหลดหน้า View ที่ถูกกำหนดไว้
// =========================================================

if (file_exists($page_path)) {
    // โหลด Header และ Footer สำหรับ Public/Member Layout
    // สมมติว่าไฟล์ header/footer อยู่ใน includes/
    include __DIR__ . '/includes/header.php'; // ใช้ _header.php ตามไฟล์ที่คุณมี
    include __DIR__ . '/' . $page_path;
    include __DIR__ . '/includes/footer.php'; // ใช้ _footer.php ตามไฟล์ที่คุณมี
} else {
    // ถ้าไฟล์ View ไม่พบและไม่ใช่ API Call (404 Error)
    http_response_code(404);
    // โหลดหน้า 404.php แทน (ถ้าไฟล์ 404.php มีอยู่จริงใน pages/)
    include __DIR__ . '/includes/header.php';
    include __DIR__ . '/pages/404.php';
    include __DIR__ . '/includes/footer.php';
}
