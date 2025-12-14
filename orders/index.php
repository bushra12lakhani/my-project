<?php
$pageTitle = "Orders";
include __DIR__ . '/../inc/layout.php';
// require_once __DIR__ . '/../inc/db.php';
// require_once __DIR__ . '/../inc/auth.php';
requireLogin();

// Fetch all orders
$orders = $pdo->query('
    SELECT o.id, o.customer_id, o.total_amount, o.status, o.created_at, c.name AS customer_name
    FROM orders o
    JOIN customers c ON o.customer_id = c.id
    ORDER BY o.created_at DESC
')->fetchAll();
?>
<!-- <!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Orders • Cosmetics Admin</title>
<style>
body{font-family:Poppins,sans-serif;background:#fce4ec;color:#121212;margin:20px}
table{width:100%;border-collapse:collapse;margin-top:20px}
th,td{padding:12px;border-bottom:1px solid #eee;text-align:left}
th{background:#fff0f4}
.status-pending{color:#f06292;font-weight:600}
.status-processing{color:#ff80ab;font-weight:600}
.status-shipped{color:#64b5f6;font-weight:600}
.status-delivered{color:#4caf50;font-weight:600}
.status-cancelled{color:#d32f2f;font-weight:600}
.btn{padding:6px 12px;background:#ff80ab;color:white;border:none;border-radius:6px;cursor:pointer;text-decoration:none}
</style>
</head>
<body> -->
    <div class="card">
        <div class="card-header">
<h2>Orders</h2>
</div>
<table class="admin-table">
<thead>
<tr>
<th>ID</th>
<th>Customer</th>
<th>Total ($)</th>
<th>Status</th>
<th>Order Date</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach($orders as $o): ?>
<tr>
<td><?= $o['id'] ?></td>
<td><?= htmlspecialchars($o['customer_name']) ?></td>
<td><?= number_format($o['total_amount'],2) ?></td>
<td class="status-<?= strtolower($o['status']) ?>"><?= ucfirst($o['status']) ?></td>
<td><?= $o['created_at'] ?></td>
<td>
<a href="view.php?id=<?= $o['id'] ?>" class="btn">View</a>
<a href="update-status.php?id=<?= $o['id'] ?>" class="btn">Update Status</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
