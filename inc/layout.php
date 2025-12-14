<?php
require_once __DIR__ . '/db.php'; 
require_once __DIR__ . '/auth.php';
requireLogin();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= $pageTitle ?? 'Admin Panel' ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
body{margin:0;font-family:'Poppins',sans-serif;background:#ffffff;color:#222;}
header{background:white;color:#d4af37;border-bottom:1px solid #e8e8e8;padding:18px 0;font-size:22px;text-align:center;font-weight:600;}
.sidebar{width:220px;background:white;border-right:1px solid #e8e8e8;height:100vh;position:fixed;top:0;left:0;padding:28px 12px;}
.sidebar a{color:#444;text-decoration:none;display:block;margin:12px 0;padding:10px 12px;border-radius:8px;font-weight:500;transition:0.2s;}
.sidebar a:hover{background:#fff6d6;color:#d4af37;border-left:4px solid #d4af37;}
.main{margin-left:240px;padding:28px;}
.card{background:white;border-radius:14px;padding:20px;margin-bottom:20px;border:1px solid #e7d9a8;box-shadow:0 4px 14px rgba(0,0,0,0.06);}
input,textarea,select{width:100%;padding:10px;margin-bottom:12px;border-radius:8px;border:1px solid #eee;}
/* CARD HEADER */
.card-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:16px;
}

.card-header h2{
    margin:0;
    font-size:20px;
    font-weight:600;
    color:#222;
}

/* ADMIN TABLE */
.admin-table{
    width:100%;
    border-collapse:collapse;
}

.admin-table th,
.admin-table td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:left;
}

.admin-table th{
    background:#fff9e6;
    color:#d4af37;
    font-weight:600;
}

/* BUTTONS */
/* button, */
.btn{
    padding:6px 14px;
    border-radius:6px;
    font-size:14px;
    font-weight:500;
    text-decoration:none;
    display:inline-block;
    transition:0.2s;
    border:none;
    /* outline:none;
    box-shadow:none; */
    /* appearance:none; */
    /* -webkit-appearance:none; */
    /* -moz-appearance:none; */
}

.btn-primary{
    background:#d4af37;
    color:#fff;
}

.btn-primary:hover{
    opacity:0.9;
}
.actions{
    white-space: nowrap;
}
.actions .btn{
    margin-right:6px;
}

.actions .btn.edit{
    background:#d4af37;
    color:#fff;
} 

 .actions .btn.delete{
    background:#971d1dff;
    color:#fff;
} 

.actions .btn:hover{
    opacity:0.85;
}


/* .actions .btn.edit { background: #d4af37; }        
.actions .btn.delete { background: #971d1dff; }     
.actions .btn.shades { background: #f9d7b1; color:#222;} 
.actions .btn.upload { background: #c49bff; }       */

.actions .btn:hover {
    opacity: 0.85;
}

</style>
</head>
<body>
<header>Cosmetics Admin Dashboard</header>


<div class="sidebar">
    <a href="/admin/index.php">Dashboard</a>
    <a href="/admin/products/index.php">Products</a>
    <a href="/admin/categories/index.php">Categories</a>
    <a href="/admin/brands/index.php">Brands</a>
    <a href="/admin/orders/index.php">Orders</a>
    <a href="/admin/customers/index.php">Customers</a>
    <a href="/admin/coupons/index.php">Coupons</a>
    <a href="/admin/banners/index.php">Banners</a>
    <a href="/admin/settings/general.php">Settings</a>
    <a href="/admin/logout.php">Logout</a>
</div>
<div class="main">
