# Discover Saudi Arabia – Interactive Cultural Website
**CSC457 Course Project**

A dynamic Arabic (RTL) cultural website that introduces Saudi Arabia, its
regions, and important places. Built with **HTML, CSS, JavaScript, PHP, and
MySQL**, with a public side and a session-protected admin area with full
CRUD support.

---

## Setup (XAMPP)

1. **Install XAMPP** if you don't have it: <https://www.apachefriends.org/>
2. **Copy the project folder** into `xampp/htdocs/`. After this you should
   have something like `xampp/htdocs/discover-saudi/`.
3. **Start Apache and MySQL** from the XAMPP control panel.
4. **Import the database**:
   - Open <http://localhost/phpmyadmin/>
   - Click **Import** → choose `database.sql` from the project folder → **Go**.
   - This creates the `discover_saudi` database with two tables (`admins`, `regions`)
     and seeds it with 6 sample regions and the default admin account.
5. Open the site: <http://localhost/discover-saudi/>

> If you placed the folder under a different name, just change the URL accordingly.

---

## Admin demo credentials

```
Username:  admin
Password:  admin123
```

Login URL: <http://localhost/discover-saudi/admin/login.php>

---

## File / folder layout

```
Project/
├── README.md                 ← this file
├── database.sql              ← schema + seed data + admin account
├── config.php                ← DB connection + helpers
│
├── index.php                 ← Home (الصفحة الرئيسية)
├── gallery.php               ← Regions Gallery (معرض المناطق)
├── details.php               ← Region/Place Details (صفحة التفاصيل)
│
├── style.css                 ← all styling (RTL, light + night mode)
├── script.js                 ← night-mode, gallery filter, form validation
│
├── includes/
│   ├── header.php            ← public top nav
│   └── footer.php            ← shared footer
│
├── admin/
│   ├── auth.php              ← session guard (included on every protected page)
│   ├── login.php             ← admin sign-in
│   ├── logout.php            ← destroys session
│   ├── dashboard.php         ← list of all content + flash messages
│   ├── add.php               ← new place form (handles image uploads)
│   ├── edit.php              ← edit existing place
│   └── delete.php            ← deletes a place after JS confirm
│
└── uploads/                  ← admin-uploaded images land here
```

---

## Features mapped to grading criteria

| Criterion (Grading PDF) | Where it lives |
|---|---|
| Home page (الصفحة الرئيسية) | `index.php` |
| Regions Gallery (معرض المناطق) | `gallery.php` |
| Details page (صفحة التفاصيل) | `details.php` |
| Admin Login (تسجيل دخول المشرف) | `admin/login.php` |
| Admin Dashboard (لوحة التحكم) | `admin/dashboard.php` |
| Admin CRUD | `add.php`, `edit.php`, `delete.php`, `dashboard.php` |
| CSS for all pages | `style.css` |
| JS – Admin login form validation | `script.js` (`setupLoginValidation`) |
| JS – Add/Edit form validation | `script.js` (`setupContentFormValidation`) |
| JS – Night mode | `script.js` (`setupNightToggle`) + button in headers |
| JS – Filtering on gallery | `script.js` (`setupGalleryFilter`) + UI in `gallery.php` |
| PHP – Display data on gallery | `gallery.php` |
| PHP – Display data on details | `details.php` |
| PHP – Admin login (session start) | `admin/login.php` |
| PHP – Logout on every admin page | `admin/logout.php` (linked from `admin-bar`) |
| PHP – Admin dashboard list | `admin/dashboard.php` |
| PHP – Create record | `admin/add.php` |
| PHP – Update record | `admin/edit.php` |
| PHP – Delete confirmation | `admin/dashboard.php` (`onclick="return confirm(...)"`) |
| PHP – Delete record | `admin/delete.php` |
| Success messages on dashboard | `?msg=added|updated|deleted` flash banner in `dashboard.php` |
| Sessions guard admin pages | `admin/auth.php` (included by every protected page) |

---

## Default admin account

Already inserted by `database.sql`:
- **username:** `admin`
- **password:** `admin123`
  (stored in DB as a bcrypt hash — verified with PHP's `password_verify`)

To create more admins, you can run something like this in phpMyAdmin's SQL tab:

```sql
INSERT INTO admins (username, password)
VALUES ('newuser', '$2y$12$REPLACE_WITH_PASSWORD_HASH');
```

Generate the hash by running this in PHP:

```php
echo password_hash('your-password', PASSWORD_BCRYPT);
```

---

## Notes

- The site is **fully Arabic and RTL** (`<html dir="rtl" lang="ar">`).
- Night mode is **stored in `localStorage`** so it persists between visits.
- Image fields can hold either an uploaded filename (in `/uploads/`) **or**
  a full external URL — the helper in `config.php` (`img_src`) handles both.
- All DB queries use **PDO prepared statements** to prevent SQL injection.

---

## Author

**Abdulaziz Mohammed Al-Aqeel** — ID **443100944**
CSC457: Internet Technologies
