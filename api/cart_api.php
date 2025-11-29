<?php
include_once '../config/session_init.php';
session_start();
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($action === 'add') {
    $id = $_POST['id'] ?? 0;
    $title = $_POST['title'] ?? '';
    
    // ตรวจสอบว่ามีหนังสือนั้นในตะกร้าหรือยัง (ยืมได้เรื่องละ 1 เล่ม)
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = [
            'id' => $id,
            'title' => $title,
            'qty' => 1
        ];
        echo json_encode(['status' => 'success', 'message' => 'เพิ่มลงตะกร้าแล้ว', 'cart_count' => count($_SESSION['cart'])]);
    } else {
        echo json_encode(['status' => 'warning', 'message' => 'คุณเลือกหนังสือนื้ไปแล้ว']);
    }

} elseif ($action === 'remove') {
    $id = $_POST['id'] ?? 0;
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    echo json_encode(['status' => 'success', 'message' => 'ลบรายการแล้ว', 'reload' => true]);

} elseif ($action === 'clear') {
    unset($_SESSION['cart']);
    echo json_encode(['status' => 'success', 'message' => 'ล้างตะกร้าแล้ว', 'reload' => true]);
}
?>