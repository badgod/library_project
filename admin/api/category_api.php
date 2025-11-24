<?php
// admin/api/category_api.php
header('Content-Type: application/json');
require_once '../../config/session_init.php';
session_start();

require_once '../../config/appconfig.php';
require_once '../../config/connectdb.php';
require_once '../services/security.php';

checkAdminLogin();

$action = $_REQUEST['action'] ?? '';

try {
    // 1. ดึงข้อมูล (GET)
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sql = "SELECT * FROM category ORDER BY category_id DESC";
        $stmt = $pdo->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $data]);
        exit();
    }

    // 2. เพิ่มข้อมูล (POST action=create)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);

        if (empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกชื่อหมวดหมู่']);
            exit();
        }

        // แก้ไขชื่อฟิลด์ให้ตรงกับ DB: name, description
        $stmt = $pdo->prepare("INSERT INTO category (name, description) VALUES (:name, :desc)");
        $stmt->execute([':name' => $name, ':desc' => $description]);

        echo json_encode(['status' => 'success', 'message' => 'เพิ่มหมวดหมู่สำเร็จ']);
        exit();
    }

    // 3. แก้ไขข้อมูล (POST action=update)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
        $id = $_POST['category_id'];
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);

        if (empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกชื่อหมวดหมู่']);
            exit();
        }

        // แก้ไขชื่อฟิลด์ให้ตรงกับ DB
        $stmt = $pdo->prepare("UPDATE category SET name = :name, description = :desc WHERE category_id = :id");
        $stmt->execute([':name' => $name, ':desc' => $description, ':id' => $id]);

        echo json_encode(['status' => 'success', 'message' => 'แก้ไขข้อมูลสำเร็จ']);
        exit();
    }

    // 4. ลบข้อมูล (POST action=delete)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
        $id = $_POST['id'];

        // เช็ค Foreign Key ในตาราง book_title
        $check = $pdo->prepare("SELECT COUNT(*) FROM book_title WHERE category_id = :id");
        $check->execute([':id' => $id]);
        if ($check->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบได้ เนื่องจากมีหนังสือในหมวดหมู่นี้อยู่']);
            exit();
        }

        $stmt = $pdo->prepare("DELETE FROM category WHERE category_id = :id");
        $stmt->execute([':id' => $id]);

        echo json_encode(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
        exit();
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
}
