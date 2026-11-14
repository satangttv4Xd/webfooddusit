-- ============================================================
--  ข้อมูลตัวอย่าง (Seeder) สำหรับเว็บไซต์โรงเรียนการเรือน มสด.
--  ไฟล์: seed.sql  (import หลังจาก schema.sql)
-- ------------------------------------------------------------
--  บัญชีเข้าสู่ระบบตัวอย่าง (username / password):
--    superadmin / admin123     (ผู้ดูแลระบบสูงสุด)
--    admin      / admin123      (ผู้ดูแลระบบ)
--    editor     / editor123     (บรรณาธิการ)
--    teacher    / teacher123    (อาจารย์)
--    staff      / staff123      (เจ้าหน้าที่)
-- ============================================================

SET NAMES utf8mb4;
USE `fooddusit_db`;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------- roles ----------
INSERT INTO `roles` (`id`,`name`,`slug`,`description`,`status`,`created_at`,`updated_at`) VALUES
(1,'ผู้ดูแลระบบสูงสุด','super_admin','เข้าถึงและจัดการได้ทุกส่วน','active',NOW(),NOW()),
(2,'ผู้ดูแลระบบ','admin','จัดการเนื้อหาและผู้ใช้ส่วนใหญ่','active',NOW(),NOW()),
(3,'บรรณาธิการ','editor','จัดการข่าว/บทความ/กิจกรรม','active',NOW(),NOW()),
(4,'อาจารย์','teacher','จัดการหลักสูตร/งานวิจัย','active',NOW(),NOW()),
(5,'เจ้าหน้าที่','staff','จัดการเอกสาร/ดาวน์โหลด/ติดต่อ','active',NOW(),NOW());

-- ---------- permissions ----------
INSERT INTO `permissions` (`name`,`slug`,`module`,`status`,`created_at`) VALUES
('ดูข่าว','news.view','news','active',NOW()),
('เพิ่มข่าว','news.create','news','active',NOW()),
('แก้ไขข่าว','news.edit','news','active',NOW()),
('ลบข่าว','news.delete','news','active',NOW()),
('จัดการหลักสูตร','programs.manage','programs','active',NOW()),
('จัดการกิจกรรม','events.manage','events','active',NOW()),
('จัดการแกลเลอรี','gallery.manage','gallery','active',NOW()),
('จัดการดาวน์โหลด','downloads.manage','downloads','active',NOW()),
('จัดการแบนเนอร์','banner.manage','banner','active',NOW()),
('จัดการหน้าเนื้อหา','pages.manage','pages','active',NOW()),
('จัดการผู้ใช้','users.manage','users','active',NOW()),
('จัดการสิทธิ์','roles.manage','roles','active',NOW()),
('จัดการตั้งค่า','settings.manage','settings','active',NOW()),
('ดูข้อความติดต่อ','contact.view','contact','active',NOW()),
('จัดการปฏิทินงาน','schedules.manage','schedules','active',NOW());

-- ---------- role_permissions (editor = ข่าว/กิจกรรม/แกลเลอรี) ----------
INSERT INTO `role_permissions` (`role_id`,`permission_id`,`created_at`)
SELECT 3, id, NOW() FROM permissions WHERE slug IN ('news.view','news.create','news.edit','news.delete','events.manage','gallery.manage');
-- teacher = หลักสูตร/งานวิจัย
INSERT INTO `role_permissions` (`role_id`,`permission_id`,`created_at`)
SELECT 4, id, NOW() FROM permissions WHERE slug IN ('programs.manage','news.view');
-- staff = เอกสาร/ดาวน์โหลด/ติดต่อ
INSERT INTO `role_permissions` (`role_id`,`permission_id`,`created_at`)
SELECT 5, id, NOW() FROM permissions WHERE slug IN ('downloads.manage','contact.view');
-- admin = เกือบทั้งหมด ยกเว้นจัดการสิทธิ์ระดับสูง
INSERT INTO `role_permissions` (`role_id`,`permission_id`,`created_at`)
SELECT 2, id, NOW() FROM permissions WHERE slug <> 'roles.manage';

-- ---------- users ----------
INSERT INTO `users` (`role_id`,`username`,`email`,`password`,`full_name`,`phone`,`position`,`status`,`created_at`,`updated_at`) VALUES
(1,'superadmin','superadmin@dusit.ac.th','$2y$10$usChmr50aNrgurcuAISrneOKM.1sP7n7kY6Hwsil7.5qVyLkX4y06','ผู้ดูแลระบบสูงสุด','021234567','ผู้ดูแลระบบ','active',NOW(),NOW()),
(2,'admin','admin@dusit.ac.th','$2y$10$usChmr50aNrgurcuAISrneOKM.1sP7n7kY6Hwsil7.5qVyLkX4y06','ผู้ดูแลระบบ','021234568','ผู้ดูแลระบบ','active',NOW(),NOW()),
(3,'editor','editor@dusit.ac.th','$2y$10$SoAi7uIesAo9GeaUDK98Uevt2NEyAClL9fNhSw7nwoSG4EuARU2D2','บรรณาธิการข่าว','021234569','บรรณาธิการ','active',NOW(),NOW()),
(4,'teacher','teacher@dusit.ac.th','$2y$10$RCfOOb1/vtIVas3amOUvced9wlMD5s8YSAzXCb5eWTpVXg/Z089W.','อาจารย์ประจำ','021234570','อาจารย์','active',NOW(),NOW()),
(5,'staff','staff@dusit.ac.th','$2y$10$MvtAHIDjd.U0oOiazrOoXuwgAqg.makUEM5dZZ4KTm7udR9twzA0G','เจ้าหน้าที่ธุรการ','021234571','เจ้าหน้าที่','active',NOW(),NOW());

-- ---------- departments ----------
INSERT INTO `departments` (`name`,`slug`,`description`,`head_name`,`sort_order`,`status`,`created_at`,`updated_at`) VALUES
('สาขาวิชาเทคโนโลยีการประกอบอาหารและการบริการ','culinary-tech','หลักสูตรด้านการประกอบอาหารและการบริการมืออาชีพ','ผศ.ดร. ก. ครัวทอง',1,'active',NOW(),NOW()),
('สาขาวิชาคหกรรมศาสตร์','home-economics','ศาสตร์แห่งการจัดการครัวเรือนและงานประดิษฐ์',' ผศ. ข. เรือนไทย',2,'active',NOW(),NOW()),
('สาขาวิชาโภชนาการและการประกอบอาหารเพื่อการส่งเสริมสมรรถภาพและการชะลอวัย','nutrition','โภชนาการเชิงสุขภาพและการชะลอวัย','ดร. ค. โภชนา',3,'active',NOW(),NOW());

-- ---------- news_category ----------
INSERT INTO `news_category` (`name`,`slug`,`description`,`sort_order`,`status`,`created_at`,`updated_at`) VALUES
('ข่าวประชาสัมพันธ์','announcement','ข่าวสารทั่วไปของโรงเรียนการเรือน',1,'active',NOW(),NOW()),
('ข่าวรับสมัคร','admission','ข่าวการรับสมัครนักศึกษาและอบรม',2,'active',NOW(),NOW()),
('ข่าวกิจกรรม','activity','ภาพและข่าวกิจกรรมต่าง ๆ',3,'active',NOW(),NOW()),
('ข่าวรางวัลและความภาคภูมิใจ','award','รางวัลของนักศึกษาและบุคลากร',4,'active',NOW(),NOW());

-- ---------- news ----------
INSERT INTO `news` (`category_id`,`user_id`,`title`,`slug`,`excerpt`,`content`,`tags`,`views`,`is_featured`,`status`,`published_at`,`created_at`,`updated_at`) VALUES
(2,3,'เปิดรับสมัครนักศึกษาใหม่ ปีการศึกษา 2569','open-admission-2569','โรงเรียนการเรือน เปิดรับสมัครนักศึกษาใหม่ระดับปริญญาตรี ประจำปีการศึกษา 2569 แล้ววันนี้','<p>โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต เปิดรับสมัครนักศึกษาใหม่ระดับปริญญาตรี ประจำปีการศึกษา 2569 ในหลากหลายสาขาวิชา ผู้สนใจสามารถสมัครได้ทางระบบออนไลน์ตั้งแต่บัดนี้เป็นต้นไป</p><p>สอบถามข้อมูลเพิ่มเติมได้ที่สำนักงานโรงเรียนการเรือน</p>','รับสมัคร,ปริญญาตรี,2569',245,1,'published',NOW(),NOW(),NOW()),
(3,3,'นักศึกษาคว้ารางวัลชนะเลิศการแข่งขันทำอาหารระดับประเทศ','win-cooking-contest','ขอแสดงความยินดีกับนักศึกษาที่คว้ารางวัลชนะเลิศจากการแข่งขันทำอาหารระดับประเทศ','<p>นักศึกษาโรงเรียนการเรือน สร้างชื่อเสียงคว้ารางวัลชนะเลิศจากการแข่งขันทำอาหารระดับประเทศ ตอกย้ำคุณภาพการเรียนการสอนด้านการประกอบอาหารระดับแนวหน้าของประเทศ</p>','รางวัล,แข่งขัน,ทำอาหาร',180,1,'published',NOW(),NOW(),NOW()),
(1,3,'โครงการอบรมระยะสั้น หลักสูตรเบเกอรีมืออาชีพ','short-course-bakery','เปิดอบรมหลักสูตรเบเกอรีสำหรับบุคคลทั่วไป เรียนกับเชฟมืออาชีพ','<p>โรงเรียนการเรือน จัดโครงการอบรมระยะสั้น หลักสูตรเบเกอรีมืออาชีพ สำหรับบุคคลทั่วไปที่สนใจ เรียนกับคณาจารย์และเชฟมืออาชีพ พร้อมรับใบประกาศนียบัตร</p>','อบรม,เบเกอรี,ระยะสั้น',95,0,'published',NOW(),NOW(),NOW()),
(1,3,'พิธีลงนามความร่วมมือทางวิชาการกับโรงแรมชั้นนำ','mou-hotel','โรงเรียนการเรือนลงนาม MOU กับโรงแรมชั้นนำเพื่อพัฒนานักศึกษาสู่มืออาชีพ','<p>โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต ลงนามบันทึกข้อตกลงความร่วมมือทางวิชาการกับโรงแรมชั้นนำ เพื่อเปิดโอกาสให้นักศึกษาได้ฝึกประสบการณ์วิชาชีพในสถานประกอบการจริง</p>','MOU,ความร่วมมือ',77,0,'published',NOW(),NOW(),NOW());

-- ---------- programs ----------
INSERT INTO `programs` (`department_id`,`name`,`name_en`,`slug`,`level`,`degree`,`duration`,`credits`,`tuition_fee`,`overview`,`qualification`,`careers`,`is_featured`,`sort_order`,`status`,`created_at`,`updated_at`) VALUES
(1,'เทคโนโลยีการประกอบอาหารและการบริการ','Culinary Technology and Services','bachelor-culinary','bachelor','วิทยาศาสตรบัณฑิต (วท.บ.)','4 ปี','ไม่น้อยกว่า 130 หน่วยกิต','ประมาณ 25,000 บาท/ภาคเรียน','หลักสูตรที่มุ่งผลิตบัณฑิตด้านการประกอบอาหารและการบริการระดับมืออาชีพ ผสานทฤษฎีและปฏิบัติในครัวมาตรฐานสากล','สำเร็จการศึกษาระดับมัธยมศึกษาตอนปลายหรือเทียบเท่า','เชฟ, ผู้จัดการครัว, ผู้ประกอบการร้านอาหาร, นักพัฒนาผลิตภัณฑ์อาหาร',1,1,'active',NOW(),NOW()),
(2,'คหกรรมศาสตร์','Home Economics','bachelor-home-economics','bachelor','ศิลปศาสตรบัณฑิต (ศศ.บ.)','4 ปี','ไม่น้อยกว่า 129 หน่วยกิต','ประมาณ 23,000 บาท/ภาคเรียน','บูรณาการศาสตร์ด้านอาหาร ผ้าและเครื่องแต่งกาย งานประดิษฐ์ และการจัดการครัวเรือน','สำเร็จการศึกษาระดับมัธยมศึกษาตอนปลายหรือเทียบเท่า','นักคหกรรมศาสตร์, ครู, ผู้ประกอบการ, นักออกแบบ',1,2,'active',NOW(),NOW()),
(3,'โภชนาการและการประกอบอาหารเพื่อการชะลอวัย','Nutrition and Culinary for Anti-Aging','bachelor-nutrition','bachelor','วิทยาศาสตรบัณฑิต (วท.บ.)','4 ปี','ไม่น้อยกว่า 132 หน่วยกิต','ประมาณ 26,000 บาท/ภาคเรียน','เน้นองค์ความรู้ด้านโภชนาการเชิงสุขภาพและการประกอบอาหารเพื่อส่งเสริมสมรรถภาพและการชะลอวัย','สำเร็จการศึกษาระดับมัธยมศึกษาตอนปลาย สายวิทย์-คณิต','นักโภชนาการ, ที่ปรึกษาด้านสุขภาพ, ผู้ประกอบการอาหารสุขภาพ',1,3,'active',NOW(),NOW()),
(1,'อบรมเบเกอรีและเพสตรีมืออาชีพ','Professional Bakery & Pastry','training-bakery','training','ประกาศนียบัตร','40 ชั่วโมง','-','9,900 บาท','หลักสูตรอบรมระยะสั้นสำหรับผู้สนใจเริ่มต้นอาชีพเบเกอรี','บุคคลทั่วไป อายุ 15 ปีขึ้นไป','เจ้าของร้านเบเกอรี, ผู้ช่วยเชฟเพสตรี',0,4,'active',NOW(),NOW());

-- ---------- events ----------
INSERT INTO `events` (`title`,`slug`,`description`,`location`,`start_date`,`end_date`,`allow_register`,`is_featured`,`status`,`created_at`,`updated_at`) VALUES
('เทศกาลอาหารนานาชาติ Suan Dusit Food Fair','food-fair-2569','งานแสดงและจำหน่ายอาหารจากฝีมือนักศึกษาและคณาจารย์','อาคารโรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต','2026-08-20 09:00:00','2026-08-22 17:00:00',1,1,'active',NOW(),NOW()),
('เวิร์กช็อปการแกะสลักผักและผลไม้','carving-workshop','เรียนรู้ศิลปะการแกะสลักผักและผลไม้แบบไทย','ห้องปฏิบัติการคหกรรม','2026-09-05 13:00:00','2026-09-05 16:00:00',1,0,'active',NOW(),NOW()),
('สัมมนาวิชาการด้านโภชนาการเพื่อสุขภาพ','nutrition-seminar','สัมมนาโดยผู้เชี่ยวชาญด้านโภชนาการระดับประเทศ','หอประชุมมหาวิทยาลัยสวนดุสิต','2026-10-10 08:30:00','2026-10-10 16:30:00',1,0,'active',NOW(),NOW());

-- ---------- gallery_albums ----------
INSERT INTO `gallery_albums` (`name`,`slug`,`description`,`event_date`,`status`,`created_at`,`updated_at`) VALUES
('พิธีรับปริญญา ประจำปี 2568','graduation-2568','บรรยากาศพิธีรับปริญญาบัตร','2025-11-15','active',NOW(),NOW()),
('การแข่งขันทำอาหารภายใน','internal-cooking','ภาพการแข่งขันทำอาหารของนักศึกษา','2026-02-14','active',NOW(),NOW());

-- ---------- banner ----------
INSERT INTO `banner` (`title`,`subtitle`,`image`,`link_url`,`button_text`,`sort_order`,`status`,`created_at`,`updated_at`) VALUES
('โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต','ผู้นำด้านศาสตร์การประกอบอาหารและการบริการของประเทศไทย','banner/hero-1.svg','programs','ดูหลักสูตรทั้งหมด',1,'active',NOW(),NOW()),
('เปิดรับสมัครนักศึกษาใหม่ 2569','เรียนกับเชฟมืออาชีพในครัวมาตรฐานสากล','banner/hero-2.svg','news/detail/open-admission-2569','สมัครเรียนเลย',2,'active',NOW(),NOW());

-- ---------- downloads ----------
INSERT INTO `downloads` (`title`,`description`,`category`,`file_path`,`file_type`,`file_size`,`download_count`,`status`,`created_at`,`updated_at`) VALUES
('ใบสมัครเข้าศึกษา ปีการศึกษา 2569','แบบฟอร์มใบสมัครสำหรับผู้สนใจเข้าศึกษา','ใบสมัคร','downloads/sample-application.pdf','pdf',102400,58,'active',NOW(),NOW()),
('คู่มือนักศึกษาใหม่','ข้อมูลที่นักศึกษาใหม่ควรทราบ','คู่มือ','downloads/sample-handbook.pdf','pdf',204800,120,'active',NOW(),NOW());

-- ---------- faq ----------
INSERT INTO `faq` (`question`,`answer`,`category`,`sort_order`,`status`,`created_at`,`updated_at`) VALUES
('เปิดรับสมัครนักศึกษาช่วงไหน?','โดยทั่วไปเปิดรับสมัครช่วงเดือนพฤศจิกายนถึงพฤษภาคมของทุกปี โปรดติดตามประกาศจากทางเว็บไซต์','การรับสมัคร',1,'active',NOW(),NOW()),
('มีหอพักสำหรับนักศึกษาหรือไม่?','มหาวิทยาลัยมีหอพักและมีเครือข่ายหอพักเอกชนใกล้เคียงให้เลือก','ทั่วไป',2,'active',NOW(),NOW()),
('เรียนจบแล้วประกอบอาชีพอะไรได้บ้าง?','เชฟ ผู้ประกอบการร้านอาหาร นักโภชนาการ นักพัฒนาผลิตภัณฑ์อาหาร และอื่น ๆ อีกมากมาย','หลักสูตร',3,'active',NOW(),NOW());

-- ---------- pages ----------
INSERT INTO `pages` (`title`,`slug`,`content`,`template`,`status`,`created_at`,`updated_at`) VALUES
('ประวัติความเป็นมา','history','<p>โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต มีรากฐานยาวนานด้านการเรียนการสอนศาสตร์การประกอบอาหารและคหกรรมศาสตร์ เป็นที่ยอมรับในระดับประเทศและนานาชาติ</p>','default','published',NOW(),NOW()),
('วิสัยทัศน์และพันธกิจ','vision-mission','<h3>วิสัยทัศน์</h3><p>เป็นผู้นำด้านศาสตร์การประกอบอาหารและการบริการของอาเซียน</p><h3>พันธกิจ</h3><ul><li>ผลิตบัณฑิตคุณภาพสูง</li><li>สร้างงานวิจัยและนวัตกรรมด้านอาหาร</li><li>บริการวิชาการสู่สังคม</li></ul>','default','published',NOW(),NOW());

-- ---------- menu ----------
INSERT INTO `menu` (`parent_id`,`title`,`url`,`position`,`sort_order`,`status`,`created_at`,`updated_at`) VALUES
(NULL,'หน้าแรก','','header',1,'active',NOW(),NOW()),
(NULL,'เกี่ยวกับเรา','about','header',2,'active',NOW(),NOW()),
(NULL,'หลักสูตร','programs','header',3,'active',NOW(),NOW()),
(NULL,'ข่าวสาร','news','header',4,'active',NOW(),NOW()),
(NULL,'กิจกรรม','events','header',5,'active',NOW(),NOW()),
(NULL,'ปฏิทินงาน','calendar','header',6,'active',NOW(),NOW()),
(NULL,'แกลเลอรี','gallery','header',7,'active',NOW(),NOW()),
(NULL,'ดาวน์โหลด','downloads','header',8,'active',NOW(),NOW()),
(NULL,'ติดต่อเรา','contact','header',9,'active',NOW(),NOW());

-- ---------- settings ----------
INSERT INTO `settings` (`key`,`value`,`group`,`type`,`label`,`status`,`created_at`,`updated_at`) VALUES
('site_name','โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต','general','text','ชื่อเว็บไซต์','active',NOW(),NOW()),
('site_description','ผู้นำด้านศาสตร์การประกอบอาหารและการบริการของประเทศไทย','general','textarea','คำอธิบายเว็บไซต์','active',NOW(),NOW()),
('site_email','info@fooddusit.ac.th','contact','text','อีเมลติดต่อ','active',NOW(),NOW()),
('site_phone','0-2244-5000','contact','text','โทรศัพท์','active',NOW(),NOW()),
('site_address','295 ถนนนครราชสีมา เขตดุสิต กรุงเทพมหานคร 10300','contact','textarea','ที่อยู่','active',NOW(),NOW()),
('facebook_url','https://facebook.com/','social','text','Facebook','active',NOW(),NOW()),
('youtube_url','https://youtube.com/','social','text','YouTube','active',NOW(),NOW()),
('line_url','#','social','text','Line','active',NOW(),NOW()),
('google_map','<iframe src="https://www.google.com/maps?q=%E0%B8%A1%E0%B8%AB%E0%B8%B2%E0%B8%A7%E0%B8%B4%E0%B8%97%E0%B8%A2%E0%B8%B2%E0%B8%A5%E0%B8%B1%E0%B8%A2%E0%B8%AA%E0%B8%A7%E0%B8%99%E0%B8%94%E0%B8%B8%E0%B8%B4%E0%B8%95&hl=th&z=16&output=embed" width="100%" height="450" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="แผนที่มหาวิทยาลัยสวนดุสิต"></iframe>','contact','textarea','Google Map','active',NOW(),NOW()),
('copyright','© 2569 โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต. สงวนลิขสิทธิ์.','general','text','ข้อความลิขสิทธิ์','active',NOW(),NOW());

-- ---------- staff ----------
INSERT INTO `staff` (`department_id`,`full_name`,`position`,`type`,`sort_order`,`status`,`created_at`,`updated_at`) VALUES
(NULL,'รศ.ดร. ผู้อำนวยการ โรงเรียนการเรือน','ผู้อำนวยการโรงเรียนการเรือน','executive',1,'active',NOW(),NOW()),
(NULL,'ผศ.ดร. รองผู้อำนวยการ','รองผู้อำนวยการฝ่ายวิชาการ','executive',2,'active',NOW(),NOW()),
(1,'อาจารย์ประจำสาขา','อาจารย์','teacher',3,'active',NOW(),NOW());

-- ---------- partners ----------
INSERT INTO `partners` (`name`,`website`,`sort_order`,`status`,`created_at`,`updated_at`) VALUES
('โรงแรมพันธมิตร','#',1,'active',NOW(),NOW()),
('สมาคมเชฟแห่งประเทศไทย','#',2,'active',NOW(),NOW()),
('บริษัทอาหารชั้นนำ','#',3,'active',NOW(),NOW());

-- ---------- alumni ----------
INSERT INTO `alumni` (`full_name`,`graduate_year`,`program`,`current_job`,`organization`,`quote`,`is_featured`,`status`,`created_at`,`updated_at`) VALUES
('ศิษย์เก่าดีเด่น 1','2560','เทคโนโลยีการประกอบอาหาร','Executive Chef','โรงแรมระดับ 5 ดาว','โรงเรียนการเรือนให้ทั้งความรู้และโอกาส','1','active',NOW(),NOW()),
('ศิษย์เก่าดีเด่น 2','2562','คหกรรมศาสตร์','เจ้าของกิจการ','ร้านเบเกอรีชื่อดัง','ทุกวันนี้ประสบความสำเร็จได้เพราะพื้นฐานที่แข็งแรง','1','active',NOW(),NOW());

SET FOREIGN_KEY_CHECKS = 1;
-- ============================================================
--  จบไฟล์ seed.sql
-- ============================================================
