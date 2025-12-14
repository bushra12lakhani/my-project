<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
requireLogin();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Cosmetics Admin Panel</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
/* GLOBAL */
body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:#ffffff;
    color:#222;
}

/* HEADER TOP */
header{
    background:white;
    color:#d4af37;
    border-bottom:1px solid #e8e8e8;
    padding:18px 0;
    font-size:22px;
    text-align:center;
    font-weight:600;
}

/* SIDEBAR SPACING */
.main{
    margin-left:240px;
    padding:28px;
}

/* GOLDEN CARDS */
.card{
    background:white;
    border-radius:14px;
    padding:20px;
    margin-bottom:20px;
    border:1px solid #e7d9a8;
    box-shadow:0 4px 14px rgba(0,0,0,0.06);
}

.card h3{
    margin-top:0;
    font-size:16px;
    font-weight:600;
    color:#d4af37;
}

/* STATS BOXES */
.stats{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}
.stats .card{
    flex:1;
    min-width:200px;
    text-align:center;
}

/* TABLES */
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:12px;
    border-bottom:1px solid #eee;
}
th{
    background:#fff9e6;
    color:#d4af37;
    font-weight:600;
}

/* SIDEBAR */
.sidebar{
    width:220px;
    background:white;
    border-right:1px solid #e8e8e8;
    height:100vh;
    position:fixed;
    top:0;
    left:0;
    padding:28px 12px;
}

.sidebar a{
    color:#444;
    text-decoration:none;
    display:block;
    margin:12px 0;
    padding:10px 12px;
    border-radius:8px;
    font-weight:500;
    transition:0.2s;
}

.sidebar a:hover{
    background:#fff6d6;
    color:#d4af37;
    border-left:4px solid #d4af37;
}

/* CHARTS */
.chart-card{
    background:white;
    padding:20px;
    border-radius:14px;
    border:1px solid #e7d9a8;
    margin-bottom:20px;
}
</style>

<!-- CHART.JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

<header>Cosmetics Admin Dashboard</header>
