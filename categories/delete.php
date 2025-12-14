<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$id = $_GET['id'] ?? 0;
if(!$id) exit('Category ID missing');

// Optionally: Check if category has products before deletion
$stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE category_id = ?');
$stmt->execute([$id]);
$count = $stmt->fetchColumn();
if($count > 0){
    $_SESSION['category_success'] = "Cannot delete category. It has $count product(s) assigned.";
    header('Location: index.php');
    exit;
}

// Safe to delete
$pdo->prepare('DELETE FROM categories WHERE id=?')->execute([$id]);
$_SESSION['category_success'] = "Category deleted successfully.";
header('Location: index.php');
exit;
