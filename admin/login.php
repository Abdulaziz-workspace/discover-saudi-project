<?php
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// If already logged in, jump to dashboard
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'الرجاء تعبئة جميع الحقول.';
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password FROM admins WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id']       = (int)$admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'اسم المستخدم أو كلمة المرور غير صحيحة.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المشرف</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<header class="admin-bar">
    <strong>لوحة المشرف</strong>
    <div class="actions">
        <a href="../index.php">زيارة الموقع</a>
        <button id="nightToggle" class="night-btn" type="button">الوضع الليلي</button>
    </div>
</header>

<main class="login-wrap">
    <form id="loginForm" class="login-card" method="post" novalidate>
        <h2>تسجيل دخول المشرف</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="form-group">
            <label for="username">اسم المستخدم</label>
            <input id="username" name="username" type="text"
                   placeholder="مثل: admin" data-required
                   value="<?= e($_POST['username'] ?? '') ?>">
            <div class="form-error"></div>
        </div>

        <div class="form-group">
            <label for="password">كلمة المرور</label>
            <input id="password" name="password" type="password"
                   placeholder="••••••••" data-required>
            <div class="form-error"></div>
        </div>

        <button class="btn" type="submit">دخول</button>
    </form>
</main>

<script src="../script.js"></script>
</body>
</html>
