<?php
// Simple CV upload handler
$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['cv'])) {
    $file = $_FILES['cv'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        header('Location: index.html?error=upload');
        exit;
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        header('Location: index.html?error=size');
        exit;
    }
    $allowed = ['pdf','doc','docx'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        header('Location: index.html?error=type');
        exit;
    }
    $safeName = 'cv.' . $ext;
    $target = $uploadDir . '/' . $safeName;
    if (!move_uploaded_file($file['tmp_name'], $target)) {
        header('Location: index.html?error=move');
        exit;
    }
    // record latest filename for the frontend
    @file_put_contents($uploadDir . '/latest_cv.txt', $safeName);
    header('Location: index.html?success=1');
    exit;
}

header('Location: index.html');
exit;
