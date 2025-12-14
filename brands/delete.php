<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$id = $_GET['id'] ?? 0;
if(!$id) exit('Brand ID missing');
$pdo->prepare('DELETE FROM brands WHERE id=?')->execute([$id]);
header('Location: index.php');
exit;
