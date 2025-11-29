
<?php
header('Content-Type: application/json');
include_once '../../config/session_init.php';
session_start();

require_once '../../config/appconfig.php';
require_once '../../config/connectdb.php';

// CASE 1: GET - ดึงข้อมูล Settings มาแสดง
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // ดึงข้อมูลจากตาราง settings id=1
        $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1 LIMIT 1");
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}

// CASE 2: POST - บันทึกข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // รับค่าจาก jQuery serialize()
        $max_loan_days = intval($_POST['max_loan_days']);
        $renew_days_before_due = intval($_POST['renew_days_before_due']);
        $renew_days_after_due = intval($_POST['renew_days_after_due']);
        $max_renew_count = intval($_POST['max_renew_count']);
        $max_renew_days = intval($_POST['max_renew_days']);
        $fine_per_day = intval($_POST['fine_per_day']);

        if ($max_loan_days < 0 || $fine_per_day < 0) {
             echo json_encode(['status' => 'error', 'message' => 'ค่าข้อมูลไม่ถูกต้อง (ห้ามติดลบ)']);
             exit();
        }

        $sql = "UPDATE settings SET 
                max_loan_days = :max_loan_days,
                renew_days_before_due = :renew_days_before_due,
                renew_days_after_due = :renew_days_after_due,
                max_renew_count = :max_renew_count,
                max_renew_days = :max_renew_days,
                fine_per_day = :fine_per_day
                WHERE id = 1";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':max_loan_days' => $max_loan_days,
            ':renew_days_before_due' => $renew_days_before_due,
            ':renew_days_after_due' => $renew_days_after_due,
            ':max_renew_count' => $max_renew_count,
            ':max_renew_days' => $max_renew_days,
            ':fine_per_day' => $fine_per_day
        ]);

        if($result){
            echo json_encode(['status' => 'success', 'message' => 'บันทึกการตั้งค่าเรียบร้อยแล้ว']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
        }

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
    }
}