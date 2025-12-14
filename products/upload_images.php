<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
requireLogin();

$product_id = $_GET['product_id'] ?? 0;
if(!$product_id) exit('Product ID missing');

// Upload images
if($_SERVER['REQUEST_METHOD']==='POST'){
    $uploadDir = __DIR__.'/../assets/images/products/';
    if(!is_dir($uploadDir)) mkdir($uploadDir,0777,true);

    foreach($_FILES['images']['tmp_name'] as $i => $tmp){
        if(!$_FILES['images']['name'][$i]) continue;

        $fileName = time().'_'.basename($_FILES['images']['name'][$i]);
        move_uploaded_file($tmp, $uploadDir.$fileName);

        $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?,?)");
        $stmt->execute([$product_id, "assets/images/products/".$fileName]);
    }

    header("Location: upload_images.php?product_id=$product_id");
    exit;
}

// Fetch images
$images = $pdo->prepare("SELECT * FROM product_images WHERE product_id=?");
$images->execute([$product_id]);
$images = $images->fetchAll();

?>
<!doctype html>
<html>
<head>
<title>Upload Images</title>
<style>
body{background:#fce4ec;font-family:Poppins;padding:20px}
img{width:100px;border-radius:8px;margin:5px;border:1px solid #ddd}
button{padding:8px 16px;background:#ff80ab;color:#fff;border:none;border-radius:6px;}
</style>
</head>
<body>

<h2>Upload Images for Product ID <?= $product_id ?></h2>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="images[]" multiple required>
    <button type="submit">Upload</button>
</form>

<h3>Existing Images</h3>
<?php foreach($images as $img): ?>
    <img src="../<?= $img['image_path'] ?>">
<?php endforeach; ?>

</body>
</html>
