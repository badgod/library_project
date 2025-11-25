<?php
// admin/api/user_api.php
header('Content-Type: application/json');
require_once '../../config/session_init.php';
require_once '../../config/appconfig.php';
require_once '../../config/connectdb.php';
require_once '../services/security.php';

checkAdminLogin();

$action = $_REQUEST['action'] ?? '';

try {
    // ===================================================================================
    // 1. อ่านข้อมูล (GET) - รวม Logic ดึงทั้งหมด และ ดึงรายคน ไว้ในที่เดียว
    // ===================================================================================
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        // CASE 1.1: ถ้ามี ID ส่งมา -> ดึงข้อมูลเฉพาะคนนั้น (สำหรับหน้าแก้ไข)
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT u.user_id, u.username, u.role, 
                           m.member_id, m.employee_id, m.first_name, m.last_name, m.email, m.tel, m.status
                    FROM user u
                    LEFT JOIN member m ON u.member_id = m.member_id
                    WHERE u.user_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
            }
            exit(); // จบการทำงาน
        }

        // CASE 1.2: ถ้าไม่มี ID -> ดึงข้อมูลทั้งหมด (สำหรับหน้าตาราง)
        $sql = "SELECT u.user_id, u.username, u.role, 
                       m.member_id, m.employee_id, m.first_name, m.last_name, m.email, m.tel, m.status
                FROM user u
                LEFT JOIN member m ON u.member_id = m.member_id
                ORDER BY u.user_id DESC";
        $stmt = $pdo->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // DataTables ต้องการ key ชื่อ 'data'
        echo json_encode(['data' => $data]);
        exit(); // จบการทำงาน
    }

    // ===================================================================================
    // 2. เพิ่มผู้ใช้งาน (POST action=create)
    // ===================================================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
        $employee_id = trim($_POST['employee_id']);
        $username = trim($_POST['username']);
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $tel = trim($_POST['tel']);
        $role = $_POST['role'];
        $status = $_POST['status'];

        // ตรวจสอบ Username ซ้ำ
        $chk = $pdo->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
        $chk->execute([$username]);
        if($chk->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Username นี้มีผู้ใช้งานแล้ว']);
            exit();
        }

        // ตรวจสอบ Employee ID ซ้ำ
        $chk2 = $pdo->prepare("SELECT COUNT(*) FROM member WHERE employee_id = ?");
        $chk2->execute([$employee_id]);
        if($chk2->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'รหัสพนักงานนี้มีอยู่ในระบบแล้ว']);
            exit();
        }

        $pdo->beginTransaction();

        try {
            // 2.1 เพิ่มลงตาราง Member
            $sql_member = "INSERT INTO member (employee_id, first_name, last_name, email, tel, status) 
                           VALUES (:emp_id, :fname, :lname, :email, :tel, :status)";
            $stmt1 = $pdo->prepare($sql_member);
            $stmt1->execute([
                ':emp_id' => $employee_id,
                ':fname' => $first_name,
                ':lname' => $last_name,
                ':email' => $email,
                ':tel' => $tel,
                ':status' => $status
            ]);
            $member_id = $pdo->lastInsertId();

            // 2.2 เพิ่มลงตาราง User (Password Default: 123456)
            $password_hash = password_hash('123456', PASSWORD_DEFAULT);
            $sql_user = "INSERT INTO user (username, password, role, member_id) 
                         VALUES (:user, :pass, :role, :mid)";
            $stmt2 = $pdo->prepare($sql_user);
            $stmt2->execute([
                ':user' => $username,
                ':pass' => $password_hash,
                ':role' => $role,
                ':mid' => $member_id
            ]);

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'เพิ่มผู้ใช้งานเรียบร้อย (รหัสผ่านเริ่มต้น: 123456)']);

        } catch (Exception $ex) {
            $pdo->rollBack();
            throw $ex;
        }
        exit();
    }

    // ===================================================================================
    // 3. แก้ไขข้อมูล (POST action=update)
    // ===================================================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
        $user_id = $_POST['user_id'];
        $member_id = $_POST['member_id'];
        
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $tel = trim($_POST['tel']);
        $role = $_POST['role'];
        $status = $_POST['status'];

        $pdo->beginTransaction();
        try {
            // อัปเดตตาราง Member
            $sql_member = "UPDATE member SET first_name = :fname, last_name = :lname, email = :email, tel = :tel, status = :status 
                           WHERE member_id = :mid";
            $stmt1 = $pdo->prepare($sql_member);
            $stmt1->execute([
                ':fname' => $first_name,
                ':lname' => $last_name,
                ':email' => $email,
                ':tel' => $tel,
                ':status' => $status,
                ':mid' => $member_id
            ]);

            // อัปเดตตาราง User (Role)
            $sql_user = "UPDATE user SET role = :role WHERE user_id = :uid";
            $stmt2 = $pdo->prepare($sql_user);
            $stmt2->execute([':role' => $role, ':uid' => $user_id]);

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'บันทึกการแก้ไขเรียบร้อย']);
        } catch (Exception $ex) {
            $pdo->rollBack();
            throw $ex;
        }
        exit();
    }

    // ===================================================================================
    // 4. รีเซ็ตรหัสผ่าน (POST action=reset_password)
    // ===================================================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'reset_password') {
        $user_id = $_POST['id'];
        
        $new_password = password_hash('123456', PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("UPDATE user SET password = :pass WHERE user_id = :id");
        $stmt->execute([':pass' => $new_password, ':id' => $user_id]);
        
        echo json_encode(['status' => 'success', 'message' => 'รีเซ็ตรหัสผ่านเป็น "123456" เรียบร้อยแล้ว']);
        exit();
    }

    // ===================================================================================
    // 5. ลบผู้ใช้งาน (POST action=delete)
    // ===================================================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
        $user_id = $_POST['user_id'];
        $member_id = $_POST['member_id'];

        // ป้องกันการลบตัวเอง
        if ($user_id == $_SESSION['admin_id']) {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบบัญชีที่กำลังใช้งานอยู่ได้']);
            exit();
        }

        $pdo->beginTransaction();
        try {
            // ลบ User ก่อน (เพราะมี FK ชี้ไป Member)
            $stmt1 = $pdo->prepare("DELETE FROM user WHERE user_id = :uid");
            $stmt1->execute([':uid' => $user_id]);

            // ลบ Member
            $stmt2 = $pdo->prepare("DELETE FROM member WHERE member_id = :mid");
            $stmt2->execute([':mid' => $member_id]);

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'ลบผู้ใช้งานเรียบร้อย']);
        } catch (Exception $ex) {
            $pdo->rollBack();
            // กรณีลบไม่ได้เนื่องจาก Member ไปผูกกับตารางอื่น
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบได้ เนื่องจากมีประวัติการใช้งานในระบบ']);
        }
        exit();
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
}
?>