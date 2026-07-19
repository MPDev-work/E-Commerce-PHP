<?php
session_start();
require_once __DIR__ . '/../../config/connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

$userId = (int) $_SESSION['user_id'];
$userStmt = $conn->prepare('SELECT id, username, email, roles FROM users WHERE id = ?');
$userStmt->bind_param('i', $userId);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

if (!$user) {
    session_destroy();
    header('Location: ../../auth/login.php');
    exit;
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function scalar(mysqli $conn, string $sql, string $field = 'total')
{
    $result = $conn->query($sql);
    $row = $result ? $result->fetch_assoc() : [];
    return $row[$field] ?? 0;
}
