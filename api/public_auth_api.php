<?php
// api/public_auth_api.php
header('Content-Type: application/json');
include_once '../config/session_init.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
    exit();
}

require_once '../config/appconfig.php';
require_once '../config/connectdb.php';

// รับค่าจาก AJAX (ชื่อ field ตาม name ใน input form)
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
    exit();
}

try {
    // ตรวจสอบ username และ role='admin'
    // อ้างอิงตาราง user
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = :username AND role = 'member' LIMIT 1");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบ Password hash
    if ($user && password_verify($password, $user['password'])) {
        $sql = 'SELECT * FROM member WHERE member_id = :member_id AND status = "active"';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':member_id' => $user['member_id']]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$member) {
            echo json_encode(['status' => 'error', 'message' => 'บัญชีผู้ใช้ถูกระงับหรือไม่พบข้อมูลสมาชิก']);
            exit();
        }

        $_SESSION['member_login'] = true;
        $_SESSION['member_id'] = $user['user_id'];
        $_SESSION['member_username'] = $user['username'];
        $_SESSION['member_member_id'] = $member['member_id'];
        $_SESSION['member_employee_id'] = $member['employee_id'];
        $_SESSION['member_name'] = $member['first_name'] . ' ' . $member['last_name'];


        echo json_encode(['status' => 'success', 'message' => 'เข้าสู่ระบบสำเร็จ', 'change_password' => $user['change_password']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
}
