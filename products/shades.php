<?php
$pageTitle = 'Shades';
include __DIR__ . '/../inc/layout.php';

$product_id = $_GET['product_id'] ?? 0;
if(!$product_id) exit('Product ID missing');

$product = $pdo->prepare('SELECT * FROM products WHERE id=?');
$product->execute([$product_id]);
$product = $product->fetch();
if(!$product) exit('Product not found');

$shades = $pdo->prepare('SELECT * FROM product_shades WHERE product_id=?');
$shades->execute([$product_id]);
$shades = $shades->fetchAll();

$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $shade_name = $_POST['shade_name'] ?? '';
    $shade_color = $_POST['shade_color'] ?? '';
    $stock_qty = $_POST['stock_qty'] ?? 0;
    $extra_price = $_POST['extra_price'] ?? 0;

    if(!$shade_name) $errors[]='Shade name required';
    if(empty($errors)){
        $stmt=$pdo->prepare('INSERT INTO product_shades (product_id,shade_name,shade_color,extra_price,stock_qty) VALUES (:pid,:name,:color,:price,:stock)');
        $stmt->execute(['pid'=>$product_id,'name'=>$shade_name,'color'=>$shade_color,'price'=>$extra_price,'stock'=>$stock_qty]);
        header("Location: shades.php?product_id=$product_id");exit;
    }
}
?>

<div class="card">
<h2>Shades for: <?= htmlspecialchars($product['name']) ?></h2>

<?php if($errors): ?><div class="errors"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div><?php endif; ?>

<form method="post" style="display:flex;gap:8px;flex-wrap:wrap;">
<input type="text" name="shade_name" placeholder="Shade Name" required>
<input type="color" name="shade_color" value="#ffffff">
<input type="number" name="stock_qty" placeholder="Stock" min="0">
<input type="number" step="0.01" name="extra_price" placeholder="Extra Price">
<button type="submit">Add Shade</button>
</form>

<table>
<thead><tr><th>Name</th><th>Color</th><th>Stock</th><th>Extra Price</th></tr></thead>
<tbody>
<?php foreach($shades as $s): ?>
<tr>
<td><?= htmlspecialchars($s['shade_name']) ?></td>
<td style="background:<?= $s['shade_color'] ?>;width:40px"></td>
<td><?= $s['stock_qty'] ?></td>
<td>$<?= number_format($s['extra_price'],2) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
