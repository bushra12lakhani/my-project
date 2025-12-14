<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

// Fetch all products with stock info
$products = $pdo->query('
    SELECT p.id, p.name, c.name AS category_name, b.name AS brand_name, p.stock_qty, p.expiry_date
    FROM products p
    JOIN categories c ON p.category_id = c.id
    JOIN brands b ON p.brand_id = b.id
    ORDER BY p.name ASC
')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Stock Levels • Cosmetics Admin</title>
<style>
body{font-family:Poppins,sans-serif;background:#fce4ec;color:#121212;margin:20px}
table{width:100%;border-collapse:collapse;margin-top:20px}
th,td{padding:12px;border-bottom:1px solid #eee;text-align:left}
th{background:#fff0f4}
.low{color:#d32f2f;font-weight:600}
</style>
</head>
<body>
<h2>Product Stock Levels</h2>
<table>
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Category</th>
<th>Brand</th>
<th>Stock Qty</th>
<th>Expiry Date</th>
</tr>
</thead>
<tbody>
<?php foreach($products as $p): ?>
<tr>
<td><?= $p['id'] ?></td>
<td><?= htmlspecialchars($p['name']) ?></td>
<td><?= htmlspecialchars($p['category_name']) ?></td>
<td><?= htmlspecialchars($p['brand_name']) ?></td>
<td class="<?= $p['stock_qty']<10?'low':'' ?>"><?= $p['stock_qty'] ?></td>
<td><?= $p['expiry_date'] ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</body>
</html>
