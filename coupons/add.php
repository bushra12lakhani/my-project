<?php
$pageTitle = "Add Coupon";
include __DIR__ . '/../inc/layout.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$errors = [];
$success = '';



if($_SERVER['REQUEST_METHOD']==='POST'){
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $discount_percent = floatval($_POST['discount'] ?? 0);
    $min_purchase = floatval($_POST['min_purchase'] ?? 0);
    $expiry_date = $_POST['expiry'] ?? '';
    $status = $_POST['status'] ?? 'Active';

    // Validation
    $errors = [];
    if(!$code) $errors[]='Coupon code required';
    if($discount_percent <= 0) $errors[]='Discount must be greater than 0';
    if(!$expiry_date) $errors[]='Expiry date required';

    if(empty($errors)){
    // Check if code already exists
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM coupons WHERE code = ?");
    $stmt_check->execute([$code]);
    if($stmt_check->fetchColumn() > 0){
        $errors[] = "Coupon code '$code' already exists";
    } else {
        $stmt = $pdo->prepare('INSERT INTO coupons (code, discount_percent, min_purchase, expiry_date, status) VALUES (?,?,?,?,?)');
        $stmt->execute([$code, $discount_percent, $min_purchase, $expiry_date, $status]);
        header("Location: index.php"); 
        exit;
    }
}

}


?>

<div class="card">
    <div class="card-header">
<h2>Add Coupon</h2>
</div>
<?php if($errors): ?><div class="errors"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div><?php endif; ?>
<?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
<form method="post">
<label>Coupon Code</label>
<input type="text" name="code" required>
<label>Discount (%)</label>
<input type="number" name="discount" step="0.01" required>
<label>Minimum Purchase ($)</label>
<input type="number" name="min_purchase" step="0.01" value="0">
<label>Expiry Date</label>
<input type="date" name="expiry" required>
<label>Status</label>
<select name="status">
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>
<button type="submit" class="btn btn-primary">Add Coupon</button>
</form>
</div>
