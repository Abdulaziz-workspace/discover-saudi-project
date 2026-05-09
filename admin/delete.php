<?php
require_once __DIR__ . '/../config.php';
require __DIR__ . '/auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: dashboard.php?msg=not_found');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM regions WHERE id = :id");
$stmt->execute([':id' => $id]);

if ($stmt->rowCount() > 0) {
    header('Location: dashboard.php?msg=deleted');
} else {
    header('Location: dashboard.php?msg=not_found');
}
exit;
