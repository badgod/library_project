<?php
// admin/api/book_api.php
header('Content-Type: application/json');
require_once '../../config/session_init.php';
session_start();

require_once '../../config/appconfig.php';
require_once '../../config/connectdb.php';
require_once '../services/security.php';
// เรียกใช้ class.upload.php ที่คุณมีอยู่
require_once '../services/class.upload.php';

checkAdminLogin();

$action = $_REQUEST['action'] ?? '';

// กำหนด Path สำหรับเก็บไฟล์ (ต้องสร้างโฟลเดอร์เหล่านี้ไว้จริง)
$uploadDirImages = '../../assets/images/';
$uploadDirEbooks = '../../uploads/ebooks/';

try {
    // =================================================================================
    // 1. GET: ดึงข้อมูลหนังสือ (Read All / Get One)
    // =================================================================================
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        // 1.1 ดึงรายการหนังสือทั้งหมด
        if ($action === 'read_all') {
            $sql = "SELECT 
                        b.*, 
                        c.name as category_name,
                        (SELECT COUNT(*) FROM physical_copy p WHERE p.title_id = b.title_id) as total_copies,
                        (SELECT COUNT(*) FROM physical_copy p WHERE p.title_id = b.title_id AND p.status = 'available') as available_copies,
                        (SELECT ebook_file FROM ebook e WHERE e.title_id = b.title_id LIMIT 1) as ebook_file
                    FROM book_title b
                    LEFT JOIN category c ON b.category_id = c.category_id
                    ORDER BY b.title_id DESC";
            $stmt = $pdo->query($sql);
            echo json_encode(['data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            exit();
        }

        // 1.2 ดึงรายการเล่มหนังสือ (Physical Copies)
        if ($action === 'get_copies') {
            $title_id = $_GET['title_id'];
            $stmt = $pdo->prepare("SELECT * FROM physical_copy WHERE title_id = :id ORDER BY accession_no ASC");
            $stmt->execute([':id' => $title_id]);
            echo json_encode(['data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            exit();
        }
    }

    // =================================================================================
    // 2. POST: สร้าง/แก้ไข หนังสือ (Create / Update Book Title)
    // =================================================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'create_book' || $action === 'update_book')) {

        $title = trim($_POST['title']);
        $category_id = $_POST['category_id'];
        $author = trim($_POST['author']);
        $isbn = trim($_POST['isbn']);
        $description = trim($_POST['description']);

        // ตรวจสอบข้อมูลจำเป็น
        if (empty($title) || empty($category_id)) {
            echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกชื่อเรื่องและเลือกหมวดหมู่']);
            exit();
        }

        $imageName = ($action === 'update_book') ? $_POST['old_image'] : 'default.jpg'; // ชื่อรูปเดิม

        // --- ส่วนการจัดการอัปโหลดรูปภาพด้วย class.upload.php ---
        if (!empty($_FILES['image']['name'])) {
            $handle = new \Verot\Upload\Upload($_FILES['image']);
            if ($handle->uploaded) {
                // ตั้งชื่อไฟล์ใหม่ (ใช้เวลา + สุ่ม) เพื่อไม่ให้ซ้ำ
                $handle->file_new_name_body   = 'book_' . time() . '_' . rand(100, 999);

                // ปรับขนาดรูปภาพ (Resize)
                $handle->image_resize         = true;
                $handle->image_x              = 500; // กว้าง 500px
                $handle->image_ratio_y        = true; // สูงปรับอัตโนมัติตามสัดส่วน

                // อนุญาตเฉพาะไฟล์รูปภาพ
                $handle->allowed = array('image/*');

                // สั่ง Process ไปยังโฟลเดอร์ปลายทาง
                $handle->process($uploadDirImages);

                if ($handle->processed) {
                    $imageName = $handle->file_dst_name; // ได้ชื่อไฟล์ใหม่พร้อมนามสกุล
                    $handle->clean(); // ลบไฟล์ temp
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Upload Error: ' . $handle->error]);
                    exit();
                }
            }
        }
        // -------------------------------------------------------

        if ($action === 'create_book') {
            $sql = "INSERT INTO book_title (title, category_id, author, isbn, description, image) 
                    VALUES (:title, :cat, :auth, :isbn, :desc, :img)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':cat' => $category_id,
                ':auth' => $author,
                ':isbn' => $isbn,
                ':desc' => $description,
                ':img' => $imageName
            ]);
            echo json_encode(['status' => 'success', 'message' => 'เพิ่มหนังสือเรียบร้อย']);
        } else if ($action === 'update_book') {
            $title_id = $_POST['title_id'];
            $sql = "UPDATE book_title SET 
                    title = :title, category_id = :cat, author = :auth, 
                    isbn = :isbn, description = :desc, image = :img 
                    WHERE title_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':cat' => $category_id,
                ':auth' => $author,
                ':isbn' => $isbn,
                ':desc' => $description,
                ':img' => $imageName,
                ':id' => $title_id
            ]);
            echo json_encode(['status' => 'success', 'message' => 'แก้ไขข้อมูลเรียบร้อย']);
        }
        exit();
    }

    // =================================================================================
    // 3. POST: จัดการเล่มหนังสือ (Physical Copy)
    // =================================================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add_copy') {
        $title_id = $_POST['title_id'];
        $accession_no = trim($_POST['accession_no']);

        // เช็คเลขทะเบียนซ้ำ
        $chk = $pdo->prepare("SELECT COUNT(*) FROM physical_copy WHERE accession_no = :no");
        $chk->execute([':no' => $accession_no]);
        if ($chk->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'เลขทะเบียนนี้มีอยู่ในระบบแล้ว']);
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO physical_copy (title_id, accession_no, status) VALUES (:tid, :no, 'available')");
        if ($stmt->execute([':tid' => $title_id, ':no' => $accession_no])) {
            echo json_encode(['status' => 'success', 'message' => 'เพิ่มเล่มหนังสือสำเร็จ']);
        }
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete_copy') {
        $copy_id = $_POST['copy_id'];
        // *ควรเช็คก่อนว่าเล่มนี้ถูกยืมอยู่หรือไม่*
        $stmt = $pdo->prepare("DELETE FROM physical_copy WHERE copy_id = :id");
        $stmt->execute([':id' => $copy_id]);
        echo json_encode(['status' => 'success', 'message' => 'ลบเล่มหนังสือสำเร็จ']);
        exit();
    }

    // =================================================================================
    // 4. POST: จัดการ E-Book (Upload & Delete)
    // =================================================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'upload_ebook') {
        $title_id = $_POST['title_id'];

        if (empty($_FILES['ebook_file']['name'])) {
            echo json_encode(['status' => 'error', 'message' => 'กรุณาเลือกไฟล์ PDF']);
            exit();
        }

        // --- ใช้ class.upload.php จัดการไฟล์ PDF ---
        $handle = new \Verot\Upload\Upload($_FILES['ebook_file']);
        if ($handle->uploaded) {

            // ตรวจสอบว่าเป็น PDF เท่านั้น
            if ($handle->file_src_name_ext != 'pdf') {
                echo json_encode(['status' => 'error', 'message' => 'อนุญาตเฉพาะไฟล์ PDF เท่านั้น']);
                exit();
            }

            $handle->file_new_name_body = 'ebook_' . $title_id . '_' . time();
            $handle->file_overwrite = true; // หรือ false แล้วแต่ logic

            $handle->process($uploadDirEbooks);

            if ($handle->processed) {
                $fileName = $handle->file_dst_name;
                $handle->clean();

                // บันทึกลงฐานข้อมูล (Check ว่ามีอยู่แล้วหรือยัง ถ้ามีให้ Update)
                $chk = $pdo->prepare("SELECT ebook_id FROM ebook WHERE title_id = :tid");
                $chk->execute([':tid' => $title_id]);
                $exists = $chk->fetch(PDO::FETCH_ASSOC);

                if ($exists) {
                    $stmt = $pdo->prepare("UPDATE ebook SET ebook_file = :file WHERE title_id = :tid");
                } else {
                    $stmt = $pdo->prepare("INSERT INTO ebook (title_id, ebook_file) VALUES (:tid, :file)");
                }
                $stmt->execute([':file' => $fileName, ':tid' => $title_id]);

                echo json_encode(['status' => 'success', 'message' => 'อัปโหลด E-Book สำเร็จ']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Upload Error: ' . $handle->error]);
            }
        }
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete_ebook') {
        $title_id = $_POST['title_id'];

        // 1. หาชื่อไฟล์เพื่อลบจากโฟลเดอร์
        $stmt = $pdo->prepare("SELECT ebook_file FROM ebook WHERE title_id = :tid");
        $stmt->execute([':tid' => $title_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $filePath = $uploadDirEbooks . $row['ebook_file'];
            if (file_exists($filePath)) {
                @unlink($filePath); // ลบไฟล์จริง
            }
            // 2. ลบจากฐานข้อมูล
            $del = $pdo->prepare("DELETE FROM ebook WHERE title_id = :tid");
            $del->execute([':tid' => $title_id]);
        }

        echo json_encode(['status' => 'success', 'message' => 'ลบ E-Book สำเร็จ']);
        exit();
    }

    // =================================================================================
    // 5. POST: ลบหนังสือทั้งเรื่อง (Delete Book Title)
    // =================================================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete_book') {
        $title_id = $_POST['title_id'];

        // ตรวจสอบว่ามีการยืมค้างอยู่ไหม (จากตาราง loan_item -> physical_copy)
        // ถ้าต้องการบังคับห้ามลบถ้ายืมอยู่ ต้องเพิ่ม Query check ตรงนี้

        // 1. ลบ Physical Copies
        $pdo->prepare("DELETE FROM physical_copy WHERE title_id = :id")->execute([':id' => $title_id]);

        // 2. ลบ Ebook (ไฟล์และ DB)
        $qEbook = $pdo->prepare("SELECT ebook_file FROM ebook WHERE title_id = :id");
        $qEbook->execute([':id' => $title_id]);
        if ($eRow = $qEbook->fetch()) {
            @unlink($uploadDirEbooks . $eRow['ebook_file']);
        }
        $pdo->prepare("DELETE FROM ebook WHERE title_id = :id")->execute([':id' => $title_id]);

        // 3. ลบรูปภาพปก (ถ้าไม่ใช่ default)
        $qImg = $pdo->prepare("SELECT image FROM book_title WHERE title_id = :id");
        $qImg->execute([':id' => $title_id]);
        if ($iRow = $qImg->fetch()) {
            if ($iRow['image'] != 'default.jpg' && file_exists($uploadDirImages . $iRow['image'])) {
                @unlink($uploadDirImages . $iRow['image']);
            }
        }

        // 4. ลบ Title
        $pdo->prepare("DELETE FROM book_title WHERE title_id = :id")->execute([':id' => $title_id]);

        echo json_encode(['status' => 'success', 'message' => 'ลบข้อมูลหนังสือสำเร็จ']);
        exit();
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
}
