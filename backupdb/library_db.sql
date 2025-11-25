-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 25, 2025 at 03:45 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `book_title`
--

CREATE TABLE `book_title` (
  `title_id` int NOT NULL COMMENT 'รหัสชื่อเรื่อง (Primary Key)',
  `category_id` int DEFAULT NULL COMMENT 'รหัสหมวดหมู่',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ชื่อเรื่อง',
  `image` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รูปภาพหนังสือ',
  `author` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ผู้แต่ง',
  `isbn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ISBN (รหัสมาตรฐานสากลประจำหนังสือ)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รายละเอียดหนังสือ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : ชื่อเรื่องหนังสือหลัก';

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int NOT NULL COMMENT 'รหัสหมวดหมู่ (Primary Key)',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ชื่อหมวดหมู่ เช่น เทคโนโลยี การบริหาร',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'คำอธิบาย'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : หมวดหมู่';

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`, `description`) VALUES
(1, 'เทคโนโลยีและคอมพิวเตอร์', 'หนังสือเกี่ยวกับคอมพิวเตอร์ การเขียนโปรแกรม และเทคโนโลยีสมัยใหม่'),
(2, 'วรรณกรรมและนวนิยาย', 'รวมนิยาย เรื่องสั้น และวรรณกรรมแปลจากต่างประเทศ'),
(3, 'การบริหารและการจัดการ', 'คู่มือการบริหารธุรกิจ การตลาด และการพัฒนาองค์กร'),
(4, 'วิทยาศาสตร์และธรรมชาติ', 'ความรู้ทางวิทยาศาสตร์ ชีววิทยา และสิ่งแวดล้อม'),
(5, 'ประวัติศาสตร์และภูมิศาสตร์', 'เรื่องราวทางประวัติศาสตร์ และภูมิศาสตร์ของประเทศต่างๆ');

-- --------------------------------------------------------

--
-- Table structure for table `ebook`
--

CREATE TABLE `ebook` (
  `ebook_id` int NOT NULL COMMENT 'รหัส E-Book (Primary Key)',
  `title_id` int DEFAULT NULL COMMENT 'รหัสชื่อเรื่อง',
  `ebook_file` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ไฟล์ ebook'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : หนังสืออิเล็กทรอนิกส์';

-- --------------------------------------------------------

--
-- Table structure for table `ebook_log`
--

CREATE TABLE `ebook_log` (
  `ebooklog_id` int NOT NULL COMMENT 'รหัสการเก็บ',
  `ebook_id` int DEFAULT NULL COMMENT 'รหัส E-book',
  `member_id` int DEFAULT NULL COMMENT 'รหัสสมาชิก',
  `ebooklog_date` timestamp NULL DEFAULT NULL COMMENT 'วันเวลาที่เปิดอ่าน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : บันทึกการอ่าน E-Book';

-- --------------------------------------------------------

--
-- Table structure for table `fine`
--

CREATE TABLE `fine` (
  `fine_id` int NOT NULL COMMENT 'รหัสการปรับ',
  `loan_id` int DEFAULT NULL COMMENT 'รหัสการยืม',
  `fine_amount` decimal(8,2) DEFAULT NULL COMMENT 'จำนวนเงินที่ปรับ',
  `incurred_date` date DEFAULT NULL COMMENT 'วันที่เริ่มคิดค่าปรับ',
  `paid_date` datetime DEFAULT NULL COMMENT 'วันที่มาชำระเงิน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : ค่าปรับ';

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `loan_id` int NOT NULL COMMENT 'รหัสการยืม',
  `member_id` int DEFAULT NULL COMMENT 'รหัสสมาชิก',
  `loan_date` date DEFAULT NULL COMMENT 'วันที่ยืม',
  `due_date` date DEFAULT NULL COMMENT 'วันครบกำหนดคืน',
  `return_date` date DEFAULT NULL COMMENT 'วันที่คืนจริง',
  `renewal_count` int DEFAULT '0' COMMENT 'จำนวนครั้งที่ต่ออายุ',
  `status` enum('borrow','receive','somereturn','return') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'สถานะ (borrow ยืม, receive รับหนังสือ, somereturn คืนแล้วบางส่วน return คืนหนังสือ)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : การยืม';

-- --------------------------------------------------------

--
-- Table structure for table `loan_item`
--

CREATE TABLE `loan_item` (
  `loan_item_id` int NOT NULL COMMENT 'รหัสรายละเอียดการยืม',
  `loan_id` int DEFAULT NULL COMMENT 'รหัสการยืม',
  `copy_id` int DEFAULT NULL COMMENT 'รหัสเล่มหนังสือ',
  `status` enum('borrow','return') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'borrow' COMMENT 'สถานะ (ยืม, คืน)',
  `return_date` date DEFAULT NULL COMMENT 'วันที่คืน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : รายละเอียดการยืม';

-- --------------------------------------------------------

--
-- Table structure for table `loan_renewal`
--

CREATE TABLE `loan_renewal` (
  `renewal_id` int NOT NULL COMMENT 'รหัสการต่ออายุ',
  `loan_id` int DEFAULT NULL COMMENT 'รหัสการยืม',
  `renewal_date` date DEFAULT NULL COMMENT 'วันที่ต่ออายุ',
  `renewal_count_date` int DEFAULT NULL COMMENT 'จำนวนวันที่ขอต่ออายุ',
  `return_date` date DEFAULT NULL COMMENT 'วันที่ต้องคืน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : การต่ออายุ';

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int NOT NULL COMMENT 'รหัสสมาชิก (PK)',
  `employee_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสพนักงาน',
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ชื่อจริง',
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'นามสกุล',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'อีเมล (ใช้ติดต่อ)',
  `tel` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'เบอร์โทรติดต่อ',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'สถานะ (''active'', ''inactive'')'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : สมาชิก';

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `employee_id`, `first_name`, `last_name`, `email`, `tel`, `status`) VALUES
(1, '0000', 'เจ้าหน้าที่ดูแลห้องสมุด', 'ผู้ดูแลระบบ', 'admin@mail.com', '0999999999', 'active'),
(2, '0001', 'ผู้ใช้ระบบ', 'ทดสอบ', 'member@mail.com', '098989890', 'active'),
(3, '00002', 'สุพจน์', 'วงษ์ศรี', 'supod@mail.com', '0988888888', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `physical_copy`
--

CREATE TABLE `physical_copy` (
  `copy_id` int NOT NULL COMMENT 'รหัสฉบับเล่ม',
  `title_id` int DEFAULT NULL COMMENT 'รหัสชื่อเรื่อง',
  `accession_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'เลขทะเบียน/รหัสประจำเล่ม (ใช้ติดตามการยืม-คืน)',
  `status` enum('available','on_loan','lost') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'สถานะ (''available'', ''on_loan'', ''lost'')'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง :  หนังสือที่เป็นเล่ม';

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL COMMENT 'รหัส',
  `max_loan_days` int DEFAULT NULL COMMENT 'วันที่ยืมได้สูงสุด',
  `renew_days_before_due` int DEFAULT NULL COMMENT 'ต่ออายุก่อนหมดอายุ',
  `renew_days_after_due` int DEFAULT NULL COMMENT 'ต่ออายุหลังหมดอายุ',
  `max_renew_count` int DEFAULT NULL COMMENT 'จำนวนครั้งยืมต่อสูงสุด',
  `max_renew_days` int DEFAULT NULL COMMENT 'จำนวนวันที่ต่ออายุได้สูงสุด',
  `fine_per_day` int DEFAULT NULL COMMENT 'ค่าปรับต่อวัน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : การตั้งค่าต่าง ๆ';

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `max_loan_days`, `renew_days_before_due`, `renew_days_after_due`, `max_renew_count`, `max_renew_days`, `fine_per_day`) VALUES
(1, 7, 3, 3, 3, 30, 50);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL COMMENT 'รหัสผู้ใช้งาน (PK)',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อผู้ใช้สำหรับ Login',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสผ่านที่ถูกเข้ารหัส',
  `role` enum('member','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'สิทธิ์การใช้งาน (''member'', ''admin'')',
  `member_id` int DEFAULT NULL COMMENT 'รหัสสมาชิก',
  `change_password` int NOT NULL DEFAULT '0' COMMENT 'ตรวจสอบว่าเปลี่นรหัสผ่านหรือยัง 0 = ยังไม่ได้เปลี่ยนใช้รหัสผ่านเริ่มต้นอยู่ 1 = เปลี่ยนแล้ว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : ผู้ใช้ระบบ';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `role`, `member_id`, `change_password`) VALUES
(1, 'admin', '$2y$10$NB9oPlgek0SQ1HqQgfXKfusahEG6KnxIaVQX14jRbtRUVCi6NkTse', 'admin', 1, 1),
(2, 'member', '$2y$10$M39a9Ph.oE8HB8bfMf1KjOw4qrXs.hrdZHuSEjyA1ihcSg8iRbWBC', 'member', 2, 0),
(3, 'supod', '$2y$10$0ToyjYdBU2k1LlfIEvRF/.MqfwiAgaaWz9lz25sVJUYBPKddP1bt6', 'member', 3, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book_title`
--
ALTER TABLE `book_title`
  ADD PRIMARY KEY (`title_id`),
  ADD KEY `book_title_category_FK` (`category_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `ebook`
--
ALTER TABLE `ebook`
  ADD PRIMARY KEY (`ebook_id`),
  ADD KEY `ebook_book_title_FK` (`title_id`);

--
-- Indexes for table `ebook_log`
--
ALTER TABLE `ebook_log`
  ADD PRIMARY KEY (`ebooklog_id`),
  ADD KEY `ebook_log_ebook_FK` (`ebook_id`),
  ADD KEY `ebook_log_member_FK` (`member_id`);

--
-- Indexes for table `fine`
--
ALTER TABLE `fine`
  ADD PRIMARY KEY (`fine_id`),
  ADD KEY `fine_loan_FK` (`loan_id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `loan_member_FK` (`member_id`);

--
-- Indexes for table `loan_item`
--
ALTER TABLE `loan_item`
  ADD PRIMARY KEY (`loan_item_id`),
  ADD KEY `loan_item_loan_FK` (`loan_id`),
  ADD KEY `loan_item_physical_copy_FK` (`copy_id`);

--
-- Indexes for table `loan_renewal`
--
ALTER TABLE `loan_renewal`
  ADD PRIMARY KEY (`renewal_id`),
  ADD KEY `loan_renewal_loan_FK` (`loan_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `member_unique` (`employee_id`);

--
-- Indexes for table `physical_copy`
--
ALTER TABLE `physical_copy`
  ADD PRIMARY KEY (`copy_id`),
  ADD UNIQUE KEY `physical_copy_unique` (`accession_no`),
  ADD KEY `physical_copy_book_title_FK` (`title_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `user_member_FK` (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book_title`
--
ALTER TABLE `book_title`
  MODIFY `title_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสชื่อเรื่อง (Primary Key)';

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสหมวดหมู่ (Primary Key)', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ebook`
--
ALTER TABLE `ebook`
  MODIFY `ebook_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัส E-Book (Primary Key)';

--
-- AUTO_INCREMENT for table `ebook_log`
--
ALTER TABLE `ebook_log`
  MODIFY `ebooklog_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสการเก็บ';

--
-- AUTO_INCREMENT for table `fine`
--
ALTER TABLE `fine`
  MODIFY `fine_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสการปรับ';

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `loan_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสการยืม';

--
-- AUTO_INCREMENT for table `loan_item`
--
ALTER TABLE `loan_item`
  MODIFY `loan_item_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายละเอียดการยืม';

--
-- AUTO_INCREMENT for table `loan_renewal`
--
ALTER TABLE `loan_renewal`
  MODIFY `renewal_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสการต่ออายุ';

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสสมาชิก (PK)', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `physical_copy`
--
ALTER TABLE `physical_copy`
  MODIFY `copy_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสฉบับเล่ม';

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัส', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสผู้ใช้งาน (PK)', AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book_title`
--
ALTER TABLE `book_title`
  ADD CONSTRAINT `book_title_category_FK` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Constraints for table `ebook`
--
ALTER TABLE `ebook`
  ADD CONSTRAINT `ebook_book_title_FK` FOREIGN KEY (`title_id`) REFERENCES `book_title` (`title_id`);

--
-- Constraints for table `ebook_log`
--
ALTER TABLE `ebook_log`
  ADD CONSTRAINT `ebook_log_ebook_FK` FOREIGN KEY (`ebook_id`) REFERENCES `ebook` (`ebook_id`),
  ADD CONSTRAINT `ebook_log_member_FK` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`);

--
-- Constraints for table `fine`
--
ALTER TABLE `fine`
  ADD CONSTRAINT `fine_loan_FK` FOREIGN KEY (`loan_id`) REFERENCES `loan` (`loan_id`);

--
-- Constraints for table `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `loan_member_FK` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`);

--
-- Constraints for table `loan_item`
--
ALTER TABLE `loan_item`
  ADD CONSTRAINT `loan_item_loan_FK` FOREIGN KEY (`loan_id`) REFERENCES `loan` (`loan_id`),
  ADD CONSTRAINT `loan_item_physical_copy_FK` FOREIGN KEY (`copy_id`) REFERENCES `physical_copy` (`copy_id`);

--
-- Constraints for table `loan_renewal`
--
ALTER TABLE `loan_renewal`
  ADD CONSTRAINT `loan_renewal_loan_FK` FOREIGN KEY (`loan_id`) REFERENCES `loan` (`loan_id`);

--
-- Constraints for table `physical_copy`
--
ALTER TABLE `physical_copy`
  ADD CONSTRAINT `physical_copy_book_title_FK` FOREIGN KEY (`title_id`) REFERENCES `book_title` (`title_id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_member_FK` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
