<?php
$pageTitle = "General Settings";
include __DIR__ . '/../inc/layout.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$errors = [];
$success = '';

// Fetch current settings
$stmt = $pdo->query('SELECT * FROM settings LIMIT 1');
$settings = $stmt->fetch();

// Decode contact info JSON
$contact_email = $contact_phone = '';
if($settings && !empty($settings['contact_info'])){
    $contact_data = json_decode($settings['contact_info'], true);
    $contact_email = $contact_data['email'] ?? '';
    $contact_phone = $contact_data['phone'] ?? '';
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $site_name = trim($_POST['site_name'] ?? '');
    $contact_email = trim($_POST['contact_email'] ?? '');
    $contact_phone = trim($_POST['contact_phone'] ?? '');
    $theme_color = trim($_POST['theme_color'] ?? '#ff80ab');

    // Validation
    if(!$site_name) $errors[] = 'Website Name is required';
    if(!$contact_email) $errors[] = 'Contact Email is required';

    if(empty($errors)){
        $contact_info = json_encode([
            'email' => $contact_email,
            'phone' => $contact_phone
        ]);

        if($settings){
            // Update existing
            $query = 'UPDATE settings SET site_name=?, contact_info=?, theme_color=?';
            $params = [$site_name, $contact_info, $theme_color];

            // Add updated_at if column exists
            $stmt_check = $pdo->query("SHOW COLUMNS FROM settings LIKE 'updated_at'")->fetch();
            if($stmt_check) {
                $query .= ', updated_at=NOW()';
            }

            $query .= ' WHERE id=?';
            $params[] = $settings['id'];

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
        } else {
            // Insert new
            $stmt = $pdo->prepare('INSERT INTO settings (site_name, contact_info, theme_color) VALUES (?,?,?)');
            $stmt->execute([$site_name, $contact_info, $theme_color]);
        }

        $success = 'Settings updated successfully';

        // Refresh
        $stmt = $pdo->query('SELECT * FROM settings LIMIT 1');
        $settings = $stmt->fetch();

        if($settings && !empty($settings['contact_info'])){
            $contact_data = json_decode($settings['contact_info'], true);
            $contact_email = $contact_data['email'] ?? '';
            $contact_phone = $contact_data['phone'] ?? '';
        }
    }
}
?>

<div class="card">
    <h2>General Settings</h2>
    <?php if($errors): ?>
        <div class="errors">
            <?php foreach($errors as $e): ?>
                <div><?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Website Name</label>
        <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" required>

        <label>Contact Email</label>
        <input type="email" name="contact_email" value="<?= htmlspecialchars($contact_email) ?>" required>

        <label>Contact Phone</label>
        <input type="text" name="contact_phone" value="<?= htmlspecialchars($contact_phone) ?>">

        <label>Theme Color</label>
        <input type="color" name="theme_color" value="<?= htmlspecialchars($settings['theme_color'] ?? '#ff80ab') ?>">

        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>

<style>
.card {
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
    max-width:600px;
    margin:auto;
}
label {display:block; margin:10px 0 4px;}
input[type=text], input[type=email], input[type=color] {
    width:100%; padding:10px; border:1px solid #ddd; border-radius:6px;
}
button {padding:10px 16px; background:#ff80ab; color:#fff; border:none; border-radius:8px; cursor:pointer; margin-top:12px;}
.errors {color:#d32f2f; margin-bottom:12px;}
.success {color:#388e3c; margin-bottom:12px;}
</style>
