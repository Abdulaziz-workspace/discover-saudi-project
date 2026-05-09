<?php
require_once __DIR__ . '/../config.php';
require __DIR__ . '/auth.php';

// Read all regions
$regions = $pdo->query("SELECT id, name, category, description FROM regions ORDER BY id ASC")->fetchAll();

// Optional success / error message via ?msg=
$messages = [
    'added'     => 'تم إضافة السجل بنجاح.',
    'updated'   => 'تم تحديث السجل بنجاح.',
    'deleted'   => 'تم حذف السجل بنجاح.',
    'not_found' => 'السجل غير موجود.',
];
$flashKey = $_GET['msg'] ?? '';
$flash    = $messages[$flashKey] ?? '';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المشرف</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<header class="admin-bar">
    <strong>لوحة تحكم المشرف</strong>
    <div class="actions">
        <a href="../index.php">زيارة الموقع</a>
        <button id="nightToggle" class="night-btn" type="button">الوضع الليلي</button>
        <a class="admin-logout" href="logout.php">تسجيل الخروج</a>
    </div>
</header>

<main class="container site-main">

    <?php if ($flash): ?>
        <div class="alert alert-success"><?= e($flash) ?></div>
    <?php endif; ?>

    <h1 class="page-title">إدارة المحتوى</h1>
    <p class="page-subtitle">
        استخدم هذه الصفحة لإدارة محتوى الموقع من خلال عرض السجلات وإضافة أو تعديل أو حذف المحتوى.
    </p>

    <p style="margin: 14px 0;">
        <a class="btn" href="add.php">إضافة محتوى جديد</a>
    </p>

    <?php if (count($regions) === 0): ?>
        <div class="empty">لا يوجد محتوى. اضغط "إضافة محتوى جديد" للبدء.</div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>المنطقة</th>
                    <th>التصنيف</th>
                    <th>الوصف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($regions as $r): ?>
                <tr>
                    <td><?= (int)$r['id'] ?></td>
                    <td><?= e($r['name']) ?></td>
                    <td><?= e($r['category']) ?></td>
                    <td><?= e(mb_substr($r['description'], 0, 60)) ?>…</td>
                    <td>
                        <div class="row-actions">
                            <a class="edit"   href="edit.php?id=<?= (int)$r['id'] ?>">تعديل</a>
                            <a class="delete"
                               href="delete.php?id=<?= (int)$r['id'] ?>"
                               onclick="return confirm('هل تريد حذف هذا السجل؟');">حذف</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<footer class="site-footer">
    <div class="container">© اكتشف السعودية — جامعة الملك سعود</div>
</footer>

<script src="../script.js"></script>
</body>
</html>
