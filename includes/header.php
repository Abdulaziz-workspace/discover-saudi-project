<?php
/**
 * Shared public-side header (navigation bar).
 * Expects $pageTitle to be set before include.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = $pageTitle ?? 'اكتشف السعودية';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | اكتشف السعودية</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="index.php">اكتشف السعودية</a>
        <nav class="main-nav">
            <a href="index.php">الرئيسية</a>
            <a href="gallery.php">معرض المناطق</a>
            <a href="admin/login.php">دخول المشرف</a>
            <button id="nightToggle" class="night-btn" type="button" aria-label="تبديل الوضع الليلي">
                الوضع الليلي
            </button>
        </nav>
    </div>
</header>
<main class="container site-main">
