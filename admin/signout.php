<?php
require_once '../config/session_init.php';
session_start();

// ลบ Session ทั้งหมด
session_unset();
session_destroy();

// เปลี่ยนเส้นทางกลับไปยังหน้า Signin
header('Location: signin.php');
exit();
