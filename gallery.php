<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'معرض المناطق';
include __DIR__ . '/includes/header.php';

// --- Retrieve all regions from the database ---
$stmt    = $pdo->query("SELECT id, name, category, description, main_image FROM regions ORDER BY id ASC");
$regions = $stmt->fetchAll();

// Distinct categories for the filter dropdown
$categories = array_values(array_unique(array_map(fn($r) => $r['category'], $regions)));
?>

<h1 class="page-title">معرض المناطق</h1>
<p class="page-subtitle">ابحث أو اضغط على أي منطقة للانتقال إلى صفحة التفاصيل.</p>

<div class="filter-bar">
    <input type="text" id="searchInput" placeholder="ابحث عن منطقة أو مدينة...">
    <select id="categoryFilter">
        <option value="all">كل المناطق</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= e($cat) ?>"><?= e($cat) ?></option>
        <?php endforeach; ?>
    </select>
    <span class="results-count" id="resultsCount">عدد النتائج: <?= count($regions) ?></span>
</div>

<?php if (count($regions) === 0): ?>
    <div class="empty">لا توجد مناطق متاحة حاليًا.</div>
<?php else: ?>
    <div class="gallery-grid">
        <?php foreach ($regions as $r): ?>
            <a class="gallery-card"
               href="details.php?id=<?= (int)$r['id'] ?>"
               data-name="<?= e($r['name']) ?>"
               data-desc="<?= e($r['description']) ?>"
               data-category="<?= e($r['category']) ?>">
                <img src="<?= e(img_src($r['main_image'])) ?>" alt="<?= e($r['name']) ?>">
                <div class="info">
                    <div class="category"><?= e($r['category']) ?></div>
                    <h3><?= e($r['name']) ?></h3>
                    <p><?= e(mb_substr($r['description'], 0, 80)) ?>…</p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
