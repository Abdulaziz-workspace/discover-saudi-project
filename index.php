<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'الرئيسية';
include __DIR__ . '/includes/header.php';
?>

<section class="hero-grid">
    <div class="card hero-card">
        <h1>موقع ثقافي تفاعلي للتعريف بالمملكة</h1>
        <p>
            استكشف مناطق المملكة العربية السعودية وتعرّف على أهم
            المعالم التاريخية والثقافية. اختر منطقة من المعرض للانتقال
            إلى صفحة التفاصيل.
        </p>
        <a href="gallery.php" class="btn">ابدأ الاستكشاف</a>
    </div>
    <div class="card hero-card hero-welcome">
        <h2>أهلاً بك 👋</h2>
        <p>ابدأ رحلتك لاكتشاف مناطق المملكة</p>
    </div>
</section>

<section class="info-grid">
    <div class="card info-card">
        <h3>⭐ الهدف</h3>
        <p>تقديم معلومات عربية موثوقة عن مناطق المملكة وأبرز الوجهات.</p>
    </div>
    <div class="card info-card">
        <h3>🏛 المناطق</h3>
        <p>معرض تفاعلي ينقل المستخدم بين المناطق (صور + عناوين + روابط).</p>
    </div>
    <div class="card info-card">
        <h3>📝 التفاصيل</h3>
        <p>صفحة تعرض وصفًا وصورًا ومعلومات تاريخية عن المكان المختار.</p>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
