<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$errors = [];
$success = '';

// Fetch current settings
$stmt = $pdo->query('SELECT * FROM settings LIMIT 1');
$settings = $stmt->fetch();

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['logo'])){
    if($_FILES['logo']['error']!==0){
        $errors[]='Please select a valid logo image';
    } else {
        $uploadDir = __DIR__.'/../assets/images/';
        if(!is_dir($uploadDir)) mkdir($uploadDir,0777,true);
        $filename = 'logo_'.time().'_'.basename($_FILES['logo']['name']);
        move_uploaded_file($_FILES['logo']['tmp_name'],$uploadDir.$filename);
        
        if($settings){
            $stmt = $pdo->prepare('UPDATE settings SET logo=? WHERE id=?');
            $stmt->execute(['assets/images/'.$filename,$settings['id']]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO settings (logo) VALUES (?)');
            $stmt->execute(['assets/images/'.$filename]);
        }
        $success='Logo updated successfully';
        $stmt = $pdo->query('SELECT * FROM settings LIMIT 1');
        $settings = $stmt->fetch();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Logo Upload • Cosmetics Admin</title>
<style>
body{font-family:Poppins,sans-serif;background:#fce4ec;color:#121212;margin:20px}
input{padding:10px;margin-bottom:12px;border-radius:8px;border:1px solid #eee}
button{padding:10px 16px;background:#ff80ab;color:white;border:none;border-radius:8px;cursor:pointer}
.errors{color:red;margin-bottom:12px}
.success{color:green;margin-bottom:12px}
img{max-width:150px;margin-bottom:12px}
</style>
</head>
<body>
<h2>Upload Logo</h2>
<?php if($errors): ?><div class="errors"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div><?php endif; ?>
<?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
<?php if(!empty($settings['logo'])): ?>
<img src="../<?= $settings['logo'] ?>" alt="Logo">
<?php endif; ?>
<form method="post" enctype="multipart/form-data">
<input type="file" name="logo" required>
<button type="submit">Upload</button>
</form>
</body>
</html>
