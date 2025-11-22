-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 22, 2025 at 07:40 AM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

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

DROP TABLE IF EXISTS `book_title`;
CREATE TABLE `book_title` (
  `title_id` int(11) NOT NULL COMMENT 'รหัสชื่อเรื่อง (Primary Key)',
  `category_id` int(11) DEFAULT NULL COMMENT 'รหัสหมวดหมู่',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ชื่อเรื่อง',
  `title_image` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รูปภาพหนังสือ',
  `author` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ผู้แต่ง',
  `isbn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ISBN (รหัสมาตรฐานสากลประจำหนังสือ)',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รายละเอียดหนังสือ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : ชื่อเรื่องหนังสือหลัก';

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `category_id` int(11) NOT NULL COMMENT 'รหัสหมวดหมู่ (Primary Key)',
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ชื่อหมวดหมู่ เช่น เทคโนโลยี การบริหาร'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : หมวดหมู่';

-- --------------------------------------------------------

--
-- Table structure for table `ebook`
--

DROP TABLE IF EXISTS `ebook`;
CREATE TABLE `ebook` (
  `ebook_id` int(11) NOT NULL COMMENT 'รหัส E-Book (Primary Key)',
  `title_id` int(11) DEFAULT NULL COMMENT 'รหัสชื่อเรื่อง',
  `ebook_file` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ไฟล์ ebook'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : หนังสืออิเล็กทรอนิกส์';

-- --------------------------------------------------------

--
-- Table structure for table `ebook_log`
--

DROP TABLE IF EXISTS `ebook_log`;
CREATE TABLE `ebook_log` (
  `ebooklog_id` int(11) NOT NULL COMMENT 'รหัสการเก็บ',
  `ebook_id` int(11) DEFAULT NULL COMMENT 'รหัส E-book',
  `member_id` int(11) DEFAULT NULL COMMENT 'รหัสสมาชิก',
  `ebooklog_date` timestamp NULL DEFAULT NULL COMMENT 'วันเวลาที่เปิดอ่าน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : บันทึกการอ่าน E-Book';

-- --------------------------------------------------------

--
-- Table structure for table `fine`
--

DROP TABLE IF EXISTS `fine`;
CREATE TABLE `fine` (
  `fine_id` int(11) NOT NULL COMMENT 'รหัสการปรับ',
  `loan_id` int(11) DEFAULT NULL COMMENT 'รหัสการยืม',
  `fine_amount` decimal(8,2) DEFAULT NULL COMMENT 'จำนวนเงินที่ปรับ',
  `incurred_date` date DEFAULT NULL COMMENT 'วันที่เริ่มคิดค่าปรับ',
  `paid_date` datetime DEFAULT NULL COMMENT 'วันที่มาชำระเงิน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : ค่าปรับ';

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

DROP TABLE IF EXISTS `loan`;
CREATE TABLE `loan` (
  `loan_id` int(11) NOT NULL COMMENT 'รหัสการยืม',
  `member_id` int(11) DEFAULT NULL COMMENT 'รหัสสมาชิก',
  `loan_date` date DEFAULT NULL COMMENT 'วันที่ยืม',
  `due_date` date DEFAULT NULL COMMENT 'วันครบกำหนดคืน',
  `return_date` date DEFAULT NULL COMMENT 'วันที่คืนจริง',
  `renewal_count` int(11) DEFAULT '0' COMMENT 'จำนวนครั้งที่ต่ออายุ',
  `status` enum('borrow','receive','somereturn','return') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'สถานะ (borrow ยืม, receive รับหนังสือ, somereturn คืนแล้วบางส่วน return คืนหนังสือ)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : การยืม';

-- --------------------------------------------------------

--
-- Table structure for table `loan_item`
--

DROP TABLE IF EXISTS `loan_item`;
CREATE TABLE `loan_item` (
  `loan_item_id` int(11) NOT NULL COMMENT 'รหัสรายละเอียดการยืม',
  `loan_id` int(11) DEFAULT NULL COMMENT 'รหัสการยืม',
  `copy_id` int(11) DEFAULT NULL COMMENT 'รหัสเล่มหนังสือ',
  `status` enum('borrow','return') COLLATE utf8mb4_unicode_ci DEFAULT 'borrow' COMMENT 'สถานะ (ยืม, คืน)',
  `return_date` date DEFAULT NULL COMMENT 'วันที่คืน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : รายละเอียดการยืม';

-- --------------------------------------------------------

--
-- Table structure for table `loan_renewal`
--

DROP TABLE IF EXISTS `loan_renewal`;
CREATE TABLE `loan_renewal` (
  `renewal_id` int(11) NOT NULL COMMENT 'รหัสการต่ออายุ',
  `loan_id` int(11) DEFAULT NULL COMMENT 'รหัสการยืม',
  `renewal_date` date DEFAULT NULL COMMENT 'วันที่ต่ออายุ',
  `renewal_count_date` int(11) DEFAULT NULL COMMENT 'จำนวนวันที่ขอต่ออายุ',
  `return_date` date DEFAULT NULL COMMENT 'วันที่ต้องคืน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : การต่ออายุ';

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `member_id` int(11) NOT NULL COMMENT 'รหัสสมาชิก (PK)',
  `emloyee_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสพนักงาน',
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ชื่อจริง',
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'นามสกุล',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'อีเมล (ใช้ติดต่อ)',
  `tel` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'เบอร์โทรติดต่อ',
  `staus` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'สถานะ (''active'', ''inactive'', ''pending'')'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : สมาชิก';

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `emloyee_id`, `first_name`, `last_name`, `email`, `tel`, `staus`) VALUES
(1, '0000', 'ผู้ดูแลระบบ', 'เจ้าหน้าที่ห้องสมุด', 'admin@mail.com', '0999999999', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `physical_copy`
--

DROP TABLE IF EXISTS `physical_copy`;
CREATE TABLE `physical_copy` (
  `copy_id` int(11) NOT NULL COMMENT 'รหัสฉบับเล่ม',
  `title_id` int(11) DEFAULT NULL COMMENT 'รหัสชื่อเรื่อง',
  `accession_no` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'เลขทะเบียน/รหัสประจำเล่ม (ใช้ติดตามการยืม-คืน)',
  `status` enum('available','on_loan','lost') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'สถานะ (''available'', ''on_loan'', ''lost'')'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง :  หนังสือที่เป็นเล่ม';

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL COMMENT 'รหัส',
  `max_loan_days` int(11) DEFAULT NULL COMMENT 'วันที่ยืมได้สูงสุด',
  `renew_days_before_due` int(11) DEFAULT NULL COMMENT 'ต่ออายุก่อนหมดอายุ',
  `renew_days_after_due` int(11) DEFAULT NULL COMMENT 'ต่ออายุหลังหมดอายุ',
  `max_renew_count` int(11) DEFAULT NULL COMMENT 'จำนวนครั้งยืมต่อสูงสุด',
  `max_renew_days` int(11) DEFAULT NULL COMMENT 'จำนวนวันที่ต่ออายุได้สูงสุด',
  `fine_per_day` int(11) DEFAULT NULL COMMENT 'ค่าปรับต่อวัน'
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

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL COMMENT 'รหัสผู้ใช้งาน (PK)',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อผู้ใช้สำหรับ Login',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสผ่านที่ถูกเข้ารหัส',
  `role` enum('member','admin') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'สิทธิ์การใช้งาน (''member'', ''admin'')',
  `member_id` int(11) DEFAULT NULL COMMENT 'รหัสสมาชิก'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตาราง : ผู้ใช้ระบบ';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `role`, `member_id`) VALUES
(1, 'admin', '$2y$10$NB9oPlgek0SQ1HqQgfXKfusahEG6KnxIaVQX14jRbtRUVCi6NkTse', 'admin', 1);

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
  ADD UNIQUE KEY `member_unique` (`emloyee_id`);

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
  MODIFY `title_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสชื่อเรื่อง (Primary Key)';

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสหมวดหมู่ (Primary Key)';

--
-- AUTO_INCREMENT for table `ebook`
--
ALTER TABLE `ebook`
  MODIFY `ebook_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัส E-Book (Primary Key)';

--
-- AUTO_INCREMENT for table `ebook_log`
--
ALTER TABLE `ebook_log`
  MODIFY `ebooklog_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสการเก็บ';

--
-- AUTO_INCREMENT for table `fine`
--
ALTER TABLE `fine`
  MODIFY `fine_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสการปรับ';

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสการยืม';

--
-- AUTO_INCREMENT for table `loan_item`
--
ALTER TABLE `loan_item`
  MODIFY `loan_item_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายละเอียดการยืม';

--
-- AUTO_INCREMENT for table `loan_renewal`
--
ALTER TABLE `loan_renewal`
  MODIFY `renewal_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสการต่ออายุ';

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสสมาชิก (PK)', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `physical_copy`
--
ALTER TABLE `physical_copy`
  MODIFY `copy_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสฉบับเล่ม';

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัส', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสผู้ใช้งาน (PK)', AUTO_INCREMENT=2;

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
