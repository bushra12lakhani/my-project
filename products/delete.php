<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$id = $_GET['id'] ?? 0;
if(!$id) exit('Product ID missing');

// Delete product images first
$pdo->prepare('DELETE FROM product_images WHERE product_id=?')->execute([$id]);
// Delete product shades
$pdo->prepare('DELETE FROM product_shades WHERE product_id=?')->execute([$id]);
// Delete product itself
$pdo->prepare('DELETE FROM products WHERE id=?')->execute([$id]);

header('Location: index.php');
exit;
