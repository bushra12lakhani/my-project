<?php
// inc/auth.php
session_start();

// Check if admin is logged in
function requireLogin() {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

// Optional: logout function
function logout() {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
