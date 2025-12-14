<?php
// $success = $_SESSION['success'] ?? '';
// unset($_SESSION['success']);
$success = $_SESSION['brand_success'] ?? '';
unset($_SESSION['brand_success']);


$pageTitle = "Brands";
include __DIR__ . '/../inc/layout.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

// Fetch brands
$brands = $pdo->query('SELECT * FROM brands ORDER BY id ASC')->fetchAll();
?>
<?php if($success): ?>
<div class="card" style="background:#fdf7e3;border-color:#d4af37;margin-bottom:16px">
    <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

<div class="card">

    <div class="card-header">
        <h2>Brands</h2>
        <a href="manage.php" class="btn btn-primary">+ Add Brand</a>
    </div>

    <!-- <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($brands as $b): ?>
            <tr>
                <td><?= $b['id'] ?></td>
                <td><?= htmlspecialchars($b['name']) ?></td>
                <td class="actions">
                    <a href="manage.php?id=<?= $b['id'] ?>" class="btn edit">Edit</a>
                    <a href="delete.php?id=<?= $b['id'] ?>" 
                       class="btn delete"
                       onclick="return confirm('Are you sure?')">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table> -->

    <table class="admin-table">
    <thead>
        <tr>
            <th>#</th> <!-- Serial Number heading -->
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php $i = 1; ?>
    <?php foreach($brands as $b): ?>
        <tr>
            <td><?= $i++ ?></td>  <!-- Serial number -->
            <td><?= htmlspecialchars($b['name']) ?></td>
            <td class="actions">
                <a href="manage.php?id=<?= $b['id'] ?>" class="btn edit">Edit</a>
                <a href="delete.php?id=<?= $b['id'] ?>" 
                   class="btn delete"
                   onclick="return confirm('Are you sure?')">
                   Delete
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>


</div>
