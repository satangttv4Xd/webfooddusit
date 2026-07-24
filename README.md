# เว็บไซต์โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต

ระบบเว็บไซต์ + ระบบจัดการเนื้อหา (CMS) พัฒนาด้วย **PHP ล้วน (MVC ทำเอง)** ไม่พึ่ง framework ภายนอก
รองรับภาษาไทยเต็มรูปแบบ (UTF-8 / utf8mb4) พร้อมระบบสิทธิ์ (Roles & Permissions), Soft Delete และ Audit Log

> **School of Culinary Arts, Suan Dusit University** — Website & Content Management System

---

## สารบัญ

1. [ความต้องการของระบบ](#1-ความต้องการของระบบ)
2. [การติดตั้ง (ทีละขั้น)](#2-การติดตั้ง-ทีละขั้น)
3. [บัญชีผู้ใช้เริ่มต้น](#3-บัญชีผู้ใช้เริ่มต้น)
4. [การตั้งค่า (Configuration)](#4-การตั้งค่า-configuration)
5. [โครงสร้างโปรเจกต์](#5-โครงสร้างโปรเจกต์)
6. [สถาปัตยกรรม & การทำงาน](#6-สถาปัตยกรรม--การทำงาน)
7. [ระบบสิทธิ์ (Roles & Permissions)](#7-ระบบสิทธิ์-roles--permissions)
8. [โมดูลหลังบ้าน (Admin)](#8-โมดูลหลังบ้าน-admin)
9. [ฐานข้อมูล](#9-ฐานข้อมูล)
10. [แนวทางพัฒนาต่อ (เพิ่มโมดูลใหม่)](#10-แนวทางพัฒนาต่อ-เพิ่มโมดูลใหม่)
11. [การขึ้นโฮสต์จริง (Production)](#11-การขึ้นโฮสต์จริง-production)
12. [แก้ปัญหาที่พบบ่อย (Troubleshooting)](#12-แก้ปัญหาที่พบบ่อย-troubleshooting)

---

## 1. ความต้องการของระบบ

| รายการ | เวอร์ชันที่แนะนำ |
|--------|------------------|
| PHP | **8.0 ขึ้นไป** (ใช้ typed properties, `str_starts_with`, `match`) |
| MySQL / MariaDB | MySQL 5.7+ หรือ MariaDB 10.4+ |
| Apache | 2.4+ พร้อมเปิด **`mod_rewrite`** |
| PHP Extensions | `pdo_mysql`, `mbstring`, `fileinfo`, `openssl` |

แนะนำให้ใช้ **XAMPP** (Windows) เพราะมาพร้อม Apache + MySQL + PHP ครบชุด

---

## 2. การติดตั้ง (ทีละขั้น)

### ขั้นที่ 1 — วางไฟล์โปรเจกต์

วางโฟลเดอร์ทั้งหมดไว้ใน `htdocs` ของ XAMPP โดยใช้ชื่อโฟลเดอร์ **`webfooddusit`**

```
C:\xampp\htdocs\webfooddusit\
```

> ⚠️ ชื่อโฟลเดอร์สำคัญ! ค่าเริ่มต้นของ `BASE_URL` และ `.htaccess` อ้างอิงชื่อ `webfooddusit`
> หากใช้ชื่ออื่น ต้องแก้ 2 จุด: `app/config/config.php` (BASE_URL) และ `.htaccess` (RewriteBase)

### ขั้นที่ 2 — เปิด Apache + MySQL

เปิด **XAMPP Control Panel** แล้วกด **Start** ทั้ง Apache และ MySQL

### ขั้นที่ 3 — สร้างฐานข้อมูล & Import

เปิด phpMyAdmin ที่ http://localhost/phpmyadmin แล้ว import 2 ไฟล์ **ตามลำดับ**:

1. `database/sql/schema.sql` — สร้างฐานข้อมูล `fooddusit_db` + ตารางทั้งหมด (จะสร้าง DB ให้อัตโนมัติ)
2. `database/sql/seed.sql` — ใส่ข้อมูลตัวอย่าง (roles, permissions, users, เมนู ฯลฯ)

หรือใช้ command line:

```bash
mysql -u root < database/sql/schema.sql
mysql -u root fooddusit_db < database/sql/seed.sql
```

### ขั้นที่ 4 — ตรวจการตั้งค่าฐานข้อมูล

เปิด `app/config/database.php` — ค่าเริ่มต้นตรงกับ XAMPP อยู่แล้ว (user=`root`, ไม่มีรหัสผ่าน)
แก้เฉพาะเมื่อ MySQL ของคุณตั้งรหัสผ่านไว้

```php
'host'     => '127.0.0.1',
'database' => 'fooddusit_db',
'username' => 'root',
'password' => '',            // ← ใส่รหัสผ่านตรงนี้ถ้ามี
```

### ขั้นที่ 5 — สิทธิ์การเขียนโฟลเดอร์ (Linux/Mac เท่านั้น)

บน Windows/XAMPP ข้ามได้ ส่วน Linux ต้องให้เว็บเซิร์ฟเวอร์เขียนได้:

```bash
chmod -R 755 public/uploads storage
```

### ขั้นที่ 6 — เปิดเว็บไซต์

- หน้าเว็บ (ผู้เยี่ยมชม): **http://localhost/webfooddusit/**
- หน้าหลังบ้าน (ผู้ดูแล): **http://localhost/webfooddusit/admin**

---

## 3. บัญชีผู้ใช้เริ่มต้น

จากไฟล์ `seed.sql`:

| Username | รหัสผ่าน | บทบาท | สิทธิ์ |
|----------|----------|-------|--------|
| `superadmin` | `admin123` | ผู้ดูแลระบบสูงสุด | ทุกอย่าง |
| `admin` | `admin123` | ผู้ดูแลระบบ | เกือบทั้งหมด (ยกเว้นจัดการสิทธิ์ระดับสูง) |
| `editor` | `editor123` | บรรณาธิการข่าว | จัดการข่าว/เนื้อหา |
| `teacher` | `teacher123` | อาจารย์ | จำกัด |
| `staff` | `staff123` | เจ้าหน้าที่ | จำกัด |

> 🔒 **สำคัญ**: เปลี่ยนรหัสผ่านทุกบัญชีก่อนขึ้นใช้งานจริง (เมนู "โปรไฟล์" หรือหน้าจัดการผู้ใช้)

---

## 4. การตั้งค่า (Configuration)

ไฟล์ตั้งค่าหลักอยู่ที่ `app/config/config.php` ค่าที่ควรรู้:

| ค่า | ความหมาย | ค่าเริ่มต้น |
|-----|----------|-------------|
| `APP_ENV` | โหมด `development` (โชว์ error) / `production` (ซ่อน error) | `development` |
| `BASE_URL` | ที่อยู่หลักของเว็บ (ต้องลงท้าย `/`) | `http://localhost/webfooddusit/` |
| `SESSION_TIMEOUT` | เวลาหมดอายุ session (วินาที) | `3600` (1 ชม.) |
| `LOGIN_MAX_ATTEMPTS` | ล็อกอินผิดกี่ครั้งก่อนโดนล็อก | `5` |
| `LOGIN_LOCK_TIME` | ระยะเวลาโดนล็อก (วินาที) | `900` (15 นาที) |
| `MAX_UPLOAD_SIZE` | ขนาดไฟล์อัปโหลดสูงสุด | `5 MB` |
| `ALLOWED_IMAGE_TYPES` | นามสกุลรูปที่อนุญาต | `jpg,jpeg,png,webp,gif` |
| `ALLOWED_DOC_TYPES` | นามสกุลเอกสารที่อนุญาต | `pdf,doc,docx,xls,xlsx,ppt,pptx` |
| `ADMIN_PER_PAGE` / `PER_PAGE` | จำนวนรายการต่อหน้า (หลังบ้าน / หน้าเว็บ) | `15` / `12` |

---

## 5. โครงสร้างโปรเจกต์

```
webfooddusit/
├── .htaccess                 # ส่งทุก request เข้า public/ (ไม่ต้องพิมพ์ /public/)
├── app/
│   ├── config/
│   │   ├── config.php         # ค่าตั้งค่าหลัก (BASE_URL, session, upload ฯลฯ)
│   │   └── database.php       # ค่าเชื่อมต่อฐานข้อมูล
│   ├── core/                  # หัวใจของระบบ (framework ทำเอง)
│   │   ├── bootstrap.php      # จุดเริ่มต้น: config, session, autoload, security header
│   │   ├── App.php            # Router (จับคู่ URL → Controller@method)
│   │   ├── Controller.php     # คลาสแม่ของ Controller (view, redirect, csrf, input ฯลฯ)
│   │   ├── Model.php          # คลาสแม่ของ Model (CRUD, paginate, soft delete)
│   │   ├── Database.php       # PDO wrapper (query, bind, execute)
│   │   └── View.php           # render view + layout
│   ├── controllers/
│   │   ├── *.php              # ฝั่งหน้าเว็บ (Home, News, Program, Event, Gallery ฯลฯ)
│   │   └── admin/*.php        # ฝั่งหลังบ้าน (namespace Admin\)
│   ├── models/                # 1 ไฟล์ = 1 ตาราง
│   ├── helpers/
│   │   ├── functions.php      # ฟังก์ชัน global (url, e, upload_url, thai_date, pagination ฯลฯ)
│   │   ├── Auth.php           # ระบบล็อกอิน/สิทธิ์
│   │   ├── Security.php       # CSRF, slug, hash, sanitize
│   │   ├── Upload.php         # อัปโหลดไฟล์อย่างปลอดภัย (ตรวจ MIME จริง)
│   │   └── Validator.php      # ตรวจสอบข้อมูลฟอร์ม
│   └── views/
│       ├── layouts/           # public.php, admin.php, blank.php
│       ├── public/            # หน้าเว็บฝั่งผู้ชม
│       ├── admin/             # หน้าหลังบ้าน
│       └── errors/            # 404, 403
├── database/sql/
│   ├── schema.sql             # โครงสร้างตาราง (import ก่อน)
│   └── seed.sql               # ข้อมูลตัวอย่าง (import ทีหลัง)
├── public/                    # DocumentRoot จริง (จุดเข้าเดียว)
│   ├── index.php              # Front Controller
│   ├── .htaccess              # rewrite ทุก request → index.php
│   ├── assets/                # css, js, images (ของเว็บ)
│   └── uploads/               # ไฟล์ที่ผู้ใช้อัปโหลด (banner, news, gallery, programs, downloads)
└── storage/
    ├── cache/                 # แคช
    └── logs/                  # log (php-error.log ตอน production)
```

---

## 6. สถาปัตยกรรม & การทำงาน

ระบบเป็น **MVC + Front Controller** เขียนเอง ไม่มี framework

### ลำดับการทำงานของ 1 request

```
Browser → .htaccess (root) → public/.htaccess → public/index.php
   → bootstrap.php (config, session, autoload, security header)
   → App::run()  ── parse URL ──►  Controller@method(params)
   → Controller เรียก Model ดึงข้อมูล → ส่งเข้า View → ครอบด้วย layout → ส่ง HTML กลับ
```

### รูปแบบ URL (Friendly URL)

| URL | ทำงานที่ |
|-----|----------|
| `/` | `HomeController@index` |
| `/news` | `NewsController@index` |
| `/news/detail/5` | `NewsController@detail(5)` |
| `/admin` | `Admin\DashboardController@index` |
| `/admin/news` | `Admin\NewsController@index` |
| `/admin/news/edit/3` | `Admin\NewsController@edit(3)` |

> ฝั่งหลังบ้านใช้ namespace `Admin\` และ controller สืบทอดจาก `AdminController` ซึ่งบังคับล็อกอิน + ตรวจสิทธิ์ก่อนทุกครั้ง

### จุดสำคัญของ core

- **Model** รองรับ Soft Delete อัตโนมัติ (`deleted_at`) — `delete()` = ซ่อน, `restore()` = กู้คืน, `forceDelete()` = ลบถาวร
- **Controller** มี helper: `input()`, `query()`, `verifyCsrf()`, `flash()`, `redirect()`, `model()`, `adminView()`
- **View** แยก HTML ออกจาก PHP ครอบด้วย layout อัตโนมัติ (`adminView('news/index')` → `views/admin/news/index.php`)
- ทุกฟอร์มต้องมี `<?= Security::csrfField() ?>` และ controller ต้อง `verifyCsrf()` ก่อนบันทึก

---

## 7. ระบบสิทธิ์ (Roles & Permissions)

- ผู้ใช้ 1 คน → 1 บทบาท (role) → หลายสิทธิ์ (permissions) ผ่านตาราง `role_permissions`
- ตรวจสิทธิ์ในโค้ดด้วย `Auth::can('news.edit')` หรือใน controller `$this->requirePermission('news.view')`
- `superadmin` ผ่านทุกสิทธิ์เสมอ (`Auth::isSuperAdmin()`)

ตัวอย่าง permission slug ที่ใช้: `news.view`, `news.create`, `news.edit`, `news.delete`, `programs.manage`, `events.manage`, `banner.manage`, `gallery.manage`, `downloads.manage`, `pages.manage`, `contact.view`, `users.manage`, `settings.manage`

---

## 8. โมดูลหลังบ้าน (Admin)

เข้าที่ `/admin` เมนูด้านซ้ายมีทั้งหมด:

| เมนู | ความสามารถ |
|------|-----------|
| แดชบอร์ด | สรุปภาพรวม |
| จัดการข่าว | CRUD + หมวดหมู่ + Soft Delete + กู้คืน + SEO |
| หลักสูตร | CRUD หลักสูตร/สาขา |
| กิจกรรม | CRUD + ดูรายชื่อผู้ลงทะเบียน |
| แบนเนอร์ | CRUD สไลด์หน้าแรก |
| แกลเลอรี | CRUD อัลบั้ม + อัปโหลดรูปหลายไฟล์ + เพิ่มลิงก์วิดีโอ YouTube |
| ดาวน์โหลด | CRUD ไฟล์เอกสาร |
| หน้าเนื้อหา | CRUD หน้า static (slug, เทมเพลต, SEO) |
| คำถามพบบ่อย | CRUD FAQ |
| ข้อความติดต่อ | ดู/จัดการข้อความจากฟอร์มติดต่อ |
| ผู้ใช้งาน | CRUD ผู้ใช้ + โปรไฟล์ |
| ตั้งค่าเว็บไซต์ | ตั้งค่าทั่วไป (ชื่อเว็บ, ติดต่อ, โซเชียล ฯลฯ) |

---

## 9. ฐานข้อมูล

ฐานข้อมูล `fooddusit_db` (utf8mb4) มี **34 ตาราง** ครอบคลุม:

**ระบบสิทธิ์:** `roles`, `permissions`, `role_permissions`, `users`
**เนื้อหา:** `news`, `news_category`, `news_comments`, `pages`, `faq`, `announcements`
**วิชาการ:** `departments`, `programs`, `curriculum`, `staff`, `research`, `admissions`, `alumni`
**กิจกรรม/สื่อ:** `events`, `event_registrations`, `gallery_albums`, `gallery`, `banner`, `partners`
**ไฟล์/เมนู:** `downloads`, `documents`, `menu`, `settings`
**ติดต่อ/ระบบ:** `contact`, `newsletter_subscribers`, `visitor_logs`, `audit_logs`

> ทุกตารางหลักมี `created_at`, `updated_at`, `deleted_at` (soft delete) และ `status`

---

## 10. แนวทางพัฒนาต่อ (เพิ่มโมดูลใหม่)

ตัวอย่าง: เพิ่มโมดูลจัดการ "บุคลากร (staff)" ฝั่งหลังบ้าน

1. **Model** — `app/models/Staff.php` (มีอยู่แล้ว) กำหนด `protected string $table = 'staff';`
2. **Controller** — `app/controllers/admin/StaffController.php`
   ```php
   <?php
   namespace Admin;
   require_once __DIR__ . '/AdminController.php';
   class StaffController extends AdminController {
       public function __construct() {
           parent::__construct();
           $this->requirePermission('staff.manage');
       }
       public function index(): void {
           $pager = $this->model('Staff')->paginate((int)$this->query('page',1), ADMIN_PER_PAGE);
           $this->adminView('staff/index', ['title' => 'จัดการบุคลากร', 'pager' => $pager]);
       }
       // create/store/edit/update/delete ...
   }
   ```
3. **View** — สร้าง `app/views/admin/staff/index.php` และ `form.php` (ดูของ `downloads` เป็นตัวอย่างที่สั้นที่สุด)
4. **เมนู** — เพิ่มบรรทัดใน `$navItems` ที่ `app/views/layouts/admin.php`
5. **สิทธิ์** — เพิ่ม permission slug `staff.manage` ในตาราง `permissions` และผูกกับ role

> 💡 คัดลอกโมดูลที่คล้ายกันแล้วแก้ชื่อเป็นวิธีที่เร็วที่สุด — ทุกโมดูลใช้แพตเทิร์นเดียวกัน

---

## 11. การขึ้นโฮสต์จริง (Production)

1. ตั้ง `APP_ENV` เป็น `'production'` ใน `config.php` (ซ่อน error, เขียน log แทน)
2. แก้ `BASE_URL` เป็นโดเมนจริง เช่น `https://food.dusit.ac.th/`
3. แก้ `RewriteBase` ใน `.htaccess` (ราก) ให้ตรง path จริง (ถ้าอยู่ root domain ใช้ `/`)
4. แก้ค่าเชื่อมต่อใน `database.php` ตามที่โฮสต์ให้มา
5. **เปลี่ยนรหัสผ่านบัญชี admin ทั้งหมด**
6. ตั้งค่าโฟลเดอร์ `public/uploads` และ `storage` ให้เขียนได้ (chmod 755)
7. เปิด HTTPS — cookie จะตั้ง `secure` อัตโนมัติเมื่อเจอ HTTPS
8. ชี้ DocumentRoot ไปที่โฟลเดอร์ `public/` โดยตรง (ปลอดภัยกว่า) หากโฮสต์ตั้งได้

---

## 12. แก้ปัญหาที่พบบ่อย (Troubleshooting)

| อาการ | สาเหตุ / วิธีแก้ |
|-------|-----------------|
| เปิดเว็บแล้วขึ้น **404 / ไฟล์ .php ถูกดาวน์โหลด** | Apache ไม่ได้เปิด `mod_rewrite` → เปิดใน `httpd.conf` (`LoadModule rewrite_module`) และตั้ง `AllowOverride All` |
| **ไม่พบไฟล์ View: xxx** | ชื่อ view/โฟลเดอร์ไม่ตรง หรือไฟล์ยังไม่ถูกสร้าง |
| **เชื่อมต่อฐานข้อมูลไม่ได้** | ตรวจ `database.php` (host/user/password) และ MySQL รันอยู่จริง + import DB แล้ว |
| **CSS/JS ไม่โหลด / เว็บหน้าตาเพี้ยน** | `BASE_URL` ไม่ตรงกับ path จริง → แก้ใน `config.php` |
| **ล็อกอินไม่ได้ทั้งที่รหัสถูก** | โดนล็อกจากการพิมพ์ผิดหลายครั้ง (รอ 15 นาที หรือแก้ที่ตาราง users/ล้าง session) |
| **อัปโหลดรูปไม่ได้** | ไฟล์เกิน 5 MB, นามสกุลไม่อนุญาต, หรือโฟลเดอร์ `public/uploads` เขียนไม่ได้ |
| **ภาษาไทยเป็นตัวยึกยือ (?????)** | ฐานข้อมูลไม่ใช่ utf8mb4 → import `schema.sql` ใหม่ (มีตั้ง charset ให้แล้ว) |

---

## เทคโนโลยีที่ใช้

- **Backend:** PHP 8+ (MVC เขียนเอง), PDO, MySQL/MariaDB
- **Frontend:** Bootstrap 5.3, Font Awesome 6, Google Fonts (Sarabun, Prompt) — โหลดผ่าน CDN
- **ความปลอดภัย:** CSRF token, prepared statements, ตรวจ MIME จริงตอนอัปโหลด, security headers (CSP, X-Frame-Options), session แบบ HttpOnly + SameSite, hash รหัสผ่านด้วย bcrypt

---

_พัฒนาสำหรับ โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต_
