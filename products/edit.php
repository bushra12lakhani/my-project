<?php
$pageTitle = 'Edit Product';
include __DIR__ . '/../inc/layout.php';

$id = $_GET['id'] ?? 0;
if(!$id) exit('Product ID missing');
$product = $pdo->prepare('SELECT * FROM products WHERE id=?');
$product->execute([$id]);
$product = $product->fetch();
if(!$product) exit('Product not found');

$categories = $pdo->query('SELECT id,name FROM categories')->fetchAll();
$brands = $pdo->query('SELECT id,name FROM brands')->fetchAll();

$errors=[];$success='';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $brand_id = $_POST['brand_id'] ?? '';
    $price = $_POST['price'] ?? '';
    $stock_qty = $_POST['stock_qty'] ?? 0;
    $description = $_POST['description'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? null;
    $active = isset($_POST['active']) ? 1 : 0;

    if(!$name) $errors[]='Product name required';
    if(!$category_id) $errors[]='Select category';
    if(!$brand_id) $errors[]='Select brand';
    if(!$price || !is_numeric($price)) $errors[]='Valid price required';

    if(empty($errors)){
        $stmt = $pdo->prepare('UPDATE products SET name=:name,category_id=:cat,brand_id=:brand,price=:price,description=:desc,expiry_date=:expiry,stock_qty=:stock,active=:active WHERE id=:id');
        $stmt->execute([
            'name'=>$name,'cat'=>$category_id,'brand'=>$brand_id,'price'=>$price,'desc'=>$description,
            'expiry'=>$expiry_date,'stock'=>$stock_qty,'active'=>$active,'id'=>$id
        ]);
        $success='Product updated successfully';
    }
}
?>

<div class="card">
<h2>Edit Product</h2>
<?php if($errors): ?><div class="errors"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div><?php endif; ?>
<?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>

<form method="post">
<label>Name</label><input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
<label>Category</label>
<select name="category_id" required>
<option value="">--Select--</option>
<?php foreach($categories as $cat): ?>
<option value="<?= $cat['id'] ?>" <?= $cat['id']==$product['category_id']?'selected':'' ?>><?= htmlspecialchars($cat['name']) ?></option>
<?php endforeach; ?>
</select>
<label>Brand</label>
<select name="brand_id" required>
<option value="">--Select--</option>
<?php foreach($brands as $b): ?>
<option value="<?= $b['id'] ?>" <?= $b['id']==$product['brand_id']?'selected':'' ?>><?= htmlspecialchars($b['name']) ?></option>
<?php endforeach; ?>
</select>
<label>Price ($)</label><input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
<label>Stock Quantity</label><input type="number" name="stock_qty" value="<?= $product['stock_qty'] ?>" required>
<label>Expiry Date</label><input type="date" name="expiry_date" value="<?= $product['expiry_date'] ?>">
<label>Description</label><textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
<label><input type="checkbox" name="active" <?= $product['active']?'checked':'' ?>> Active</label>
<button type="submit">Update Product</button>
</form>
</div>
</div></body></html>
