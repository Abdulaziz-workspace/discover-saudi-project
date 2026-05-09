<?php
/**
 * auth.php
 * Included at the top of every PROTECTED admin page.
 * Redirects to login.php if the admin is not signed in.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
