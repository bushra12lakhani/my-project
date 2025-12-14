<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$id = $_GET['id'] ?? 0;
if(!$id) exit('Banner ID missing');

// Delete image file
$stmt = $pdo->prepare('SELECT image_path FROM banners WHERE id=?');
$stmt->execute([$id]);
$banner = $stmt->fetch();
if($banner && file_exists(__DIR__.'/../'.$banner['image_path'])){
    unlink(__DIR__.'/../'.$banner['image_path']);
}

// Delete banner from DB
$pdo->prepare('DELETE FROM banners WHERE id=?')->execute([$id]);
header('Location: index.php');
exit;
