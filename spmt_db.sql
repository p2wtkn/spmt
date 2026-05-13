-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 08:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spmt_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `learner_demand`
--

CREATE TABLE `learner_demand` (
  `ld_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `convenience_day` date NOT NULL,
  `convenience_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learner_demand`
--

INSERT INTO `learner_demand` (`ld_id`, `user_id`, `subject_id`, `convenience_day`, `convenience_time`) VALUES
(1, 5, 2, '2026-04-22', 1),
(2, 9, 2, '2026-05-04', 1),
(3, 9, 2, '2026-05-04', 1),
(4, 1, 1, '2026-05-04', 1),
(8, 2, 2, '2026-05-05', 3),
(9, 2, 5, '2026-05-05', 8),
(11, 1, 2, '2026-05-06', 3),
(12, 1, 4, '2026-05-06', 8),
(13, 1, 3, '2026-05-06', 8),
(14, 1, 5, '2026-05-06', 8),
(15, 1, 5, '2026-05-06', 3),
(16, 1, 5, '2026-05-06', 3),
(17, 1, 5, '2026-05-06', 3),
(18, 1, 5, '2026-05-06', 3),
(19, 1, 3, '2026-05-06', 5),
(20, 1, 3, '2026-05-06', 6),
(21, 1, 3, '2026-05-06', 4);

-- --------------------------------------------------------

--
-- Table structure for table `redemptions`
--

CREATE TABLE `redemptions` (
  `redemption_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `redemption_date` date NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `redemptions`
--

INSERT INTO `redemptions` (`redemption_id`, `user_id`, `reward_id`, `redemption_date`, `status`) VALUES
(1, 1, 1, '2026-05-06', 0),
(2, 1, 1, '2026-05-06', 0),
(3, 1, 1, '2026-05-06', 0),
(4, 1, 1, '2026-05-06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reward`
--

CREATE TABLE `reward` (
  `reward_id` int(11) NOT NULL,
  `reward_name` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  `required_points` int(11) NOT NULL,
  `reward_quota` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reward`
--

INSERT INTO `reward` (`reward_id`, `reward_name`, `description`, `required_points`, `reward_quota`) VALUES
(1, 'กาแฟอเมริกาโน่ฟรี', 'แลกรับกาแฟอเมริกาโน่เย็นขนาดปกติ 1 แก้ว', 50, 0),
(2, 'บัตรกำนัล Starbucks', 'บัตรกำนัลมูลค่า 100 บาท สำหรับใช้แทนเงินสด', 500, 20),
(3, 'ตุ๊กตามาสคอตสุดคิวท์', 'ตุ๊กตาลิมิเต็ดเอดิชั่น รุ่น Limited 2024', 1200, 10),
(4, 'ส่วนลดท้ายบิล 50 บาท', 'ใช้เป็นส่วนลดเมื่อซื้อครบ 300 บาทขึ้นไป', 150, 201);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `subject_name`) VALUES
(1, 'ภาษาไทย'),
(2, 'คณิตศาสตร์'),
(3, 'วิทยาศาสตร์'),
(4, 'สังคมศึกษา'),
(5, 'ภาษาอังกฤษ');

-- --------------------------------------------------------

--
-- Table structure for table `teaching_demand`
--

CREATE TABLE `teaching_demand` (
  `td_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teaching_level` int(11) NOT NULL,
  `available_day` date NOT NULL,
  `available_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teaching_demand`
--

INSERT INTO `teaching_demand` (`td_id`, `user_id`, `subject_id`, `teaching_level`, `available_day`, `available_time`) VALUES
(38, 1, 1, 1, '2026-05-05', 3),
(40, 1, 5, 1, '2026-05-05', 8),
(43, 1, 5, 1, '2026-05-06', 3),
(44, 2, 2, 2, '2026-05-06', 3),
(46, 2, 4, 2, '2026-05-06', 8),
(48, 2, 4, 2, '2026-05-06', 8),
(49, 1, 5, 1, '2026-05-06', 4),
(50, 2, 3, 2, '2026-05-06', 5),
(51, 2, 2, 2, '2026-05-06', 8),
(52, 2, 3, 2, '2026-05-06', 6),
(53, 2, 3, 2, '2026-05-06', 4);

-- --------------------------------------------------------

--
-- Table structure for table `teaching_log`
--

CREATE TABLE `teaching_log` (
  `teaching_log_id` int(11) NOT NULL,
  `td_id` int(11) NOT NULL,
  `ld_id` int(11) NOT NULL,
  `evidence` varchar(200) NOT NULL,
  `tutor_confirmed` int(11) NOT NULL,
  `teacher_confirmed` int(11) NOT NULL,
  `confirm_date` date NOT NULL,
  `checkup_note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teaching_log`
--

INSERT INTO `teaching_log` (`teaching_log_id`, `td_id`, `ld_id`, `evidence`, `tutor_confirmed`, `teacher_confirmed`, `confirm_date`, `checkup_note`) VALUES
(4, 40, 9, 'aaaaaaaaaa', 1, 0, '2026-05-06', 'ไม่ให้'),
(5, 0, 4, '0', 0, 0, '0000-00-00', '0'),
(6, 0, 4, '0', 0, 0, '0000-00-00', '0'),
(8, 44, 11, 'aaa', 1, 0, '0000-00-00', '0'),
(9, 44, 11, '0', 0, 0, '0000-00-00', '0'),
(10, 46, 12, 'aaa', 1, 0, '0000-00-00', '0'),
(11, 101, 201, 'https://storage.example.com/evid/v1_001', 1, 1, '2025-01-05', 'Verified'),
(12, 102, 202, 'https://storage.example.com/evid/v1_002', 1, 1, '2025-01-12', 'Checked'),
(13, 103, 203, 'https://storage.example.com/evid/v1_003', 1, 1, '2025-01-18', 'Approved'),
(14, 104, 204, 'https://storage.example.com/evid/v1_004', 1, 1, '2025-01-22', 'Done'),
(15, 105, 205, 'https://storage.example.com/evid/v1_005', 1, 1, '2025-01-25', 'Complete'),
(16, 106, 206, 'https://storage.example.com/evid/v1_006', 1, 1, '2025-01-28', 'Verified'),
(17, 107, 207, 'https://storage.example.com/evid/v1_007', 1, 1, '2025-01-29', 'Approved'),
(18, 108, 208, 'https://storage.example.com/evid/v1_008', 1, 1, '2025-01-30', 'Checked'),
(19, 109, 209, 'https://link.provider.net/s/abc009', 1, 1, '2025-02-04', 'Verified'),
(20, 110, 210, 'https://link.provider.net/s/abc010', 1, 1, '2025-02-08', 'Done'),
(21, 111, 211, 'https://link.provider.net/s/abc011', 1, 1, '2025-02-14', 'Approved'),
(22, 112, 212, 'https://link.provider.net/s/abc012', 1, 1, '2025-02-18', 'Checked'),
(23, 113, 213, 'https://link.provider.net/s/abc013', 1, 1, '2025-02-21', 'Verified'),
(24, 114, 214, 'https://link.provider.net/s/abc014', 1, 1, '2025-02-24', 'Done'),
(25, 115, 215, 'https://link.provider.net/s/abc015', 1, 1, '2025-02-26', 'Complete'),
(26, 116, 216, 'https://link.provider.net/s/abc016', 1, 1, '2025-02-28', 'Approved'),
(27, 117, 217, 'https://drive.demo/file/d/m017', 1, 1, '2025-03-03', 'Checked'),
(28, 118, 218, 'https://drive.demo/file/d/m018', 1, 1, '2025-03-07', 'Verified'),
(29, 119, 219, 'https://drive.demo/file/d/m019', 1, 1, '2025-03-10', 'Done'),
(30, 120, 220, 'https://drive.demo/file/d/m020', 1, 1, '2025-03-14', 'Approved'),
(31, 121, 221, 'https://drive.demo/file/d/m021', 1, 1, '2025-03-18', 'Checked'),
(32, 122, 222, 'https://drive.demo/file/d/m022', 1, 1, '2025-03-21', 'Complete'),
(33, 123, 223, 'https://drive.demo/file/d/m023', 1, 1, '2025-03-24', 'Verified'),
(34, 124, 224, 'https://drive.demo/file/d/m024', 1, 1, '2025-03-27', 'Done'),
(35, 125, 225, 'https://drive.demo/file/d/m025', 1, 1, '2025-03-29', 'Approved'),
(36, 126, 226, 'https://drive.demo/file/d/m026', 1, 1, '2025-03-31', 'Checked'),
(37, 127, 227, 'https://img.host.com/view/401', 1, 1, '2025-04-05', 'Verified'),
(38, 128, 228, 'https://img.host.com/view/402', 1, 1, '2025-04-10', 'Approved'),
(39, 129, 229, 'https://img.host.com/view/403', 1, 1, '2025-04-15', 'Done'),
(40, 130, 230, 'https://img.host.com/view/404', 1, 1, '2025-04-18', 'Checked'),
(41, 131, 231, 'https://img.host.com/view/405', 1, 1, '2025-04-22', 'Verified'),
(42, 132, 232, 'https://img.host.com/view/406', 1, 1, '2025-04-26', 'Complete'),
(43, 133, 233, 'https://img.host.com/view/407', 1, 1, '2025-04-29', 'Done'),
(44, 134, 234, 'https://cdn.site.com/u/501', 1, 1, '2025-05-02', 'Checked'),
(45, 135, 235, 'https://cdn.site.com/u/502', 1, 1, '2025-05-06', 'Verified'),
(46, 136, 236, 'https://cdn.site.com/u/503', 1, 1, '2025-05-10', 'Done'),
(47, 137, 237, 'https://cdn.site.com/u/504', 1, 1, '2025-05-14', 'Approved'),
(48, 138, 238, 'https://cdn.site.com/u/505', 1, 1, '2025-05-18', 'Checked'),
(49, 139, 239, 'https://cdn.site.com/u/506', 1, 1, '2025-05-22', 'Complete'),
(50, 140, 240, 'https://cdn.site.com/u/507', 1, 1, '2025-05-25', 'Verified'),
(51, 141, 241, 'https://cdn.site.com/u/508', 1, 1, '2025-05-28', 'Done'),
(52, 142, 242, 'https://cdn.site.com/u/509', 1, 1, '2025-05-30', 'Approved'),
(53, 143, 243, 'https://box.com/s/jun001', 1, 1, '2025-06-04', 'Checked'),
(54, 144, 244, 'https://box.com/s/jun002', 1, 1, '2025-06-08', 'Verified'),
(55, 145, 245, 'https://box.com/s/jun003', 1, 1, '2025-06-12', 'Done'),
(56, 146, 246, 'https://box.com/s/jun004', 1, 1, '2025-06-16', 'Approved'),
(57, 147, 247, 'https://box.com/s/jun005', 1, 1, '2025-06-20', 'Checked'),
(58, 148, 248, 'https://box.com/s/jun006', 1, 1, '2025-06-24', 'Complete'),
(59, 149, 249, 'https://box.com/s/jun007', 1, 1, '2025-06-27', 'Verified'),
(60, 150, 250, 'https://box.com/s/jun008', 1, 1, '2025-06-29', 'Done'),
(61, 151, 251, 'https://share.net/f/701', 1, 1, '2025-07-03', 'Approved'),
(62, 152, 252, 'https://share.net/f/702', 1, 1, '2025-07-07', 'Checked'),
(63, 153, 253, 'https://share.net/f/703', 1, 1, '2025-07-11', 'Verified'),
(64, 154, 254, 'https://share.net/f/704', 1, 1, '2025-07-15', 'Done'),
(65, 155, 255, 'https://share.net/f/705', 1, 1, '2025-07-19', 'Complete'),
(66, 156, 256, 'https://share.net/f/706', 1, 1, '2025-07-22', 'Checked'),
(67, 157, 257, 'https://share.net/f/707', 1, 1, '2025-07-25', 'Verified'),
(68, 158, 258, 'https://share.net/f/708', 1, 1, '2025-07-28', 'Approved'),
(69, 159, 259, 'https://share.net/f/709', 1, 1, '2025-07-30', 'Done'),
(70, 160, 260, 'https://docs.cloud/p/801', 1, 1, '2025-08-04', 'Checked'),
(71, 161, 261, 'https://docs.cloud/p/802', 1, 1, '2025-08-08', 'Verified'),
(72, 162, 262, 'https://docs.cloud/p/803', 1, 1, '2025-08-12', 'Done'),
(73, 163, 263, 'https://docs.cloud/p/804', 1, 1, '2025-08-16', 'Approved'),
(74, 164, 264, 'https://docs.cloud/p/805', 1, 1, '2025-08-20', 'Checked'),
(75, 165, 265, 'https://docs.cloud/p/806', 1, 1, '2025-08-24', 'Complete'),
(76, 166, 266, 'https://docs.cloud/p/807', 1, 1, '2025-08-27', 'Verified'),
(77, 167, 267, 'https://docs.cloud/p/808', 1, 1, '2025-08-30', 'Done'),
(78, 168, 268, 'https://file.io/sep01', 1, 1, '2025-09-05', 'Approved'),
(79, 169, 269, 'https://file.io/sep02', 1, 1, '2025-09-10', 'Checked'),
(80, 170, 270, 'https://file.io/sep03', 1, 1, '2025-09-15', 'Verified'),
(81, 171, 271, 'https://file.io/sep04', 1, 1, '2025-09-18', 'Done'),
(82, 172, 272, 'https://file.io/sep05', 1, 1, '2025-09-22', 'Complete'),
(83, 173, 273, 'https://file.io/sep06', 1, 1, '2025-09-25', 'Verified'),
(84, 174, 274, 'https://file.io/sep07', 1, 1, '2025-09-28', 'Approved'),
(85, 175, 275, 'https://file.io/sep08', 1, 1, '2025-09-30', 'Checked'),
(86, 176, 276, 'https://web.archive/oct01', 1, 1, '2025-10-04', 'Verified'),
(87, 177, 277, 'https://web.archive/oct02', 1, 1, '2025-10-08', 'Done'),
(95, 43, 18, 'ๅๅๅๅ', 1, 0, '0000-00-00', ''),
(96, 50, 19, '', 0, 0, '0000-00-00', ''),
(97, 52, 20, 'https://youtube.com/shorts/VTGRvcKzd-Q?si=kPNM9lmCOI7AyUl-', 1, 1, '2026-05-06', ''),
(98, 53, 21, 'https://youtube.com/shorts/VTGRvcKzd-Q?si=kPNM9lmCOI7AyUl-', 1, 0, '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `titles`
--

CREATE TABLE `titles` (
  `title_id` int(11) NOT NULL,
  `title_name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `titles`
--

INSERT INTO `titles` (`title_id`, `title_name`) VALUES
(1, 'เด็กชาย'),
(2, 'เด็กหญิง'),
(3, 'นาย'),
(4, 'นางสาว'),
(5, 'นาง');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `title_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `role` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `create_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `title_id`, `first_name`, `last_name`, `email`, `password`, `role`, `grade`, `points`, `create_at`) VALUES
(1, 1, 'นักเรียน', 'ซุกซน', 'student1@test.com', '$2y$10$gcsRDmie8N..3Vf0qXfrx.6ITyV0bHrLvbP/bcA/dNLjUBsmUcYdS', 1, 1, 950, '2026-01-01 21:35:49'),
(2, 2, 'นักเรียนสอง', 'ตั้งใจเรียน', 'student2@test.com', '$2y$10$nxBQhJCXjIkKYgYXDJFPDO4O67ZMtuCj20wybBh24QsK1G/HVrYZ6', 1, 2, 20, '2026-02-04 21:35:59'),
(3, 3, 'คุณครู', 'ใจดี', 'teacher@test.com', '$2y$10$wKlIWGG3xVTVhVa6B9SJa.w6ckK7ALKL992zkM2MazAlPWGDUJGHy', 2, 3, 6, '2026-03-25 21:36:09'),
(4, 4, 'แอดมิน', 'บริหาร', 'admin@test.com', '$2y$10$cDDIN5PG6ukyZt6Ni5woOeyehsKH/PNZDhjCxHHl5Gm/hWmxr4o1W', 3, 6, 20, '2026-03-04 21:36:17'),
(9, 3, 'เทส', 'มาก', 'test@gmail.com', '$2y$10$qh2cs/WzEz4AXUj1L4cUM.IdqAj15T9ZQ2PaXiaLXrwBlk.DTRagu', 2, 0, 100, '2026-01-04 21:36:23'),
(11, 1, 'a', 'c', '2test@gmail.com', '$2y$10$wE1yEnLTx3O3MIVg5oWdqOCe66k1HJR.8dhuR13XFOVfBeuXolhQS', 1, 0, 30, '2026-02-02 21:31:01'),
(12, 1, 's', 's', '3test@gmail.com', '$2y$10$7rT1RQ0hFMDsfj909CE8P.9J/MAibd2YMe.C9aNC.o65NzRdqJzbW', 1, 0, 20, '2026-03-04 21:29:38'),
(13, 3, '1', '2', '333test@gmail.com', '$2y$10$7f.knByqc.mIqmqUQfdwEOHZz4PrWVxosan02BftVOpotoqyloRI6', 2, 0, 0, '2026-04-02 21:29:21'),
(14, 3, 'QQ', 'QQQ', 'QQQ@test.com', '$2y$10$h9IIArvQYs19L0VFRmF/2.vxpI2WgEnHS7lHXnzkdhN2gkQ9Xuxpe', 1, 0, 10, '2026-05-04 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `learner_demand`
--
ALTER TABLE `learner_demand`
  ADD PRIMARY KEY (`ld_id`);

--
-- Indexes for table `redemptions`
--
ALTER TABLE `redemptions`
  ADD PRIMARY KEY (`redemption_id`);

--
-- Indexes for table `reward`
--
ALTER TABLE `reward`
  ADD PRIMARY KEY (`reward_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `teaching_demand`
--
ALTER TABLE `teaching_demand`
  ADD PRIMARY KEY (`td_id`);

--
-- Indexes for table `teaching_log`
--
ALTER TABLE `teaching_log`
  ADD PRIMARY KEY (`teaching_log_id`);

--
-- Indexes for table `titles`
--
ALTER TABLE `titles`
  ADD PRIMARY KEY (`title_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `learner_demand`
--
ALTER TABLE `learner_demand`
  MODIFY `ld_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `redemptions`
--
ALTER TABLE `redemptions`
  MODIFY `redemption_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reward`
--
ALTER TABLE `reward`
  MODIFY `reward_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `teaching_demand`
--
ALTER TABLE `teaching_demand`
  MODIFY `td_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `teaching_log`
--
ALTER TABLE `teaching_log`
  MODIFY `teaching_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `titles`
--
ALTER TABLE `titles`
  MODIFY `title_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
