<?php
// api/profile_api.php
header('Content-Type: application/json');
require_once '../config/session_init.php';
require_once '../config/connectdb.php';
require_once '../config/appconfig.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['member_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$action = $_REQUEST['action'] ?? '';
$member_id = $_SESSION['member_id'];

try {
    // 1. ดึงข้อมูลส่วนตัว (GET)
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sql = "SELECT u.username, m.first_name, m.last_name, m.email, m.tel, m.employee_id 
                FROM user u 
                LEFT JOIN member m ON u.member_id = m.member_id 
                WHERE u.user_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $member_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'data' => $data]);
        exit();
    }

    // 2. อัปเดตข้อมูลส่วนตัว (POST action=update_info)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_info') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];

        // หา member_id ของ admin คนนี้
        $stmt = $pdo->prepare("SELECT member_id FROM user WHERE user_id = :id");
        $stmt->execute([':id' => $member_id]);
        $user = $stmt->fetch();

        if ($user['member_id']) {
            $sql = "UPDATE member SET 
                    first_name = :fname, 
                    last_name = :lname, 
                    email = :email, 
                    tel = :tel 
                    WHERE member_id = :mid";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':fname' => $first_name,
                ':lname' => $last_name,
                ':email' => $email,
                ':tel' => $tel,
                ':mid' => $user['member_id']
            ]);

            // อัปเดตชื่อใน Session ด้วย
            $_SESSION['member_name'] = $first_name . ' ' . $last_name;

            echo json_encode(['status' => 'success', 'message' => 'บันทึกข้อมูลสำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่พบข้อมูลสมาชิก']);
        }
        exit();
    }

    // 3. เปลี่ยนรหัสผ่าน (POST action=change_password)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'change_password') {
        $current_pass = $_POST['current_password'];
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];

        if ($new_pass !== $confirm_pass) {
            echo json_encode(['status' => 'error', 'message' => 'รหัสผ่านใหม่ไม่ตรงกัน']);
            exit();
        }

        // ดึงรหัสผ่านเก่ามาตรวจสอบ
        $stmt = $pdo->prepare("SELECT password FROM user WHERE user_id = :id");
        $stmt->execute([':id' => $member_id]);
        $user = $stmt->fetch();

        if (password_verify($current_pass, $user['password'])) {
            // รหัสถูก -> เปลี่ยนรหัสใหม่
            $hash_new = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE user SET password = :pass, change_password = 1 WHERE user_id = :id");
            $update->execute([':pass' => $hash_new, ':id' => $member_id]);

            echo json_encode(['status' => 'success', 'message' => 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง']);
        }
        exit();
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}