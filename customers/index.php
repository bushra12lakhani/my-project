<?php
$pageTitle = "Customers";
include __DIR__ . '/../inc/layout.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

// Fetch customers with orders count & total spent
$customers = $pdo->query('
    SELECT c.id, c.name, c.email, c.phone, c.address, c.created_at,
           COUNT(o.id) AS orders_count, COALESCE(SUM(o.total_amount),0) AS total_spent
    FROM customers c
    LEFT JOIN orders o ON o.customer_id = c.id
    GROUP BY c.id
    ORDER BY c.created_at DESC
')->fetchAll();
?>
<!-- <!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Customers • Cosmetics Admin</title>
<style>
body{font-family:Poppins,sans-serif;background:#fce4ec;color:#121212;margin:20px}
table{width:100%;border-collapse:collapse;margin-top:20px}
th,td{padding:12px;border-bottom:1px solid #eee;text-align:left}
th{background:#fff0f4}
</style>
</head>
<body> -->
    <div class="card">
        <div class="card-header">
<h2>Customers</h2>
 <!-- <a href="add.php" class="btn btn-primary">+ Add Product</a> -->
    </div>
<table class="admin-table">
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Address</th>
<th>Orders</th>
<th>Total Spent ($)</th>
<th>Joined</th>
</tr>
</thead>
<tbody>
<?php foreach($customers as $c): ?>
<tr>
<td><?= $c['id'] ?></td>
<td><?= htmlspecialchars($c['name']) ?></td>
<td><?= htmlspecialchars($c['email']) ?></td>
<td><?= htmlspecialchars($c['phone']) ?></td>
<td><?= htmlspecialchars($c['address']) ?></td>
<td><?= $c['orders_count'] ?></td>
<td><?= number_format($c['total_spent'],2) ?></td>
<td><?= $c['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<!-- </body>
</html> -->
