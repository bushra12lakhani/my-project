<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$id = $_GET['id'] ?? 0;
if(!$id) exit('Order ID missing');

// Fetch order
$stmt = $pdo->prepare('
    SELECT o.*, c.name AS customer_name, c.email, c.phone, c.address
    FROM orders o
    JOIN customers c ON o.customer_id = c.id
    WHERE o.id=?
');
$stmt->execute([$id]);
$order = $stmt->fetch();
if(!$order) exit('Order not found');

// Fetch ordered items
$stmt = $pdo->prepare('
    SELECT oi.*, p.name AS product_name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id=?
');
$stmt->execute([$id]);
$items = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>View Order #<?= $order['id'] ?> • Cosmetics Admin</title>
<style>
body{font-family:Poppins,sans-serif;background:#fce4ec;color:#121212;margin:20px}
table{width:100%;border-collapse:collapse;margin-top:12px}
th,td{padding:12px;border-bottom:1px solid #eee;text-align:left}
th{background:#fff0f4}
h2,h3{margin-top:20px}
</style>
</head>
<body>
<h2>Order #<?= $order['id'] ?></h2>
<h3>Customer Info</h3>
<p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?><br>
<strong>Email:</strong> <?= htmlspecialchars($order['email']) ?><br>
<strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?><br>
<strong>Address:</strong> <?= htmlspecialchars($order['address']) ?></p>

<h3>Ordered Products</h3>
<table>
<thead>
<tr><th>Product</th><th>Quantity</th><th>Price ($)</th><th>Total ($)</th></tr>
</thead>
<tbody>
<?php foreach($items as $item): ?>
<tr>
<td><?= htmlspecialchars($item['product_name']) ?></td>
<td><?= $item['quantity'] ?></td>
<td><?= number_format($item['price'],2) ?></td>
<td><?= number_format($item['price']*$item['quantity'],2) ?></td>
</tr>
<?php endforeach; ?>
<tr>
<td colspan="3" style="text-align:right"><strong>Total:</strong></td>
<td><strong>$<?= number_format($order['total_amount'],2) ?></strong></td>
</tr>
</tbody>
</table>
</body>
</html>
