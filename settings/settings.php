<?php
$pageTitle = "Settings";
include __DIR__ . '/../inc/layout.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$errors = [];
$success = '';

// Fetch existing settings
$stmt = $pdo->query('SELECT * FROM settings LIMIT 1');
$settings = $stmt->fetch();

// Decode contact info
$contact_email = $contact_phone = '';
if($settings && $settings['contact_info']){
    $contact_data = json_decode($settings['contact_info'], true);
    $contact_email = $contact_data['email'] ?? '';
    $contact_phone = $contact_data['phone'] ?? '';
}

// Handle POST
if($_SERVER['REQUEST_METHOD']==='POST'){
    $tab = $_POST['tab'] ?? 'general';

    if($tab==='general'){
        $site_name = trim($_POST['site_name'] ?? '');
        $contact_email = trim($_POST['contact_email'] ?? '');
        $contact_phone = trim($_POST['contact_phone'] ?? '');
        $theme_color = trim($_POST['theme_color'] ?? '#ff80ab');

        // Validation
        if(!$site_name) $errors[]='Website Name is required';
        if(!$contact_email) $errors[]='Contact Email is required';

        if(empty($errors)){
            $contact_info = json_encode(['email'=>$contact_email,'phone'=>$contact_phone]);

            if($settings){
                $stmt = $pdo->prepare('UPDATE settings SET site_name=?, contact_info=?, theme_color=? WHERE id=?');
                $stmt->execute([$site_name,$contact_info,$theme_color,$settings['id']]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO settings (site_name, contact_info, theme_color) VALUES (?,?,?)');
                $stmt->execute([$site_name,$contact_info,$theme_color]);
            }

            $success = 'General settings saved successfully';
            header('Location: settings.php?tab=general&success=1');
            exit;
        }

    } elseif($tab==='logo' && isset($_FILES['logo'])){
        if($_FILES['logo']['error']!==0){
            $errors[]='Please select a valid logo image';
        } else {
            $uploadDir = __DIR__.'/../assets/images/';
            if(!is_dir($uploadDir)) mkdir($uploadDir,0777,true);
            $filename = 'logo_'.time().'_'.basename($_FILES['logo']['name']);
            move_uploaded_file($_FILES['logo']['tmp_name'],$uploadDir.$filename);

            if($settings){
                $stmt = $pdo->prepare('UPDATE settings SET logo=? WHERE id=?');
                $stmt->execute(['assets/images/'.$filename,$settings['id']]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO settings (logo) VALUES (?)');
                $stmt->execute(['assets/images/'.$filename]);
            }
            $success = 'Logo updated successfully';
            header('Location: settings.php?tab=logo&success=1');
            exit;
        }

    } elseif($tab==='social'){
        $facebook = trim($_POST['facebook'] ?? '');
        $instagram = trim($_POST['instagram'] ?? '');
        $twitter = trim($_POST['twitter'] ?? '');
        $linkedin = trim($_POST['linkedin'] ?? '');

        if($settings){
            $stmt = $pdo->prepare('UPDATE settings SET facebook=?, instagram=?, twitter=?, linkedin=? WHERE id=?');
            $stmt->execute([$facebook,$instagram,$twitter,$linkedin,$settings['id']]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO settings (facebook, instagram, twitter, linkedin) VALUES (?,?,?,?)');
            $stmt->execute([$facebook,$instagram,$twitter,$linkedin]);
        }
        $success = 'Social links updated successfully';
        header('Location: settings.php?tab=social&success=1');
        exit;
    }
}

// Tab handling
$activeTab = $_GET['tab'] ?? 'general';
$success = $_GET['success'] ?? $success;
?>
<div class="card">
    <h2>Settings</h2>

    <div class="tabs">
        <button class="<?= $activeTab==='general'?'active':'' ?>" onclick="window.location='?tab=general'">General</button>
        <button class="<?= $activeTab==='logo'?'active':'' ?>" onclick="window.location='?tab=logo'">Logo</button>
        <button class="<?= $activeTab==='social'?'active':'' ?>" onclick="window.location='?tab=social'">Social Links</button>
    </div>

    <?php if($errors): ?><div class="errors"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div><?php endif; ?>
    <?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>

    <?php if($activeTab==='general'): ?>
    <form method="post">
        <input type="hidden" name="tab" value="general">
        <label>Website Name</label>
        <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" required>

        <label>Contact Email</label>
        <input type="email" name="contact_email" value="<?= htmlspecialchars($contact_email) ?>" required>

        <label>Contact Phone</label>
        <input type="text" name="contact_phone" value="<?= htmlspecialchars($contact_phone) ?>">

        <label>Theme Color</label>
        <input type="color" name="theme_color" value="<?= htmlspecialchars($settings['theme_color'] ?? '#ff80ab') ?>" id="themeColorInput">
        <div id="themeColorPreview" style="width:50px;height:20px;border:1px solid #000;display:inline-block;margin-left:10px;background-color:<?= htmlspecialchars($settings['theme_color'] ?? '#ff80ab') ?>;"></div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>

    <?php elseif($activeTab==='logo'): ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="tab" value="logo">
        <?php if(!empty($settings['logo'])): ?>
        <img src="../<?= $settings['logo'] ?>" alt="Logo" id="logoPreview" style="max-width:150px;margin-bottom:12px;">
        <?php else: ?>
        <img src="" alt="Logo" id="logoPreview" style="max-width:150px;margin-bottom:12px;display:none;">
        <?php endif; ?>
        <input type="file" name="logo" id="logoInput" required>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <?php elseif($activeTab==='social'): ?>
    <form method="post">
        <input type="hidden" name="tab" value="social">
        <label>Facebook URL</label>
        <input type="text" name="facebook" value="<?= htmlspecialchars($settings['facebook'] ?? '') ?>">
        <label>Instagram URL</label>
        <input type="text" name="instagram" value="<?= htmlspecialchars($settings['instagram'] ?? '') ?>">
        <label>Twitter URL</label>
        <input type="text" name="twitter" value="<?= htmlspecialchars($settings['twitter'] ?? '') ?>">
        <label>LinkedIn URL</label>
        <input type="text" name="linkedin" value="<?= htmlspecialchars($settings['linkedin'] ?? '') ?>">
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
    <?php endif; ?>
</div>

<style>
.card{padding:20px;background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);margin:20px 0}
.tabs{display:flex;margin-bottom:20px}
.tabs button{padding:10px 20px;border:none;background:#eee;margin-right:4px;border-radius:6px;cursor:pointer}
.tabs button.active{background:#ff80ab;color:#fff}
input,select{width:100%;padding:10px;margin-bottom:12px;border-radius:8px;border:1px solid #eee}
button.btn-primary{background:#ff80ab;color:#fff;border:none;padding:10px 16px;border-radius:8px;cursor:pointer}
.errors{color:red;margin-bottom:12px}
.success{color:green;margin-bottom:12px}
img{display:block;margin-bottom:12px}
</style>

<script>
// Live theme color preview
const colorInput = document.getElementById('themeColorInput');
const colorPreview = document.getElementById('themeColorPreview');
if(colorInput && colorPreview){
    colorInput.addEventListener('input', e => {
        colorPreview.style.backgroundColor = e.target.value;
    });
}

// Live logo preview
const logoInput = document.getElementById('logoInput');
const logoPreview = document.getElementById('logoPreview');
if(logoInput && logoPreview){
    logoInput.addEventListener('change', e => {
        const file = e.target.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(ev){
                logoPreview.src = ev.target.result;
                logoPreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
}
</script>
