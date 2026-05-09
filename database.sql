-- ============================================================
--  Discover Saudi Arabia - Interactive Cultural Website
--  CSC457 Course Project
--  Database: discover_saudi
-- ============================================================

CREATE DATABASE IF NOT EXISTS discover_saudi
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE discover_saudi;

-- ------------------------------------------------------------
--  Drop existing tables (so re-import is clean)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS regions;
DROP TABLE IF EXISTS admins;

-- ------------------------------------------------------------
--  admins table
-- ------------------------------------------------------------
CREATE TABLE admins (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin account
--   username: admin
--   password: admin123
INSERT INTO admins (username, password) VALUES
('admin', '$2b$12$MaCvozq88JAKZXFQDouM8u6tJoo/jF5FWgLaHq9c47DlouA.VCacm');

-- ------------------------------------------------------------
--  regions table
-- ------------------------------------------------------------
CREATE TABLE regions (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100) NOT NULL,         -- اسم المنطقة / المكان
    category      VARCHAR(50)  NOT NULL,         -- التصنيف (وسطى، غربية، شرقية، ...)
    description   TEXT         NOT NULL,         -- الوصف
    location      VARCHAR(255) DEFAULT NULL,     -- الموقع
    features      TEXT         DEFAULT NULL,     -- المميزات
    activities    TEXT         DEFAULT NULL,     -- الأنشطة
    landmarks     TEXT         DEFAULT NULL,     -- أبرز المعالم (مفصولة بفاصلة)
    main_image    VARCHAR(500) DEFAULT NULL,     -- الصورة الرئيسية
    gallery_1     VARCHAR(500) DEFAULT NULL,     -- صورة المعرض 1
    gallery_2     VARCHAR(500) DEFAULT NULL,     -- صورة المعرض 2
    gallery_3     VARCHAR(500) DEFAULT NULL,     -- صورة المعرض 3
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Seed: 7 regions
--  Image filenames refer to files inside the uploads/ folder.
-- ------------------------------------------------------------
INSERT INTO regions
(name, category, description, location, features, activities, landmarks, main_image, gallery_1, gallery_2, gallery_3)
VALUES
('الرياض', 'وسطى',
 'الرياض هي عاصمة المملكة العربية السعودية ومركزها السياسي والاقتصادي. تتميز بمزيج فريد من الحداثة والتراث التاريخي.',
 'وسط المملكة',
 'مركز اقتصادي عالمي، بنية تحتية حديثة، مرافق ترفيهية متنوعة',
 'زيارة المعالم التاريخية، التسوق، الفعاليات الثقافية',
 'برج المملكة, قصر المصمك, حي الدرعية التاريخي',
 'Riyadh1.jpg', 'Riyadh2.jpg', 'Riyadh3.jpg', NULL),

('مكة المكرمة', 'غربية',
 'مكة المكرمة هي أقدس مدينة في الإسلام ووجهة الحجاج من جميع أنحاء العالم. تحتضن المسجد الحرام والكعبة المشرفة.',
 'غرب المملكة',
 'وجهة دينية وتاريخية عالمية، خدمات متكاملة للحجاج والمعتمرين',
 'أداء الحج والعمرة، زيارة المعالم الإسلامية',
 'المسجد الحرام, الكعبة المشرفة, جبل النور',
 'Makkah1.jpg', 'Makkah2.jpg', 'Makkah3.jpg', NULL),

('جدة', 'غربية',
 'جدة عروس البحر الأحمر، البوابة الرئيسية للحرمين الشريفين ومدينة ساحلية تجمع بين الأصالة والحداثة. تشتهر بالكورنيش ونافورة الملك فهد والبلد التاريخية.',
 'غرب المملكة على ساحل البحر الأحمر',
 'واجهة بحرية مميزة، تراث عريق، فعاليات ثقافية وفنية متنوعة',
 'التنزه على الكورنيش، زيارة جدة التاريخية، الأنشطة البحرية',
 'نافورة الملك فهد, جدة التاريخية (البلد), كورنيش جدة',
 'Jeddah1.jpg', 'Jeddah2.jpg', 'Jeddah3.jpg', NULL),

('العلا', 'غربية',
 'العلا وجهة سياحية فريدة تجمع بين الطبيعة الخلابة والتاريخ العريق. تضم مدائن صالح أول موقع سعودي يدرج في قائمة اليونسكو للتراث العالمي.',
 'شمال غرب المملكة',
 'مواقع تاريخية أثرية، طبيعة جبلية ساحرة، فعاليات ثقافية موسمية',
 'استكشاف المواقع الأثرية، رحلات السفاري، التخييم',
 'الحِجْر (مدائن صالح), جبل الفيل, البلدة القديمة',
 'Alula1.jpg', 'Alula2.jpg', NULL, NULL),

('الخبر', 'شرقية',
 'الخبر مدينة ساحلية حديثة تطل على الخليج العربي، تتميز بواجهتها البحرية الجميلة ومرافقها الترفيهية المتنوعة.',
 'شرق المملكة',
 'واجهة بحرية مميزة، مرافق ترفيهية حديثة، مطاعم متنوعة',
 'التنزه على الكورنيش، التسوق، الأنشطة البحرية',
 'كورنيش الخبر, جسر الملك فهد, الواجهة البحرية',
 'khobar1.jpg', 'Khobar2.jpg', 'Khobar3.jpg', NULL),

('أبها', 'جنوبية',
 'أبها مدينة جبلية جميلة ذات طبيعة خلابة ومناخ معتدل صيفًا. تشتهر بضبابها وجبالها الخضراء.',
 'جنوب غرب المملكة',
 'طبيعة جبلية ساحرة، مناخ معتدل، تراث ثقافي عريق',
 'التنزه في الحدائق، ركوب التلفريك، زيارة القرى التراثية',
 'جبل السودة, تلفريك أبها, قرية رجال ألمع',
 'Abha1.jpg', 'Abha2.jpg', NULL, NULL),

('تبوك', 'شمالية',
 'تبوك مدينة تاريخية في شمال المملكة، تجمع بين عبق التاريخ والطبيعة المتنوعة من الجبال إلى السواحل.',
 'شمال غرب المملكة',
 'مواقع تاريخية وأثرية، طبيعة متنوعة، شواطئ بكر',
 'استكشاف المواقع الأثرية، الغوص في البحر الأحمر، رحلات الجبال',
 'قلعة تبوك, محطة سكة حديد الحجاز, نيوم',
 'Tabuk1.jpg', 'Tabuk2.jpg', NULL, NULL);

-- ============================================================
--  End of script
-- ============================================================
