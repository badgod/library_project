<?php
require_once '../config/session_init.php';
session_start();
require_once '../config/connectdb.php';

// 1. ตรวจสอบว่าล็อกอินหรือยัง
if (!isset($_SESSION['member_id'])) {
    // ส่ง HTML กลับไปเพื่อรัน Script แจ้งเตือน
?>
    <!DOCTYPE html>
    <html lang="th">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <style>
            /* Bold (700) */
            @font-face {
                font-family: "IBM Plex Sans Thai";
                src: url("../fonts/IBM_Plex_Sans_Thai/IBMPlexSansThai-Bold.ttf") format("truetype");
                font-weight: 700;
                font-style: normal;
            }

            /* SemiBold (600) */
            @font-face {
                font-family: "IBM Plex Sans Thai";
                src: url("../fonts/IBM_Plex_Sans_Thai/IBMPlexSansThai-SemiBold.ttf") format("truetype");
                font-weight: 600;
                font-style: normal;
            }

            /* Medium (500) */
            @font-face {
                font-family: "IBM Plex Sans Thai";
                src: url("../fonts/IBM_Plex_Sans_Thai/IBMPlexSansThai-Medium.ttf") format("truetype");
                font-weight: 500;
                font-style: normal;
            }

            /* Regular (400) */
            @font-face {
                font-family: "IBM Plex Sans Thai";
                src: url("../fonts/IBM_Plex_Sans_Thai/IBMPlexSansThai-Regular.ttf") format("truetype");
                font-weight: 400;
                font-style: normal;
            }

            /* Light (300) */
            @font-face {
                font-family: "IBM Plex Sans Thai";
                src: url("../fonts/IBM_Plex_Sans_Thai/IBMPlexSansThai-Light.ttf") format("truetype");
                font-weight: 300;
                font-style: normal;
            }

            /* ExtraLight (200) */
            @font-face {
                font-family: "IBM Plex Sans Thai";
                src: url("../fonts/IBM_Plex_Sans_Thai/IBMPlexSansThai-ExtraLight.ttf") format("truetype");
                font-weight: 200;
                font-style: normal;
            }

            /* Thin (100) */
            @font-face {
                font-family: "IBM Plex Sans Thai";
                src: url("../fonts/IBM_Plex_Sans_Thai/IBMPlexSansThai-Thin.ttf") format("truetype");
                font-weight: 100;
                font-style: normal;
            }

            /* ------------------------------------------- */
            /* 2. GLOBAL STYLES (กำหนดให้ฟอนต์ใช้งาน) */
            /* ------------------------------------------- */

            /* กำหนดให้ฟอนต์ IBM Plex Sans Thai เป็นฟอนต์หลักของ Body */
            body {
                font-family: "IBM Plex Sans Thai", sans-serif;
            }
        </style>
    </head>

    <body>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเข้าสู่ระบบ',
                    text: 'คุณต้องเข้าสู่ระบบสมาชิกก่อนอ่าน E-Book',
                    confirmButtonText: 'ไปหน้าเข้าสู่ระบบ',
                    confirmButtonColor: '#0d6efd',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ใช้ window.top.location เพื่อ Redirect หน้าหลัก (กรณีอยู่ใน Iframe)
                        // ถอยกลับ 1 ชั้นจากโฟลเดอร์ api/ ไปหา signin
                        window.top.location.href = '../signin';
                    }
                });
            });
        </script>
    </body>

    </html>
<?php
    exit();
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
