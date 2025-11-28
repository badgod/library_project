<?php
// จัดการเรื่อง Login และ Session
function checkMemberLogin()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['member_login']) || $_SESSION['member_login'] !== true) {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        } else {
            header('Location: signin');
        }
        exit();
    }
}
