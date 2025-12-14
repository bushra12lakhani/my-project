<?php

$pageTitle = "Add Banner";
include __DIR__ . '/../inc/layout.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$errors = [];
$success = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $title = trim($_POST['title'] ?? '');
    if(!$title) $errors[]='Title is required';

    if(!isset($_FILES['image']) || $_FILES['image']['error']!==0){
        $errors[]='Image is required';
    }

    if(empty($errors)){
        $uploadDir = __DIR__.'/../assets/images/banners/';
        if(!is_dir($uploadDir)) mkdir($uploadDir,0777,true);

        $filename = time().'_'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'],$uploadDir.$filename);

        $stmt = $pdo->prepare('INSERT INTO banners (title,image_path) VALUES (?,?)');
        $stmt->execute([$title,'assets/images/banners/'.$filename]);
        $success='Banner added successfully';
    }
}
?>
<!-- <!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Add Banner • Cosmetics Admin</title>
<style>
body{font-family:Poppins,sans-serif;background:#fce4ec;color:#121212;margin:20px}
input{width:100%;padding:10px;margin-bottom:12px;border-radius:8px;border:1px solid #eee}
button{padding:10px 16px;background:#ff80ab;color:white;border:none;border-radius:8px;cursor:pointer}
.errors{color:red;margin-bottom:12px}
.success{color:green;margin-bottom:12px}
</style>
</head>
<body> -->
<div class="card">
    <div class="card-header">
<h2>Add Banner</h2>
</div>
<?php if($errors): ?><div class="errors"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div><?php endif; ?>
<?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
<label>Title</label>
<input type="text" name="title" required>
<label>Image</label>
<input type="file" name="image" required>
<button type="submit">Add Banner</button>
</form>
</div>
