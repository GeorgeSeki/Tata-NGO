<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
$msg = '';
$ok = false;
if (isset($_SESSION['message']) && $_SESSION['message'] !== '') {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
    $ok = true;
}
echo json_encode(['ok' => $ok, 'message' => $msg]);
