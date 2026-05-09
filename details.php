<?php
require_once __DIR__ . '/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM regions WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$region = $stmt->fetch();

$pageTitle = $region ? $region['name'] : 'تفاصيل المنطقة';
include __DIR__ . '/includes/header.php';
?>

<?php if (!$region): ?>
    <div class="empty">
        <h2>المنطقة غير موجودة</h2>
        <p>الرجاء الرجوع إلى <a href="gallery.php">معرض المناطق</a>.</p>
    </div>
<?php else: ?>
    <article>
        <div class="details-hero">
            <img src="<?= e(img_src($region['main_image'])) ?>" alt="<?= e($region['name']) ?>">
        </div>

        <section class="details-section">
            <h1 class="page-title"><?= e($region['name']) ?></h1>
            <p><?= nl2br(e($region['description'])) ?></p>
        </section>

        <section class="details-section">
            <div class="details-info-box">
                <strong>معلومات سريعة:</strong>
                <ul>
                    <?php if (!empty($region['location'])): ?>
                        <li>الموقع: <?= e($region['location']) ?></li>
                    <?php endif; ?>
                    <?php if (!empty($region['features'])): ?>
                        <li>المميزات: <?= e($region['features']) ?></li>
                    <?php endif; ?>
                    <?php if (!empty($region['activities'])): ?>
                        <li>الأنشطة: <?= e($region['activities']) ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </section>

        <?php if (!empty($region['landmarks'])): ?>
            <section class="details-section">
                <h2>أبرز المعالم</h2>
                <ul>
                    <?php foreach (explode(',', $region['landmarks']) as $lm): ?>
                        <?php $lm = trim($lm); if ($lm === '') continue; ?>
                        <li><?= e($lm) ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php
        $gallery = array_filter([
            $region['gallery_1'] ?? null,
            $region['gallery_2'] ?? null,
            $region['gallery_3'] ?? null,
        ]);
        ?>
        <?php if (!empty($gallery)): ?>
            <section class="details-section">
                <h2>معرض الصور</h2>
                <div class="gallery-strip">
                    <?php foreach ($gallery as $g): ?>
                        <img src="<?= e(img_src($g)) ?>" alt="صورة">
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </article>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
