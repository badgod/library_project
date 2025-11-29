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

$member_id = $_SESSION['member_id'];
$action = $_GET['action'] ?? 'get_transactions';

try {
    if ($action === 'get_transactions') {
        // ดึงข้อมูลรายการยืม (Header) โดยนับจำนวนหนังสือในแต่ละรายการด้วย
        $sql = "SELECT 
                    l.loan_id, 
                    l.loan_date, 
                    l.due_date, 
                    l.status,
                    COUNT(li.loan_item_id) as total_books,
                    SUM(CASE WHEN li.status = 'return' THEN 1 ELSE 0 END) as returned_count
                FROM loan l
                LEFT JOIN loan_item li ON l.loan_id = li.loan_id
                WHERE l.member_id = :member_id
                GROUP BY l.loan_id
                ORDER BY l.loan_date DESC, l.loan_id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':member_id' => $member_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['data' => $result]);
    } elseif ($action === 'get_details') {
        // ดึงรายละเอียดหนังสือในรายการยืมนั้นๆ (Detail)
        $loan_id = $_GET['id'] ?? 0;

        $sql = "SELECT 
                    b.title, 
                    b.image, 
                    b.title_id,
                    li.status as item_status,
                    li.return_date
                FROM loan_item li
                JOIN physical_copy pc ON li.copy_id = pc.copy_id
                JOIN book_title b ON pc.title_id = b.title_id
                WHERE li.loan_id = :loan_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':loan_id' => $loan_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'data' => $result]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
