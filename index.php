<?php

// require_once __DIR__ . '/inc/layout.php';
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/auth.php';
requireLogin();

$totalProducts = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$totalOrders   = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$totalCustomers = $pdo->query('SELECT COUNT(*) FROM customers')->fetchColumn();
$lowStock = $pdo->query('SELECT COUNT(*) FROM products WHERE stock_qty < 10')->fetchColumn();

$recentProducts = $pdo->query('SELECT id, name, price FROM products ORDER BY created_at DESC LIMIT 5')->fetchAll();

$recentOrders = $pdo->query("
    SELECT o.id, c.name AS customer_name, o.total, o.status
    FROM orders o
    JOIN customers c ON o.customer_id = c.id
    ORDER BY o.created_at DESC LIMIT 5
")->fetchAll();
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>

<div class="main">

    <div class="stats">
        <div class="card"><h3>Total Products</h3><p><?= $totalProducts ?></p></div>
        <div class="card"><h3>Total Orders</h3><p><?= $totalOrders ?></p></div>
        <div class="card"><h3>Total Customers</h3><p><?= $totalCustomers ?></p></div>
        <div class="card"><h3>Low Stock</h3><p><?= $lowStock ?></p></div>
    </div>

    <!-- CHARTS ROW -->
    <div class="chart-card">
        <h3>Monthly Sales Overview</h3>
        <canvas id="salesChart"></canvas>
    </div>

    <div class="chart-card">
        <h3>Orders Statistics</h3>
        <canvas id="ordersChart"></canvas>
    </div>

    <div class="chart-card">
        <h3>Stock Report</h3>
        <canvas id="stockChart"></canvas>
    </div>

    <div class="card">
        <h3>Recent Products</h3>
        <table>
            <thead><tr><th>Name</th><th>Price</th></tr></thead>
            <tbody>
            <?php foreach($recentProducts as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td>$<?= number_format($p['price'],2) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3>Recent Orders</h3>
        <table>
            <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach($recentOrders as $o): ?>
                <tr>
                    <td>#<?= $o['id'] ?></td>
                    <td><?= htmlspecialchars($o['customer_name']) ?></td>
                    <td>$<?= number_format($o['total'],2) ?></td>
                    <td><?= $o['status'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'inc/footer.php'; ?>

<script>
// SALES CHART
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{
            label: 'Sales ($)',
            data: [1200, 1900, 900, 2500, 3000, 3500],
            borderWidth: 3,
            borderColor: '#d4af37'
        }]
    }
});

// ORDERS CHART
new Chart(document.getElementById('ordersChart'), {
    type: 'bar',
    data: {
        labels: ['Pending','Processing','Shipped','Completed'],
        datasets: [{
            label: 'Orders',
            data: [5, 8, 3, 12],
            backgroundColor: ['#ead28d','#d4af37','#f0d98c','#d4af37']
        }]
    }
});

// STOCK PIE CHART
new Chart(document.getElementById('stockChart'), {
    type: 'pie',
    data: {
        labels: ['Low Stock','Good Stock'],
        datasets: [{
            data: [<?= $lowStock ?>, <?= $totalProducts - $lowStock ?>],
            backgroundColor: ['#d4af37','#f7e9b0']
        }]
    }
});
</script>
