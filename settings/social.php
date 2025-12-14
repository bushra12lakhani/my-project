<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$errors = [];
$success = '';

$stmt = $pdo->query('SELECT * FROM settings LIMIT 1');
$settings = $stmt->fetch();

if($_SERVER['REQUEST_METHOD']==='POST'){
    $facebook = trim($_POST['facebook'] ?? '');
    $instagram = trim($_POST['instagram'] ?? '');
    $twitter = trim($_POST['twitter'] ?? '');
    $linkedin = trim($_POST['linkedin'] ?? '');

    if($settings){
        $stmt = $pdo->prepare('UPDATE settings SET facebook=?, instagram=?, twitter=?, linkedin=? WHERE id=?');
        $stmt->execute([$facebook,$instagram,$twitter,$linkedin,$settings['id']]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO settings (facebook, instagram, twitter, linkedin) VALUES (?,?,?,?)');
        $stmt->execute([$facebook,$instagram,$twitter,$linkedin]);
    }
    $success='Social links updated';
    $stmt = $pdo->query('SELECT * FROM settings LIMIT 1');
    $settings = $stmt->fetch();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Social Links • Cosmetics Admin</title>
<style>
body{font-family:Poppins,sans-serif;background:#fce4ec;color:#121212;margin:20px}
input{width:100%;padding:10px;margin-bottom:12px;border-radius:8px;border:1px solid #eee}
button{padding:10px 16px;background:#ff80ab;color:white;border:none;border-radius:8px;cursor:pointer}
.success{color:green;margin-bottom:12px}
</style>
</head>
<body>
<h2>Social Links</h2>
<?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
<form method="post">
<label>Facebook URL</label>
<input type="text" name="facebook" value="<?= htmlspecialchars($settings['facebook'] ?? '') ?>">
<label>Instagram URL</label>
<input type="text" name="instagram" value="<?= htmlspecialchars($settings['instagram'] ?? '') ?>">
<label>Twitter URL</label>
<input type="text" name="twitter" value="<?= htmlspecialchars($settings['twitter'] ?? '') ?>">
<label>LinkedIn URL</label>
<input type="text" name="linkedin" value="<?= htmlspecialchars($settings['linkedin'] ?? '') ?>">
<button type="submit">Save</button>
</form>
</body>
</html>
