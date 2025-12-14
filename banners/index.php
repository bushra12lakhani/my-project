<?php
$pageTitle = "Banners";
include __DIR__ . '/../inc/layout.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

// Fetch banners
$banners = $pdo->query('SELECT * FROM banners ORDER BY created_at DESC')->fetchAll();
?>
<!-- <!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Banners • Cosmetics Admin</title>
<style>
body{font-family:Poppins,sans-serif;background:#fce4ec;color:#121212;margin:20px}
a{color:#ff80ab;text-decoration:none}
a:hover{text-decoration:underline}
table{width:100%;border-collapse:collapse;margin-top:20px}
th,td{padding:12px;border-bottom:1px solid #eee;text-align:left}
th{background:#fff0f4}
.btn{padding:6px 14px;background:#ff80ab;color:white;border:none;border-radius:6px;cursor:pointer;margin-right:4px}
.btn.delete{background:#d32f2f}
img{width:150px;border-radius:8px;border:1px solid #eee}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
</style>
</head>
<body> -->
<div class="card">
<div class="card-header">
<h2>Banners</h2>
<a href="add.php" class="btn btn-primary">+ Add Banner</a>
</div>

<table class="admin-table">
<thead>
<tr>
<th>ID</th>
<th>Image</th>
<th>Title</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach($banners as $b): ?>
<tr>
<td><?= $b['id'] ?></td>
<td><img src="../<?= $b['image_path'] ?>" alt=""></td>
<td><?= htmlspecialchars($b['title']) ?></td>
<td>
<a href="delete.php?id=<?= $b['id'] ?>" class="btn delete" onclick="return confirm('Are you sure?')">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

