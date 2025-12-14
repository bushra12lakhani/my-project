<?php

$pageTitle = 'Add Product';
include __DIR__ . '/../inc/layout.php';

$errors = [];
$categories = $pdo->query('SELECT id,name FROM categories')->fetchAll();
$brands = $pdo->query('SELECT id,name FROM brands')->fetchAll();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $category_id = $_POST['category_id'];
    $brand_id = $_POST['brand_id'];
    $price = $_POST['price'];
    $stock_qty = $_POST['stock_qty'];
    $description = $_POST['description'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? null;
    $active = isset($_POST['active']) ? 1 : 0;

    if(!$name) $errors[] = 'Product name required';
    if(!$category_id) $errors[] = 'Select category';
    if(!$brand_id) $errors[] = 'Select brand';
    if(!$price || !is_numeric($price)) $errors[] = 'Valid price required';

    if(empty($errors)){
        $stmt = $pdo->prepare("
            INSERT INTO products
            (name, category_id, brand_id, price, description, expiry_date, stock_qty, active)
            VALUES (?,?,?,?,?,?,?,?)
        ");
        $stmt->execute([
            $name,$category_id,$brand_id,$price,
            $description,$expiry_date,$stock_qty,$active
        ]);

        $_SESSION['product_success'] = 'Product added successfully';
        header('Location: index.php');
        exit;
    }
}
?>

<!-- <?php include '../inc/sidebar.php'; ?>
<div class="main"> -->
<div class="card">
<h2>Add Product</h2>

<?php if($errors): ?>
<div class="errors">
<?php foreach($errors as $e): ?><div><?= $e ?></div><?php endforeach; ?>
</div>
<?php endif; ?>

<form method="post">
<label>Name</label><input type="text" name="name" required>

<label>Category</label>
<select name="category_id" required>
<option value="">--Select--</option>
<?php foreach($categories as $c): ?>
<option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
<?php endforeach; ?>
</select>

<label>Brand</label>
<select name="brand_id" required>
<option value="">--Select--</option>
<?php foreach($brands as $b): ?>
<option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
<?php endforeach; ?>
</select>

<label>Price</label><input type="number" step="0.01" name="price" required>
<label>Stock</label><input type="number" name="stock_qty" required>
<label>Expiry Date</label><input type="date" name="expiry_date">
<label>Description</label><textarea name="description"></textarea>

<label><input type="checkbox" name="active" checked> Active</label>

<button type="submit">Save Product</button>
</form>
</div>
<!-- </div> -->
