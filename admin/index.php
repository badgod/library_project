<?php
// 1. เริ่มต้น Session และ Config (เรียกจากโฟลเดอร์หลัก ../config)
// ใช้ __DIR__ . '/../' เพื่อถอยกลับไป 1 ขั้นจากโฟลเดอร์ admin
include_once __DIR__ . '/../config/session_init.php';
session_start();

require_once __DIR__ . '/../config/appconfig.php';
require_once __DIR__ . '/../config/connectdb.php';
require_once __DIR__ . '/services/security.php';

checkAdminLogin(); // ตรวจสอบการ Login

// 2. Routing Logic (ปรับให้รองรับ Path ของ Admin)
$script_name = $_SERVER['SCRIPT_NAME']; // เช่น /library_project/admin/index.php
$base_path = str_replace('index.php', '', $script_name); // จะได้ /library_project/admin/

// รับ URI ที่ร้องขอ
$uri = $_SERVER['REQUEST_URI'];
$route_string = parse_url($uri, PHP_URL_PATH);

// ตัด Base Path ออกเพื่อให้เหลือแค่ Route ภายใน Admin
// เช่น /library_project/admin/dashboard -> dashboard
if (strpos($route_string, $base_path) === 0) {
    $route_string = substr($route_string, strlen($base_path));
}

$route_string = trim($route_string, '/');
$segments = explode('/', $route_string);

// กำหนด Route เริ่มต้นเป็น 'dashboard' แทน 'index'
$route = $segments[0] ?: 'dashboard';
$action = $segments[1] ?? 'index';

$page_path = '';
$title_page = '';

// 3. กำหนด Pages ตาม Route
switch ($route) {
    case 'index':
    case 'index.php':
        $title_page = 'หน้าหลัก';
        $page_path = 'pages/dashboard.php';
        break;

    case 'settings':
    case 'settings.php':
        $title_page = 'การตั้งค่าระบบ';
        $page_path = 'pages/settings/settings.php';
        break;

    case 'user':
    case 'user.php':
        $title_page = 'ผู้ใช้งานและสมาชิก';
        $page_path = 'pages/user/user.php';
        break;

    case 'user_form':
    case 'user_form.php':
        $title_page = 'ข้อมูลผู้ใช้งาน';
        $page_path = 'pages/user/user_form.php';
        break;

    case 'profile':
    case 'profile.php':
        $title_page = 'แก้ไขข้อมูลส่วนตัว';
        $page_path = 'pages/profile/profile.php';
        break;

    case 'change_password':
    case 'change_password.php':
        $title_page = 'เปลี่ยนรหัสผ่าน';
        $page_path = 'pages/profile/change_password.php';
        break;

    case 'category':
    case 'category.php':
        $title_page = 'หมวดหมู่หนังสืือ';
        $page_path = 'pages/category/category.php';
        break;

    case 'book':
    case 'book.php':
        $title_page = 'หนังสือ';
        $page_path = 'pages/book/book.php';
        break;
    
    case 'book_form':
    case 'book_form.php':
        $title_page = 'ข้อมูลหนังสือ';
        $page_path = 'pages/book/book_form.php';
        break;

    default:
        http_response_code(404);
        $page_path = 'pages/404.php';
        break;
}

// 4. โหลดหน้า View (Layout Management)
// ตรวจสอบไฟล์ View
$full_page_path = __DIR__ . '/' . $page_path;

if (file_exists($full_page_path)) {
    // INCLUDE HEADER: ใน header.php ของคุณมีการ include 'sidebar.php' 
    // และเปิด tag <main> ไว้แล้ว บรรทัดสุดท้ายคือ <main class="...">
    include __DIR__ . '/includes/header.php';

    // CONTENT: โหลดเนื้อหาจาก pages/
    include $full_page_path;

    // INCLUDE FOOTER: ต้องมี footer มาปิด tag </main> และ </div> ที่เปิดค้างไว้
    include __DIR__ . '/includes/footer.php';
} else {
    // กรณีไม่เจอไฟล์ View ให้แสดง Error ง่ายๆ หรือ Redirect ไปหน้า 404
    http_response_code(404);

    include __DIR__ . '/includes/header.php';
    include __DIR__ . '/pages/404.php';
    include __DIR__ . '/includes/footer.php';
}
