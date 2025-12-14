<?php
$success = $_SESSION['category_success'] ?? '';
unset($_SESSION['category_success']);
$pageTitle = "Categories";
include __DIR__ . '/../inc/layout.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

// Fetch categories
$categories = $pdo->query('SELECT * FROM categories ORDER BY id ASC')->fetchAll();
?>
<?php if ($success): ?>
<div class="card" style="background:#fdf7e3;border-color:#d4af37;margin-bottom:16px">
    <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

<div class="card">

<div class="card-header">
<h2>Categories</h2>
<a href="manage.php" class="btn btn-primary">+ Add Category</a>
</div>


    <table class="admin-table">
<thead>
<tr>
<th>#</th>
<th>Name</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php $i = 1; ?>
<?php foreach($categories as $cat): ?>
<tr>
<td><?= $i++?></td>
<td><?= htmlspecialchars($cat['name']) ?></td>
<td class="actions">
<a href="manage.php?id=<?= $cat['id'] ?>" class="btn edit">Edit</a>
<!-- <a href="../products/index.php?category_id=<?= $cat['id'] ?>" 
   class="btn view">
   View Products
</a> -->
<a href="delete.php?id=<?= $cat['id'] ?>" class="btn delete" onclick="return confirm('Are you sure?')">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

