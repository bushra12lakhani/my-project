<?php
// login.php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/auth.php';




$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email) $errors[] = 'Please enter a valid email.';
    if (!$password) $errors[] = 'Please enter your password.';

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id, name, email, password_hash FROM admins WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Login • Cosmetics Admin</title>
<style>
/* Minimal styling for testing */
body{font-family:sans-serif;background:#fce4ec;display:flex;justify-content:center;align-items:center;height:100vh;margin:0}
form{background:white;padding:28px;border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,0.08)}
input{display:block;width:100%;margin-bottom:12px;padding:10px;border-radius:8px;border:1px solid #eee}
button{padding:10px 18px;border:none;background:#ff80ab;color:white;border-radius:8px;cursor:pointer}
.errors{color:red;margin-bottom:12px}
</style>
</head>
<body>
<form method="post">
<h2>Admin Login</h2>
<?php if($errors): ?>
<div class="errors">
<?php foreach($errors as $err) echo "<div>$err</div>"; ?>
</div>
<?php endif; ?>
<input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>
</body>
</html>
