-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2024 at 05:50 PM
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
-- Database: `hostelmsphp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hostel_name` varchar(255) DEFAULT NULL,
  `password` varchar(300) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `hostel_name`, `password`, `reg_date`, `updation_date`) VALUES
(2, 'MirSuhaib', 'meersuhaib191@gmail.com', 'Boys Hostel North Campus , University of Kashmir .', '$2y$10$U3RHw1JRT5tqjj2i2kZ6O.IP6ZKb2a7ycwUKDF.0Qr5ESdhTmYQvK', '2024-11-10 15:36:55', '2024-11-13');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_details`
--

CREATE TABLE `attendance_details` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `enrollment_no` varchar(255) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `day1` char(1) DEFAULT '-',
  `day2` char(1) DEFAULT '-',
  `day3` char(1) DEFAULT '-',
  `day4` char(1) DEFAULT '-',
  `day5` char(1) DEFAULT '-',
  `day6` char(1) DEFAULT '-',
  `day7` char(1) DEFAULT '-',
  `day8` char(1) DEFAULT '-',
  `day9` char(1) DEFAULT '-',
  `day10` char(1) DEFAULT '-',
  `day11` char(1) DEFAULT '-',
  `day12` char(1) DEFAULT '-',
  `day13` char(1) DEFAULT '-',
  `day14` char(1) DEFAULT '-',
  `day15` char(1) DEFAULT '-',
  `day16` char(1) DEFAULT '-',
  `day17` char(1),
  `day18` char(1) DEFAULT '-',
  `day19` char(1) DEFAULT '-',
  `day20` char(1) DEFAULT '-',
  `day21` char(1) DEFAULT '-',
  `day22` char(1) DEFAULT '-',
  `day23` char(1) DEFAULT '-',
  `day24` char(1) DEFAULT '-',
  `day25` char(1) DEFAULT '-',
  `day26` char(1) DEFAULT '-',
  `day27` char(1) DEFAULT '-',
  `day28` char(1) DEFAULT '-',
  `day29` char(1) DEFAULT '-',
  `day30` char(1) DEFAULT '-',
  `day31` char(1) DEFAULT '-',
  `month` int(2) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `days_present` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_details`
--

INSERT INTO `attendance_details` (`id`, `student_name`, `enrollment_no`, `course`, `batch`, `day1`, `day2`, `day3`, `day4`, `day5`, `day6`, `day7`, `day8`, `day9`, `day10`, `day11`, `day12`, `day13`, `day14`, `day15`, `day16`, `day17`, `day18`, `day19`, `day20`, `day21`, `day22`, `day23`, `day24`, `day25`, `day26`, `day27`, `day28`, `day29`, `day30`, `day31`, `month`, `year`, `days_present`) VALUES
(1, 'Hashim   Khursheed', '22048112001', 'Btech', '2022', 'P', 'P', '', 'P', '', '', '', '', '', '', '', 'P', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 11, 2024, 4),
(2, 'Umar  Rafiq', '22048112002', 'Btech', '2022', 'P', 'P', '', '', 'P', '', '', 'P', '', '', '', '', 'P', 'P', '', 'P', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 11, 2024, 7),
(3, 'Rasikh  Parray', '22048112007', 'Btech', '2022', 'P', 'P', 'P', 'P', 'P', 'P', 'P', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 11, 2024, 7),
(4, 'Mir  Suhaib', '22048112049', 'Btech', '2022', 'P', '', '', 'P', '', '', '', '', '', '', '', '', '', '', '', 'P', 'P', 'P', '', '', '', '', '', '', '', '', '', '', '', '', '', 11, 2024, 5),
(5, 'Mir  Musaib', '22048112050', 'Btech', '2022', 'P', '', 'P', '', '', '', '', '', '', '', 'P', '', '', '', '', 'P', 'P', '', '', '', '', '', '', 'P', '', '', '', '', '', '', '', 11, 2024, 6),
(6, 'Muzamil  Manzoor', '22048112059', 'Btech', '2022', 'P', '', '', '', '', '', '', '', '', '', '', '', '', 'P', '', 'P', '', '', '', '', 'P', '', '', '', '', '', '', '', '', '', '', 11, 2024, 4),
(7, 'Nasir  Ud Din', '21048112049', 'Btech', '2021', 'P', '', '', '', '', '', '', '', '', '', '', 'P', '', '', '', '', '', '', '', 'P', '', '', '', '', '', '', '', '', '', '', '', 11, 2024, 3),
(14, 'Uzair  Javaid', '22048112039', 'Btech', '2022', '', '', '', '', '', 'P', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'P', '', '', 11, 2024, 2),
(17, 'sheikh furkan mushtaq', '22048112032', 'Btech', '2022', '', 'P', '', 'P', '', '', '', '', '', '', '', '', '', '', '', '', '', 'P', '', '', 'P', '', '', '', '', '', '', '', '', 'P', '', 11, 2024, 5),
(18, 'Arshid  Ah', '22048112004', 'Btech', '2022', '', 'P', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 11, 2024, 1);

--
-- Triggers `attendance_details`
--
DELIMITER $$
CREATE TRIGGER `update_days_present_on_attendance_change` BEFORE UPDATE ON `attendance_details` FOR EACH ROW BEGIN
    SET NEW.days_present = 
        (NEW.day1 = 'P') + (NEW.day2 = 'P') + (NEW.day3 = 'P') + 
        (NEW.day4 = 'P') + (NEW.day5 = 'P') + (NEW.day6 = 'P') + 
        (NEW.day7 = 'P') + (NEW.day8 = 'P') + (NEW.day9 = 'P') + 
        (NEW.day10 = 'P') + (NEW.day11 = 'P') + (NEW.day12 = 'P') + 
        (NEW.day13 = 'P') + (NEW.day14 = 'P') + (NEW.day15 = 'P') + 
        (NEW.day16 = 'P') + (NEW.day17 = 'P') + (NEW.day18 = 'P') + 
        (NEW.day19 = 'P') + (NEW.day20 = 'P') + (NEW.day21 = 'P') + 
        (NEW.day22 = 'P') + (NEW.day23 = 'P') + (NEW.day24 = 'P') + 
        (NEW.day25 = 'P') + (NEW.day26 = 'P') + (NEW.day27 = 'P') + 
        (NEW.day28 = 'P') + (NEW.day29 = 'P') + (NEW.day30 = 'P') + 
        (NEW.day31 = 'P');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `expenditure`
--

CREATE TABLE `expenditure` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `dealer` varchar(255) DEFAULT NULL,
  `date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenditure`
--

INSERT INTO `expenditure` (`id`, `amount`, `description`, `dealer`, `date`) VALUES
(1, 1500.00, 'Electricity Bill', NULL, '2024-10-01'),
(2, 800.00, 'Water Bill', NULL, '2024-10-05'),
(3, 1200.00, 'Food Supplies', NULL, '2024-10-10'),
(4, 500.00, 'Cleaning Services', NULL, '2024-10-15'),
(5, 10000.00, 'Maintenance', NULL, '2024-10-20'),
(8, 1500.00, 'Tomato + Chicken', 'Farhan', '2024-11-15'),
(9, 2000.00, 'Tomato + Chicken', 'Farhan', '2024-10-31');

-- --------------------------------------------------------

--
-- Table structure for table `hostels`
--

CREATE TABLE `hostels` (
  `id` int(11) NOT NULL,
  `hostel_name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hostels`
--

INSERT INTO `hostels` (`id`, `hostel_name`, `address`, `capacity`) VALUES
(1, 'Boys Hostel, University of Kashmir, North Campus Baramulla', 'Delina Baramulla', 100);

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `roomno` int(11) NOT NULL,
  `seater` int(11) NOT NULL,
  `feespm` int(11) NOT NULL,
  `foodstatus` int(11) NOT NULL,
  `stayfrom` date NOT NULL,
  `duration` int(11) NOT NULL,
  `course` varchar(500) NOT NULL,
  `regno` varchar(255) NOT NULL,
  `firstName` varchar(500) NOT NULL,
  `middleName` varchar(500) NOT NULL,
  `lastName` varchar(500) NOT NULL,
  `gender` varchar(250) NOT NULL,
  `contactno` bigint(11) NOT NULL,
  `emailid` varchar(500) NOT NULL,
  `egycontactno` bigint(11) NOT NULL,
  `guardianName` varchar(500) NOT NULL,
  `guardianRelation` varchar(500) NOT NULL,
  `guardianContactno` bigint(11) NOT NULL,
  `corresAddress` varchar(500) NOT NULL,
  `corresCIty` varchar(500) NOT NULL,
  `corresState` varchar(500) NOT NULL,
  `corresPincode` int(11) NOT NULL,
  `pmntAddress` varchar(500) NOT NULL,
  `pmntCity` varchar(500) NOT NULL,
  `pmnatetState` varchar(500) NOT NULL,
  `pmntPincode` int(11) NOT NULL,
  `postingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updationDate` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_no` varchar(50) NOT NULL,
  `no_of_students` int(11) DEFAULT 0,
  `student_names` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`student_names`)),
  `student_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`student_details`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_no`, `no_of_students`, `student_names`, `student_details`, `created_at`, `updated_at`) VALUES
(1, '210', 4, NULL, '[{\"enroll\":\"22048112049\",\"name\":\"Mir Suhaib\"},{\"enroll\":\"22048112050\",\"name\":\"Mir Musaib\"},{\"enroll\":\"22048112032\",\"name\":\"Furkan Mushtaq\"},{\"enroll\":\"22048112002\",\"name\":\"Umar Rafiq\"}]', '2024-11-17 13:32:37', '2024-11-17 14:07:43'),
(2, '211', 1, NULL, '{\"student1\":{\"name\":\"Rasikh Parray\",\"enroll\":\"22048112007\"}}', '2024-11-17 14:08:07', '2024-11-17 14:08:07');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `staff_name` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `staff_name`, `role`, `contact`, `email`, `address`, `hire_date`, `status`, `created_at`, `updated_at`, `password`) VALUES
(6, 'Javaid', 'Cook', '63486468736', 'javaid123@gmail.com', 'Budgam', NULL, 'active', '2024-11-18 14:57:32', '2024-11-18 15:03:08', '$2y$10$tayAtVEZsZGR3d3DaBR6uem6bEao5xb43MZP1sur4AXnWlzh0mCga');

-- --------------------------------------------------------

--
-- Table structure for table `userregistration`
--

CREATE TABLE `userregistration` (
  `id` int(11) NOT NULL,
  `enrollment_no` varchar(255) DEFAULT NULL,
  `firstName` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `middleName` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `lastName` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `course` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `batch` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gender` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `contactNo` bigint(20) NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `regDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updationDate` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `passUdateDate` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `role` enum('student','warden','proctor') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'student',
  `full_name` varchar(255) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userregistration`
--

INSERT INTO `userregistration` (`id`, `enrollment_no`, `firstName`, `middleName`, `lastName`, `course`, `batch`, `gender`, `contactNo`, `email`, `password`, `regDate`, `updationDate`, `passUdateDate`, `role`, `full_name`, `status`, `address`) VALUES
(1, '22048112001', 'Hashim', '', 'Khursheed', 'Computer Science', '2024', 'Male', 1234567890, 'abc@gmail.com', '$2y$10$AYLd87V2D.dricYSr7VkcuAoQueN66IOpu/zZ1tPai47W8PEDB7ie', '2024-11-12 19:19:00', '', '', 'student', 'Hashim  Khursheed', '', 'Pulwama'),
(2, '22048112002', 'Umar', '', 'Rafiq', 'Btech', '2022', 'Male', 6483728374, 'meersuhaib119@gmail.com', '$2y$10$kBOKKmXyISrLD909YPTOVuwov/y47eAEMy3TySnIyhJVWnMfuQCfm', '2024-11-11 15:14:04', '', '', 'student', 'Umar  Rafiq', '', 'Pulwama'),
(3, '22048112007', 'Rasikh', '', 'Parray', 'Btech', '2022', 'Male', 6468767784, 'meermusaib20@gmail.com', '$2y$10$6gfzuHVCPL3x/LieqUyfw.XL0BZ0vgXLHsO3Cdy5D4yhWkPMokZ0a', '2024-11-11 15:14:57', '', '', 'student', 'Rasikh  Parray', '', 'Pulwama'),
(4, '22048112049', 'Mir', '', 'Suhaib', 'Btech', '2022', 'Male', 6006975431, 'a@gmail.com', '$2y$10$m/gVN8zFA90xSxIO2V3g0.VUWb0PqNW.AIjJoqz6PrAIe9yv6qDNm', '2024-11-11 15:15:50', '14-11-2024 10:54:04', '14-11-2024 10:52:54', 'student', 'Mir  Suhaib', '', 'Pulwama'),
(5, '22048112050', 'Mir', '', 'Musaib', 'Btech', '2022', 'Male', 6006338584, 'shykhfurkan@gmail.com', '$2y$10$qhWnjsyGgTiTMlzX0xkZV.S5w3u.Nfjuos3y1xMTAs5KlWYv/Kl3u', '2024-11-11 15:16:26', '', '', 'student', 'Mir  Musaib', '', 'Pulwama'),
(6, '22048112059', 'Muzamil', '', 'Manzoor', 'Btech', '2022', 'Male', 358273424, 'meersuhaib191@gmail.com', '$2y$10$AYLd87V2D.dricYSr7VkcuAoQueN66IOpu/zZ1tPai47W8PEDB7ie', '2024-11-11 19:08:55', '', '', 'student', 'Muzamil  Manzoor', '', 'Pulwama'),
(7, '21048112049', 'Nasir ', 'Ud', 'Din', 'Btech', '2021', 'Male', 34673657465, 'nasir@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '2024-11-12 18:10:00', '', '', 'student', 'Nasir  Ud Din', '', 'Pulwama'),
(14, '22048112039', 'Uzair', '', 'Javaid', 'Btech', '2022', 'Male', 6736786234, 'm33Rsuhaib191@gmail.com', '00a4a035b6f38e3e6c96aabde2b7af84', '2024-11-14 14:37:05', '', '', 'student', 'Uzair  Javaid', '', 'Pulwama'),
(17, '22048112032', 'sheikh', 'furkan', 'mushtaq', 'Btech', '2022', 'Male', 7780940217, 'sheikhfurkan0098@gmail.com', '$2y$10$AYLd87V2D.dricYSr7VkcuAoQueN66IOpu/zZ1tPai47W8PEDB7ie', '2024-11-14 17:32:29', '', '', 'student', 'sheikh furkan mushtaq', '', 'Pulwama'),
(18, '22048112004', 'Arshid', '', 'Ah', 'Btech', '2022', 'Male', 63486468736, 'abcdes@gmail.com', '$2y$10$f0Yl5tQcb0EXNglVS79VCuE2noUKzorZukotI3C4DOfYbFOASzleW', '2024-11-16 17:55:25', '', '', 'student', 'Arshid  Ah', '', 'Budgam');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_details`
--
ALTER TABLE `attendance_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenditure`
--
ALTER TABLE `expenditure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hostels`
--
ALTER TABLE `hostels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userregistration`
--
ALTER TABLE `userregistration`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance_details`
--
ALTER TABLE `attendance_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `expenditure`
--
ALTER TABLE `expenditure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hostels`
--
ALTER TABLE `hostels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `userregistration`
--
ALTER TABLE `userregistration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
