<?php
$pageTitle = 'Products';
include __DIR__ . '/../inc/layout.php';

require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();



// Success message from add.php
// $success = $_SESSION['success'] ?? '';
// unset($_SESSION['success']);

$success = $_SESSION['product_success'] ?? '';
unset($_SESSION['product_success']);
?>

<?php if($success): ?>
<div class="card" style="background:#fdf7e3;border-color:#d4af37;margin-bottom:16px">
    <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Products</h2>
        <a href="add.php" class="btn btn-primary">+ Add Product</a>
    </div>

    <table class="admin-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Orders</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $products = $pdo->query("
            SELECT 
                p.*,
                c.name AS category,
                b.name AS brand,
                COUNT(oi.id) AS orders_count
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN brands b ON p.brand_id = b.id
            LEFT JOIN order_items oi ON oi.product_id = p.id
            GROUP BY p.id
            ORDER BY p.id ASC
        ")->fetchAll();
    

        foreach($products as $p):
        ?>
        <tr>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= htmlspecialchars($p['category']) ?></td>
            <td><?= htmlspecialchars($p['brand']) ?></td>
            <td>$<?= number_format($p['price'], 2) ?></td>
            <td><?= $p['stock_qty'] ?></td>
            <td><?= $p['orders_count'] ?></td>
            <td class="actions">
                <a href="edit.php?id=<?= $p['id'] ?>" class="btn edit">Edit</a>
                <a href="delete.php?id=<?= $p['id'] ?>" 
                   class="btn delete"
                   onclick="return confirm('Delete this product?')">
                   Delete
                </a>
            </td>
        </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>
