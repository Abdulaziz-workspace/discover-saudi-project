<?php
require_once __DIR__ . '/../config.php';
require __DIR__ . '/auth.php';

$errors = [];
$old    = [
    'name' => '', 'category' => '', 'description' => '',
    'location' => '', 'features' => '', 'activities' => '', 'landmarks' => '',
];

/**
 * Handle a single uploaded image:
 * - Validates extension
 * - Moves into ../uploads/ with a safe random name
 * - Returns the saved filename or null
 */
function handle_upload(string $field, array &$errors): ?string {
    if (empty($_FILES[$field]['name'])) return null;

    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "حدث خطأ أثناء رفع الصورة ({$field}).";
        return null;
    }
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext     = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
        $errors[] = "نوع الصورة غير مدعوم ({$field}).";
        return null;
    }

    $dir = __DIR__ . '/../uploads';
    if (!is_dir($dir)) @mkdir($dir, 0777, true);

    $newName = uniqid('img_', true) . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], $dir . '/' . $newName)) {
        $errors[] = "تعذر حفظ الصورة ({$field}).";
        return null;
    }
    return $newName;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $k => $_) {
        $old[$k] = trim($_POST[$k] ?? '');
    }

    if ($old['name'] === '')        $errors[] = 'اسم المكان مطلوب.';
    if ($old['category'] === '')    $errors[] = 'التصنيف مطلوب.';
    if ($old['description'] === '') $errors[] = 'الوصف مطلوب.';

    $main_image = handle_upload('main_image', $errors);
    $g1         = handle_upload('gallery_1',  $errors);
    $g2         = handle_upload('gallery_2',  $errors);
    $g3         = handle_upload('gallery_3',  $errors);

    if (!$main_image && empty($errors)) {
        $errors[] = 'الصورة الرئيسية للمكان مطلوبة.';
    }

    if (empty($errors)) {
        $sql = "INSERT INTO regions
                (name, category, description, location, features, activities, landmarks,
                 main_image, gallery_1, gallery_2, gallery_3)
                VALUES
                (:name, :category, :description, :location, :features, :activities, :landmarks,
                 :main_image, :g1, :g2, :g3)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'        => $old['name'],
            ':category'    => $old['category'],
            ':description' => $old['description'],
            ':location'    => $old['location'] ?: null,
            ':features'    => $old['features'] ?: null,
            ':activities'  => $old['activities'] ?: null,
            ':landmarks'   => $old['landmarks'] ?: null,
            ':main_image'  => $main_image,
            ':g1'          => $g1,
            ':g2'          => $g2,
            ':g3'          => $g3,
        ]);

        header('Location: dashboard.php?msg=added');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة محتوى</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<header class="admin-bar">
    <strong>لوحة المشرف</strong>
    <div class="actions">
        <a href="dashboard.php">لوحة التحكم</a>
        <a href="../index.php">زيارة الموقع</a>
        <button id="nightToggle" class="night-btn" type="button">الوضع الليلي</button>
        <a class="admin-logout" href="logout.php">تسجيل الخروج</a>
    </div>
</header>

<main class="container site-main">
    <h1 class="page-title">إضافة مكان جديد</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?>
                <div>• <?= e($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form id="contentForm" class="form-page" method="post" enctype="multipart/form-data" novalidate>
        <div class="form-group">
            <label>* اسم المكان</label>
            <input type="text" name="name" data-required value="<?= e($old['name']) ?>">
            <div class="form-error"></div>
        </div>

        <div class="form-group">
            <label>* التصنيف</label>
            <select name="category" data-required>
                <option value="">اختر التصنيف...</option>
                <?php
                $cats = ['وسطى','غربية','شرقية','جنوبية','شمالية'];
                foreach ($cats as $c) {
                    $sel = ($old['category'] === $c) ? 'selected' : '';
                    echo "<option value=\"".e($c)."\" {$sel}>".e($c)."</option>";
                }
                ?>
            </select>
            <div class="form-error"></div>
        </div>

        <div class="form-group">
            <label>* الصورة الرئيسية للمكان</label>
            <input type="file" name="main_image" accept="image/*">
            <div class="form-error"></div>
        </div>

        <div class="form-group">
            <label>* الوصف</label>
            <textarea name="description" data-required><?= e($old['description']) ?></textarea>
            <div class="form-error"></div>
        </div>

        <div class="form-group">
            <label>الموقع</label>
            <input type="text" name="location" value="<?= e($old['location']) ?>">
        </div>

        <div class="form-group">
            <label>المميزات</label>
            <input type="text" name="features" value="<?= e($old['features']) ?>"
                   placeholder="مثل: مواقع أثرية، طبيعة جبلية">
        </div>

        <div class="form-group">
            <label>الأنشطة</label>
            <input type="text" name="activities" value="<?= e($old['activities']) ?>"
                   placeholder="مثل: رحلات، تخييم، تسوق">
        </div>

        <div class="form-group">
            <label>المعالم (افصل بينها بفاصلة)</label>
            <input type="text" name="landmarks" value="<?= e($old['landmarks']) ?>"
                   placeholder="مثل: قصر المصمك, برج المملكة, الدرعية">
        </div>

        <h3>صور المعرض</h3>
        <div class="form-group">
            <label>صورة المعرض الأولى (اختيارية)</label>
            <input type="file" name="gallery_1" accept="image/*">
        </div>
        <div class="form-group">
            <label>صورة المعرض الثانية (اختيارية)</label>
            <input type="file" name="gallery_2" accept="image/*">
        </div>
        <div class="form-group">
            <label>صورة المعرض الثالثة (اختيارية)</label>
            <input type="file" name="gallery_3" accept="image/*">
        </div>

        <button class="btn" type="submit">إضافة المكان</button>
    </form>
</main>

<footer class="site-footer">
    <div class="container">© اكتشف السعودية — جامعة الملك سعود</div>
</footer>

<script src="../script.js"></script>
</body>
</html>
