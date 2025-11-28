<?php
// ตรวจสอบว่ามีการเปิด Session หรือยัง ถ้ายังให้เปิด (เผื่อกรณีไฟล์นี้ถูกเรียกโดยตรง)
if (session_status() === PHP_SESSION_NONE) {
    // ปรับ path ให้ถูกต้องตามที่ไฟล์นี้ตั้งอยู่จริง (relative path)
    require_once '../config/session_init.php';
    session_start();
}

// ลบ Session ทั้งหมด
session_unset();
session_destroy();

// เปลี่ยนเส้นทางกลับไปยังหน้า Index (Home)
header('Location: index');
exit();
?>