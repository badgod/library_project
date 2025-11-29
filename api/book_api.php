<?php
// api/book_api.php
header('Content-Type: application/json');
require_once '../config/connectdb.php';
require_once '../config/appconfig.php';

$action = $_GET['action'] ?? '';

try {
    if ($action === 'new_arrivals') {

        $sql = "SELECT *, (SELECT COUNT(*) FROM ebook WHERE title_id = book_title.title_id) as has_ebook 
                FROM book_title ORDER BY title_id DESC LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } elseif ($action === 'popular_books') {
        // เพิ่ม has_ebook ใน select
        $sql = "SELECT b.*, COUNT(li.loan_item_id) as borrow_count,
                (SELECT COUNT(*) FROM ebook WHERE title_id = b.title_id) as has_ebook
                FROM book_title b 
                LEFT JOIN physical_copy pc ON b.title_id = pc.title_id 
                LEFT JOIN loan_item li ON pc.copy_id = li.copy_id 
                GROUP BY b.title_id 
                ORDER BY borrow_count DESC, RAND() 
                LIMIT 10";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'data' => $data]);
    } elseif ($action === 'get_categories') {
        $stmt = $pdo->prepare("SELECT * FROM category ORDER BY name ASC");
        $stmt->execute();
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } elseif ($action === 'search_books') {
        $search = $_GET['q'] ?? '';
        $cat = $_GET['cat'] ?? '';

        // เพิ่ม has_ebook
        $sql = "SELECT b.*, c.name as category_name,
                (SELECT COUNT(*) FROM ebook WHERE title_id = b.title_id) as has_ebook
                FROM book_title b 
                LEFT JOIN category c ON b.category_id = c.category_id 
                WHERE 1=1 ";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if (!empty($cat)) {
            $sql .= " AND b.category_id = ?";
            $params[] = $cat;
        }

        $sql .= " ORDER BY b.title_id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } elseif ($action === 'book_detail') {
        $id = $_GET['id'] ?? 0;

        $sql = "SELECT b.*, c.name as category_name,
                (SELECT COUNT(*) FROM ebook WHERE title_id = b.title_id) as has_ebook,
                (SELECT ebook_id FROM ebook WHERE title_id = b.title_id LIMIT 1) as ebook_id
                FROM book_title b 
                LEFT JOIN category c ON b.category_id = c.category_id 
                WHERE b.title_id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book) {
            $stmtCopy = $pdo->prepare("SELECT count(*) as total, 
                SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available 
                FROM physical_copy WHERE title_id = ?");
            $stmtCopy->execute([$id]);
            $status = $stmtCopy->fetch(PDO::FETCH_ASSOC);

            $book['copies_total'] = $status['total'];
            $book['copies_available'] = $status['available'];

            echo json_encode(['status' => 'success', 'data' => $book]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Book not found']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}