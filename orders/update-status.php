<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$id = $_GET['id'] ?? 0;
if(!$id) exit('Order ID missing');

$stmt = $pdo->prepare('SELECT * FROM orders WHERE id=?');
$stmt->execute([$id]);
$order = $stmt->fetch();
if(!$order) exit('Order not found');

$statuses = ['Pending','Processing','Shipped','Delivered','Cancelled'];
$success='';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $newStatus = $_POST['status'] ?? $order['status'];
    if(in_array($newStatus,$statuses)){
        $stmt = $pdo->prepare('UPDATE orders SET status=? WHERE id=?');
        $stmt->execute([$newStatus,$id]);
        $success='Order status updated';
        $order['status']=$newStatus;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Update Order Status #<?= $order['id'] ?> • Cosmetics Admin</title>
<style>
body{font-family:Poppins,sans-serif;background:#fce4ec;color:#121212;margin:20px}
select{padding:10px;border-radius:8px;border:1px solid #eee;margin-bottom:12px}
button{padding:10px 16px;background:#ff80ab;color:white;border:none;border-radius:8px;cursor:pointer}
.success{color:green;margin-bottom:12px}
</style>
</head>
<body>
<h2>Update Order Status #<?= $order['id'] ?></h2>
<?php if($success) echo "<div class='success'>$success</div>"; ?>
<form method="post">
<label>Status</label>
<select name="status">
<?php foreach($statuses as $s): ?>
<option value="<?= $s ?>" <?= $s==$order['status']?'selected':'' ?>><?= $s ?></option>
<?php endforeach; ?>
</select>
<button type="submit">Update</button>
</form>
</body>
</html>
