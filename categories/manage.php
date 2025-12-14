<?php
// session_start(); // important!
$pageTitle = "Manage Category";
include __DIR__ . '/../inc/layout.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$id = $_GET['id'] ?? null;
$category = ['name' => ''];
$errors = [];

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id=?');
    $stmt->execute([$id]);
    $category = $stmt->fetch();
    if (!$category) exit('Category not found');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if (!$name) $errors[] = 'Category name required';

    if (empty($errors)) {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE categories SET name=? WHERE id=?');
            $stmt->execute([$name, $id]);
            $_SESSION['category_success'] = 'Category updated successfully';
        } else {
            $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
            $stmt->execute([$name]);
            $_SESSION['category_success'] = 'Category added successfully';
        }

        // Redirect to index.php (Categories list)
        header('Location: index.php');
        exit;
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2><?= $id ? 'Edit' : 'Add' ?> Category</h2>
    </div>

    <?php if ($errors): ?>
        <div class="errors">
            <?php foreach($errors as $e) echo "<div>$e</div>"; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
        <button type="submit"><?= $id ? 'Update' : 'Add' ?></button>
    </form>
</div>
