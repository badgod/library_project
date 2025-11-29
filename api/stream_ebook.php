<?php
require_once '../config/session_init.php';
session_start();
require_once '../config/connectdb.php';

// 1. ตรวจสอบว่าล็อกอินหรือยัง
if (!isset($_SESSION['member_id'])) {
    http_response_code(403);
    exit("กรุณาเข้าสู่ระบบ");
}

$ebook_id = $_GET['id'] ?? 0;
$member_id = $_SESSION['member_id'];

try {
    // 2. ดึงข้อมูลไฟล์ E-Book จาก Database
    $stmt = $pdo->prepare("SELECT ebook_file FROM ebook WHERE ebook_id = ?");
    $stmt->execute([$ebook_id]);
    $ebook = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ebook && !empty($ebook['ebook_file'])) {
        $file_path = '../uploads/ebooks/' . $ebook['ebook_file'];

        // ตรวจสอบว่ามีไฟล์จริงไหม
        if (file_exists($file_path)) {

            // 3. บันทึก Log การอ่าน (ebook_log)
            $logSql = "INSERT INTO ebook_log (ebook_id, member_id, ebooklog_date) VALUES (:ebook_id, :member_id, NOW())";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute([
                ':ebook_id' => $ebook_id,
                ':member_id' => $member_id
            ]);

            // 4. ตั้งค่า Header เพื่อแสดงผลไฟล์ PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            // ส่งข้อมูลไฟล์ไปที่ Browser
            readfile($file_path);
            exit;
        } else {
            echo "ไม่พบไฟล์เอกสาร";
        }
    } else {
        echo "ไม่พบข้อมูล E-Book";
    }
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}