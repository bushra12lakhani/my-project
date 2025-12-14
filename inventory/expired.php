<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

// Products expiring within 30 days
$today = date('Y-m-d');
$next30 = date('Y-m-d', strtotime('+30 days'));

$stmt = $pdo->prepare('
    SELECT p.id, p.name, c.name AS category_name, b.name AS brand_name, p.expiry_date
    FROM products p
    JOIN categories c ON p.category_id = c.id
    JOIN brands b ON p.brand_id = b.id
    WHERE p.expiry_date BETWEEN ? AND ?
    ORDER BY p.expiry_date ASC
');
$stmt->execute([$today, $next30]);
$expiring = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Expiring Products • Cosmetics Admin</title>
<style>
body{font-family:Poppins,sans-serif;background:#fce4ec;color:#121212;margin:20px}
table{width:100%;border-collapse:collapse;margin-top:20px}
th,td{padding:12px;border-bottom:1px solid #eee;text-align:left}
th{background:#fff0f4}
.expiring{color:#d32f2f;font-weight:600}
</style>
</head>
<body>
<h2>Products Expiring Soon (Next 30 Days)</h2>
<table>
<thead>
<tr><th>ID</th><th>Name</th><th>Category</th><th>Brand</th><th>Expiry Date</th></tr>
</thead>
<tbody>
<?php foreach($expiring as $p): ?>
<tr>
<td><?= $p['id'] ?></td>
<td><?= htmlspecialchars($p['name']) ?></td>
<td><?= htmlspecialchars($p['category_name']) ?></td>
<td><?= htmlspecialchars($p['brand_name']) ?></td>
<td class="expiring"><?= $p['expiry_date'] ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</body>
</html>
