<?php
require_once __DIR__ . '/../config.php';
require __DIR__ . '/auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: dashboard.php?msg=not_found');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM regions WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$region = $stmt->fetch();

if (!$region) {
    header('Location: dashboard.php?msg=not_found');
    exit;
}

$errors = [];

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
    $name        = trim($_POST['name']        ?? '');
    $category    = trim($_POST['category']    ?? '');
    $description = trim($_POST['description'] ?? '');
    $location    = trim($_POST['location']    ?? '');
    $features    = trim($_POST['features']    ?? '');
    $activities  = trim($_POST['activities']  ?? '');
    $landmarks   = trim($_POST['landmarks']   ?? '');

    if ($name === '')        $errors[] = 'اسم المكان مطلوب.';
    if ($category === '')    $errors[] = 'التصنيف مطلوب.';
    if ($description === '') $errors[] = 'الوصف مطلوب.';

    // Re-uploads keep old image if not provided
    $newMain = handle_upload('main_image', $errors) ?: $region['main_image'];
    $newG1   = handle_upload('gallery_1',  $errors) ?: $region['gallery_1'];
    $newG2   = handle_upload('gallery_2',  $errors) ?: $region['gallery_2'];
    $newG3   = handle_upload('gallery_3',  $errors) ?: $region['gallery_3'];

    if (empty($errors)) {
        $sql = "UPDATE regions
                SET name = :name, category = :category, description = :description,
                    location = :location, features = :features, activities = :activities,
                    landmarks = :landmarks,
                    main_image = :main_image,
                    gallery_1 = :g1, gallery_2 = :g2, gallery_3 = :g3
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'        => $name,
            ':category'    => $category,
            ':description' => $description,
            ':location'    => $location ?: null,
            ':features'    => $features ?: null,
            ':activities'  => $activities ?: null,
            ':landmarks'   => $landmarks ?: null,
            ':main_image'  => $newMain,
            ':g1'          => $newG1,
            ':g2'          => $newG2,
            ':g3'          => $newG3,
            ':id'          => $id,
        ]);

        header('Location: dashboard.php?msg=updated');
        exit;
    }

    // Show submitted values back if validation failed
    $region = array_merge($region, [
        'name' => $name, 'category' => $category, 'description' => $description,
        'location' => $location, 'features' => $features,
        'activities' => $activities, 'landmarks' => $landmarks,
    ]);
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديث المحتوى</title>
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
    <h1 class="page-title">تحديث مكان</h1>
    <p class="page-subtitle">المكان المحدد للتحديث: <strong><?= e($region['name']) ?></strong></p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?>
                <div>• <?= e($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form id="contentForm" class="form-page" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="id" value="<?= (int)$region['id'] ?>">

        <div class="form-group">
            <label>* اسم المكان</label>
            <input type="text" name="name" data-required value="<?= e($region['name']) ?>">
            <div class="form-error"></div>
        </div>

        <div class="form-group">
            <label>* التصنيف</label>
            <select name="category" data-required>
                <?php
                $cats = ['وسطى','غربية','شرقية','جنوبية','شمالية'];
                foreach ($cats as $c) {
                    $sel = ($region['category'] === $c) ? 'selected' : '';
                    echo "<option value=\"".e($c)."\" {$sel}>".e($c)."</option>";
                }
                ?>
            </select>
            <div class="form-error"></div>
        </div>

        <div class="form-group">
            <label>الصورة الرئيسية الحالية</label>
            <div><img src="<?= e(img_src($region['main_image'])) ?>" alt="" style="max-width:200px;border-radius:8px;"></div>
            <p class="hint">حدّث الصورة الرئيسية (اختيارية):</p>
            <input type="file" name="main_image" accept="image/*">
        </div>

        <div class="form-group">
            <label>* الوصف</label>
            <textarea name="description" data-required><?= e($region['description']) ?></textarea>
            <div class="form-error"></div>
        </div>

        <div class="form-group">
            <label>الموقع</label>
            <input type="text" name="location" value="<?= e($region['location']) ?>">
        </div>

        <div class="form-group">
            <label>المميزات</label>
            <input type="text" name="features" value="<?= e($region['features']) ?>">
        </div>

        <div class="form-group">
            <label>الأنشطة</label>
            <input type="text" name="activities" value="<?= e($region['activities']) ?>">
        </div>

        <div class="form-group">
            <label>المعالم (افصل بينها بفاصلة)</label>
            <input type="text" name="landmarks" value="<?= e($region['landmarks']) ?>">
        </div>

        <h3>تحديث صور المعرض (اختيارية)</h3>
        <div class="form-group">
            <label>صورة المعرض الأولى</label>
            <?php if (!empty($region['gallery_1'])): ?>
                <div><img src="<?= e(img_src($region['gallery_1'])) ?>" alt="" style="max-width:120px;border-radius:6px;"></div>
            <?php endif; ?>
            <input type="file" name="gallery_1" accept="image/*">
        </div>
        <div class="form-group">
            <label>صورة المعرض الثانية</label>
            <?php if (!empty($region['gallery_2'])): ?>
                <div><img src="<?= e(img_src($region['gallery_2'])) ?>" alt="" style="max-width:120px;border-radius:6px;"></div>
            <?php endif; ?>
            <input type="file" name="gallery_2" accept="image/*">
        </div>
        <div class="form-group">
            <label>صورة المعرض الثالثة</label>
            <?php if (!empty($region['gallery_3'])): ?>
                <div><img src="<?= e(img_src($region['gallery_3'])) ?>" alt="" style="max-width:120px;border-radius:6px;"></div>
            <?php endif; ?>
            <input type="file" name="gallery_3" accept="image/*">
        </div>

        <button class="btn" type="submit">حفظ التعديلات</button>
        <a class="btn btn-light" href="dashboard.php">إلغاء</a>
    </form>
</main>

<footer class="site-footer">
    <div class="container">© اكتشف السعودية — جامعة الملك سعود</div>
</footer>

<script src="../script.js"></script>
</body>
</html>
