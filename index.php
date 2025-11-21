<?php

// =========================================================
// FRONT CONTROLLER: index.php (สำหรับ Public/Member)
// =========================================================

// เริ่มต้น Session
session_start();

// 1. นำเข้าไฟล์ที่จำเป็น (ปรับ Path ไปที่ /app_shared/)
// require_once __DIR__ . '/app_shared/db_connect.php'; // SHARED PDO CONNECTION
// require_once __DIR__ . '/app_shared/auth.php';       // SHARED AUTH FUNCTIONS
// $pdo object ถูกสร้างและพร้อมใช้งานจาก db_connect.php

// 2. รับ URI และแยก Segment สำหรับ Routing
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $uri);
$route = $segments[0] ?: 'index';
$action = $segments[1] ?? 'index';

$page_path = '';

switch ($route) {
    // -----------------------------------------------------------------
    // A. PUBLIC ACCESS PAGES
    // -----------------------------------------------------------------
    case 'index':
        // หน้าแรกสาธารณะ (แสดง Carousel และ Search)
        $page_path = 'pages/home.php';
        break;

    case 'login':
    case 'register':
        // หน้า Login/Register อยู่ใน Root
        $page_path = $route . '.php'; 
        break;
        
    // -----------------------------------------------------------------
    // B. MEMBER ACCESS PAGES (ต้องการการตรวจสอบสิทธิ์)
    // -----------------------------------------------------------------
    case 'member':
        // check_member_access(); // ตรวจสอบสิทธิ์ Member 
        
        // กำหนดชื่อไฟล์ View ตาม Segment ที่ 2 
        $file_name = ($action === 'index' || $action === '') ? 'profile' : $action;
        $page_path = 'member/' . $file_name . '.php';
        break;

    // -----------------------------------------------------------------
    // C. API HANDLERS (รับ Ajax Request)
    // -----------------------------------------------------------------
    case 'api':
        // ส่งคำขอไปยัง Handler ในโฟลเดอร์ /api/
        $handler_name = $action . '_handler.php'; 
        $handler_path = 'api/' . $handler_name;
        
        if (file_exists($handler_path)) {
            require_once $handler_path;
        } else {
            http_response_code(400); 
            echo json_encode(['error' => 'Public API handler not found.']);
        }
        exit;
        
    default:
        // หากไม่ตรงกับ Route ใดๆ ให้แสดงหน้า 404
        http_response_code(404);
        $page_path = 'page/404.php';
        break;
}

// =========================================================
// 4. โหลดหน้า View ที่ถูกกำหนดไว้
// =========================================================

if (file_exists($page_path)) {
    // โหลด Header และ Footer สำหรับ Public/Member Layout
    include __DIR__ . '/includes/_header.php';
    include __DIR__ . '/' . $page_path;
    include __DIR__ . '/includes/_footer.php';
} else if ($route !== 'api') {
    // ถ้าไฟล์ View ไม่พบและไม่ใช่ API Call (404 Error)
    http_response_code(404);
    include __DIR__ . '/includes/_header.php';
    echo '<div class="container mt-5"><h1>404 Not Found</h1><p>The requested page could not be found.</p></div>';
    include __DIR__ . '/includes/_footer.php';
}
