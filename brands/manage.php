<?php
$pageTitle = "Manage Brand";    
include __DIR__ . '/../inc/layout.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$id = $_GET['id'] ?? null;
$brand = ['name'=>''];
$errors = [];
$success = '';

if($id){
    $stmt = $pdo->prepare('SELECT * FROM brands WHERE id=?');
    $stmt->execute([$id]);
    $brand = $stmt->fetch();
    if(!$brand) exit('Brand not found');
}

// if($_SERVER['REQUEST_METHOD']==='POST'){
//     $name = trim($_POST['name'] ?? '');
//     if(!$name) $errors[]='Brand name required';

//     if(empty($errors)){
//         if($id){
//             $stmt = $pdo->prepare('UPDATE brands SET name=? WHERE id=?');
//             $stmt->execute([$name,$id]);
//             $success='Brand updated';
//         } else {
//             $stmt = $pdo->prepare('INSERT INTO brands (name) VALUES (?)');
//             $stmt->execute([$name]);
//             $success='Brand added';
//         }
//     }
//     if($_SERVER['REQUEST_METHOD']==='POST'){
//     $name = trim($_POST['name'] ?? '');
//     if(!$name) $errors[]='Brand name required';

//     if(empty($errors)){
//         if($id){
//             $stmt = $pdo->prepare('UPDATE brands SET name=? WHERE id=?');
//             $stmt->execute([$name,$id]);
//             $_SESSION['success'] = 'Brand updated successfully';
//             // $_SESSION['brand_success'] = 'Brand added successfully';

//         } else {
//             $stmt = $pdo->prepare('INSERT INTO brands (name) VALUES (?)');
//             $stmt->execute([$name]);
//             $_SESSION['success'] = 'Brand added successfully';
//             $_SESSION['brand_success'] = 'Brand added successfully';

//         }

//         header('Location: index.php');
//         exit;
//     }
// }

if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = trim($_POST['name'] ?? '');
    if(!$name) $errors[]='Brand name required';

    if(empty($errors)){
        if($id){
            $stmt = $pdo->prepare('UPDATE brands SET name=? WHERE id=?');
            $stmt->execute([$name,$id]);
            $_SESSION['brand_success'] = 'Brand updated successfully';
        } else {
            $stmt = $pdo->prepare('INSERT INTO brands (name) VALUES (?)');
            $stmt->execute([$name]);
            $_SESSION['brand_success'] = 'Brand added successfully';
        }
        header('Location: index.php');
        exit;
    }
}



?>
<!-- <!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= $id?'Edit':'Add' ?> Brand • Cosmetics Admin</title>
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
<h2><?= $id?'Edit':'Add' ?> Brand</h2>
</div>
<?php if($errors): ?><div class="errors"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div><?php endif; ?>
<?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
<form method="post">
<label>Name</label>
<input type="text" name="name" value="<?= htmlspecialchars($brand['name']) ?>" required>
<button type="submit"><?= $id?'Update':'Add' ?></button>
</form>
</div>

