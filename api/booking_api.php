<?php
header('Content-Type: application/json');
include '../config/session_init.php';
session_start();

require_once '../config/connectdb.php';
require_once '../services/security.php';

checkMemberLogin();

// 1. ตรวจสอบการ Login
if (!isset($_SESSION['member_login']) || empty($_SESSION['member_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อนทำรายการ']);
    exit;
}

$member_id = $_SESSION['member_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo json_encode(['status' => 'error', 'message' => 'ไม่มีรายการในตะกร้า']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 2. ดึงค่า Setting (จำนวนวันยืมสูงสุด)
    $stmtSetting = $pdo->prepare("SELECT max_loan_days FROM settings LIMIT 1");
    $stmtSetting->execute();
    $setting = $stmtSetting->fetch();
    $max_days = $setting['max_loan_days'] ?? 7; // Default 7 วัน

    // 3. สร้างรายการ Loan (Header)
    // status = 'receive' ในที่นี้เราใช้แทนสถานะ "รอรับหนังสือ" (Pending Pickup)
    // เนื่องจาก Enum มีแค่ borrow, receive, somereturn, return
    $sqlLoan = "INSERT INTO loan (member_id, loan_date, due_date, status) VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY), 'receive')";
    $stmtLoan = $pdo->prepare($sqlLoan);
    $stmtLoan->execute([$member_id, $max_days]);
    $loan_id = $pdo->lastInsertId();

    // 4. วนลูปสินค้าในตะกร้าเพื่อหาเล่มที่ว่าง (Physical Copy)
    foreach ($cart as $item) {
        $title_id = $item['id'];

        // หาเล่มที่ว่างอยู่ (status = 'available')
        $stmtFindCopy = $pdo->prepare("SELECT copy_id FROM physical_copy WHERE title_id = ? AND status = 'available' LIMIT 1 FOR UPDATE");
        $stmtFindCopy->execute([$title_id]);
        $copy = $stmtFindCopy->fetch();

        if (!$copy) {
            // ถ้าหนังสือเล่มไหนหมด ให้ Rollback ทันที
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => "หนังสือ '{$item['title']}' ถูกยืมไปหมดแล้ว กรุณาลบออกจากรายการ"]);
            exit;
        }

        $copy_id = $copy['copy_id'];

        // 5. อัปเดตสถานะเล่มหนังสือเป็น on_loan (เพื่อจองเล่มไว้ไม่ให้คนอื่นยืมซ้อน)
        $stmtUpdateCopy = $pdo->prepare("UPDATE physical_copy SET status = 'on_loan' WHERE copy_id = ?");
        $stmtUpdateCopy->execute([$copy_id]);

        // 6. บันทึกรายละเอียดลง loan_item
        // status ใน loan_item เริ่มต้นเป็น borrow (คือสถานะของ item ใน loan นี้)
        $stmtItem = $pdo->prepare("INSERT INTO loan_item (loan_id, copy_id, status) VALUES (?, ?, 'borrow')");
        $stmtItem->execute([$loan_id, $copy_id]);
    }

    // 7. Commit Transaction และล้างตะกร้า
    $pdo->commit();
    unset($_SESSION['cart']);

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
