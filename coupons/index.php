<?php
$pageTitle = "Coupons";
include __DIR__ . '/../inc/layout.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

// Fetch all coupons
$coupons = $pdo->query('SELECT * FROM coupons ORDER BY created_at DESC')->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h2>Coupons</h2>
        <a href="add.php" class="btn btn-primary">+ Add Coupon</a>
    </div>

    <table class="admin-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Code</th>
            <th>Discount (%)</th>
            <th>Min Purchase ($)</th>
            <th>Expiry Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php $i = 1; // Row counter ?>
    <?php foreach($coupons as $c): ?>
        <tr>
            <td><?= $i++ ?></td> <!-- Sequential row number -->
            <td><?= htmlspecialchars($c['code']) ?></td>
            <td><?= number_format($c['discount_percent'],2) ?></td> <!-- Correct column -->
            <td><?= number_format($c['min_purchase'],2) ?></td>
            <td><?= $c['expiry_date'] ?></td>
            <td><?= $c['status'] ?></td>
            <td>
                <a href="delete.php?id=<?= $c['id'] ?>" class="btn btn-primary delete" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
</div>
