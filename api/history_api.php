<?php
header('Content-Type: application/json');
require_once '../config/session_init.php';
require_once '../config/connectdb.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['member_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$member_id = $_SESSION['member_id'];
$action = $_GET['action'] ?? 'get_history';

if ($action === 'get_history') {
    try {
        $sql = "SELECT 
                    l.loan_id, 
                    l.loan_date, 
                    l.due_date, 
                    l.status as loan_status,
                    li.status as item_status,
                    li.return_date as item_return_date,
                    b.title, 
                    b.image,
                    b.title_id
                FROM loan l
                JOIN loan_item li ON l.loan_id = li.loan_id
                JOIN physical_copy pc ON li.copy_id = pc.copy_id
                JOIN book_title b ON pc.title_id = b.title_id
                WHERE l.member_id = :member_id
                ORDER BY l.loan_date DESC, l.loan_id DESC"; // เรียงวันที่ล่าสุดก่อน

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':member_id' => $member_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ส่งข้อมูลกลับในรูปแบบที่ DataTables ต้องการ (Object ที่มี key ชื่อ 'data')
        echo json_encode(['data' => $result]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
