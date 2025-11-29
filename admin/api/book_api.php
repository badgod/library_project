<?php
// admin/api/book_api.php
header('Content-Type: application/json');
require_once '../../config/session_init.php';
session_start();

require_once '../../config/appconfig.php';
require_once '../../config/connectdb.php';
require_once '../services/security.php';
require_once '../services/class.upload.php';

checkAdminLogin();

$action = $_REQUEST['action'] ?? '';

$uploadDirImages = '../../assets/images/';
$uploadDirEbooks = '../../uploads/ebooks/';

try {
    // ... (ส่วน GET คงเดิม ไม่ต้องแก้) ...
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($action === 'read_all') {
            $sql = "SELECT 
                        b.*, 
                        c.name as category_name,
                        (SELECT COUNT(*) FROM physical_copy p WHERE p.title_id = b.title_id) as total_copies,
                        (SELECT COUNT(*) FROM physical_copy p WHERE p.title_id = b.title_id AND p.status = 'available') as available_copies,
                        e.ebook_file,
                        e.ebook_id
                    FROM book_title b
                    LEFT JOIN category c ON b.category_id = c.category_id
                    LEFT JOIN ebook e ON b.title_id = e.title_id
                    ORDER BY b.title_id DESC";
            $stmt = $pdo->query($sql);
            echo json_encode(['data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            exit();
        }
        if ($action === 'get_copies') {
            $title_id = $_GET['title_id'];
            $stmt = $pdo->prepare("SELECT * FROM physical_copy WHERE title_id = :id ORDER BY accession_no ASC");
            $stmt->execute([':id' => $title_id]);
            echo json_encode(['data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            exit();
        }
    }

    // ... (ส่วน POST create_book แก้ไขตรงนี้) ...
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'create_book' || $action === 'update_book')) {

        $title = trim($_POST['title']);
        $category_id = $_POST['category_id'];
        $author = trim($_POST['author']);
        $isbn = trim($_POST['isbn']);
        $description = trim($_POST['description']);

        if (empty($title) || empty($category_id)) {
            echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกชื่อเรื่องและเลือกหมวดหมู่']);
            exit();
        }

        // เปลี่ยน default image เป็น blank_cover_book.jpg ถ้าไม่มีการส่งมา
        $imageName = ($action === 'update_book') ? $_POST['old_image'] : 'blank_cover_book.jpg';

        if (!empty($_FILES['image']['name'])) {
            $handle = new \Verot\Upload\Upload($_FILES['image']);
            if ($handle->uploaded) {
                $handle->file_new_name_body   = 'book_' . time() . '_' . rand(100, 999);
                $handle->image_resize         = true;
                $handle->image_x              = 500;
                $handle->image_ratio_y        = true;
                $handle->allowed = array('image/*');
                $handle->process($uploadDirImages);

                if ($handle->processed) {
                    $imageName = $handle->file_dst_name;
                    $handle->clean();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Upload Error: ' . $handle->error]);
                    exit();
                }
            }
        }

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
            
            // [แก้ไข] ดึง ID ล่าสุดและส่งกลับไป
            $new_id = $pdo->lastInsertId();
            echo json_encode(['status' => 'success', 'message' => 'เพิ่มหนังสือเรียบร้อย', 'new_id' => $new_id]);

        } else if ($action === 'update_book') {
            // ... (Code Update เดิม) ...
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

    // ... (ส่วนอื่นๆ Copy/Upload Ebook/Delete คงเดิม) ...
    // Copy ส่วนที่เหลือจากไฟล์เดิมของคุณมาใส่ต่อท้ายได้เลยครับ
    // (Add Copy, Delete Copy, Upload Ebook, Delete Ebook, Delete Book)
    
    // --- Code เดิมส่วนจัดการ Copy ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add_copy') {
        $title_id = $_POST['title_id'];
        $accession_no = trim($_POST['accession_no']);
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
        $stmt = $pdo->prepare("DELETE FROM physical_copy WHERE copy_id = :id");
        $stmt->execute([':id' => $copy_id]);
        echo json_encode(['status' => 'success', 'message' => 'ลบเล่มหนังสือสำเร็จ']);
        exit();
    }

    // --- Code เดิมส่วนจัดการ E-Book ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'upload_ebook') {
        $title_id = $_POST['title_id'];
        if (empty($_FILES['ebook_file']['name'])) {
            echo json_encode(['status' => 'error', 'message' => 'กรุณาเลือกไฟล์ PDF']);
            exit();
        }
        $handle = new \Verot\Upload\Upload($_FILES['ebook_file']);
        if ($handle->uploaded) {
            if ($handle->file_src_name_ext != 'pdf') {
                echo json_encode(['status' => 'error', 'message' => 'อนุญาตเฉพาะไฟล์ PDF เท่านั้น']);
                exit();
            }
            $handle->file_new_name_body = 'ebook_' . $title_id . '_' . time();
            $handle->file_overwrite = true;
            $handle->process($uploadDirEbooks);
            if ($handle->processed) {
                $fileName = $handle->file_dst_name;
                $handle->clean();
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
        $stmt = $pdo->prepare("SELECT ebook_file FROM ebook WHERE title_id = :tid");
        $stmt->execute([':tid' => $title_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $filePath = $uploadDirEbooks . $row['ebook_file'];
            if (file_exists($filePath)) { @unlink($filePath); }
            $del = $pdo->prepare("DELETE FROM ebook WHERE title_id = :tid");
            $del->execute([':tid' => $title_id]);
        }
        echo json_encode(['status' => 'success', 'message' => 'ลบ E-Book สำเร็จ']);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete_book') {
        $title_id = $_POST['title_id'];
        $pdo->prepare("DELETE FROM physical_copy WHERE title_id = :id")->execute([':id' => $title_id]);
        $qEbook = $pdo->prepare("SELECT ebook_file FROM ebook WHERE title_id = :id");
        $qEbook->execute([':id' => $title_id]);
        if ($eRow = $qEbook->fetch()) { @unlink($uploadDirEbooks . $eRow['ebook_file']); }
        $pdo->prepare("DELETE FROM ebook WHERE title_id = :id")->execute([':id' => $title_id]);
        $qImg = $pdo->prepare("SELECT image FROM book_title WHERE title_id = :id");
        $qImg->execute([':id' => $title_id]);
        if ($iRow = $qImg->fetch()) {
            if ($iRow['image'] != 'default.jpg' && $iRow['image'] != 'blank_cover_book.jpg' && file_exists($uploadDirImages . $iRow['image'])) {
                @unlink($uploadDirImages . $iRow['image']);
            }
        }
        $pdo->prepare("DELETE FROM book_title WHERE title_id = :id")->execute([':id' => $title_id]);
        echo json_encode(['status' => 'success', 'message' => 'ลบข้อมูลหนังสือสำเร็จ']);
        exit();
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
}
?>