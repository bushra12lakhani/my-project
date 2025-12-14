<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

// Low stock products (<10)
$lowStock = $pdo->query('
    SELECT p.id, p.name, c.name AS category_name, b.name AS brand_name, p.stock_qty
    FROM products p
    JOIN categories c ON p.category_id = c.id
    JOIN brands b ON p.brand_id = b.id
    WHERE p.stock_qty < 10
    ORDER BY p.stock_qty ASC
')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Low Stock • Cosmetics Admin</title>
<style>
body{font-family:Poppins,sans-serif;background:#fce4ec;color:#121212;margin:20px}
table{width:100%;border-collapse:collapse;margin-top:20px}
th,td{padding:12px;border-bottom:1px solid #eee;text-align:left}
th{background:#fff0f4}
.low{color:#d32f2f;font-weight:600}
</style>
</head>
<body>
<h2>Low Stock Products (<10)</h2>
<table>
<thead>
<tr><th>ID</th><th>Name</th><th>Category</th><th>Brand</th><th>Stock Qty</th></tr>
</thead>
<tbody>
<?php foreach($lowStock as $p): ?>
<tr>
<td><?= $p['id'] ?></td>
<td><?= htmlspecialchars($p['name']) ?></td>
<td><?= htmlspecialchars($p['category_name']) ?></td>
<td><?= htmlspecialchars($p['brand_name']) ?></td>
<td class="low"><?= $p['stock_qty'] ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</body>
</html>
