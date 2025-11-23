<?php
// จัดการเรื่อง Login และ Session
function checkAdminLogin()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        } else {
            header('Location: signin.php');
        }
        exit();
    }
}
