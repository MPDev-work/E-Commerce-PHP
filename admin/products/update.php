<?php
require_once __DIR__ . '/../../config/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../page/products.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['p_title'] ?? '');
$price = (float)($_POST['p_price'] ?? 0);
$qty = max(0, (int)($_POST['p_qty'] ?? 0));
$description = trim($_POST['description'] ?? '');

if (!$id || $title === '') {
    header('Location: edit.php?id=' . $id);
    exit;
}

/* Read the current value from the database instead of trusting a posted path. */
$currentStmt = $conn->prepare('SELECT p_image FROM products WHERE p_id = ?');
$currentStmt->bind_param('i', $id);
$currentStmt->execute();
$current = $currentStmt->get_result()->fetch_assoc();
if (!$current) {
    header('Location: ../page/products.php');
    exit;
}

$imageName = basename((string)($current['p_image'] ?? ''));
$oldImageName = $imageName;
$uploadedNewImage = false;

if (isset($_FILES['p_image']) && $_FILES['p_image']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['p_image']['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($_FILES['p_image']['tmp_name'])) {
        header('Location: edit.php?id=' . $id . '&error=image');
        exit;
    }

    $extension = strtolower(pathinfo($_FILES['p_image']['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extension, $allowedExtensions, true)) {
        header('Location: edit.php?id=' . $id . '&error=image');
        exit;
    }

    $imageName = bin2hex(random_bytes(12)) . '.' . $extension;
    $destination = __DIR__ . '/../../images/' . $imageName;
    if (!move_uploaded_file($_FILES['p_image']['tmp_name'], $destination)) {
        header('Location: edit.php?id=' . $id . '&error=image');
        exit;
    }
    $uploadedNewImage = true;
}

$stmt = $conn->prepare('UPDATE products SET p_title = ?, p_price = ?, p_qty = ?, description = ?, p_image = ? WHERE p_id = ?');
$stmt->bind_param('sdissi', $title, $price, $qty, $description, $imageName, $id);

if (!$stmt->execute()) {
    if ($uploadedNewImage && is_file(__DIR__ . '/../../images/' . $imageName)) {
        unlink(__DIR__ . '/../../images/' . $imageName);
    }
    header('Location: edit.php?id=' . $id . '&error=save');
    exit;
}

/* Remove only the old, local file after the product references the new one. */
if ($uploadedNewImage && $oldImageName && $oldImageName !== $imageName) {
    $oldPath = __DIR__ . '/../../images/' . $oldImageName;
    if (is_file($oldPath)) {
        unlink($oldPath);
    }
}

header('Location: ../page/products.php');
exit;
?>
