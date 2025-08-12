-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2025 at 04:52 PM
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
-- Database: `smartcitydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `citizens`
--

CREATE TABLE `citizens` (
  `CitizenID` int(11) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Role` enum('Admin','User') DEFAULT 'User',
  `ProfilePicture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `citizens`
--

INSERT INTO `citizens` (`CitizenID`, `FullName`, `Email`, `Password`, `Address`, `Phone`, `Role`, `ProfilePicture`) VALUES
(6, 'md. sabbir hossain tamim', 'tamimhossain225e@gmail.com', '$2y$10$SHOEMKnybOCgEjqbf1RwOOeZZB9DksAbRo7xlbSvugelEVWv2SNBO', 'Noapara', '01794264988', 'Admin', 'uploads/user_6.jpg'),
(7, 'Sohag', 'sohag01945@gmail.com', '$2y$10$KTq/meli50/0p2qM556bHO4tJN2T/Bc6Q9Xy.Hem3Qiv5s1BG9zqS', 'dhaka', '01993020549', 'User', 'uploads/user_7.jpg'),
(8, 'sabbir', 'sabbir.tamim3@northsouth.edu', '$2y$10$lFFi8MA9JATsAUehQRcJ3u8F920iE4X0EGxUdxaCb84dPzu/oXNWe', 'Demra, Dhaka', '01993020549', 'Admin', 'uploads/user_8.jpg'),
(9, 'anirban', 'anirbanshah@gmail.com', NULL, 'dhaka', '0177777777', 'User', NULL),
(10, 'Md. Sabbir Hossain Tamim', 'esha225t@gmail.com', '$2y$10$dIlKPyluhbXsmbgm837.muQ02KSdajvxW05fvzHlS4n7R.lhtZJLG', 'Plot: 15, Block: B, Basundhara Dhaka', '01794264988', 'User', NULL),
(12, 'Md. Sabbir Hossain Tamim', 'esha445t@gmail.com', '$2y$10$12GiA9icRNwPTBut/2zUOeVxN9hWGR34MwQ8Dt6Sn.zWl3B01LBIK', 'Plot: 15, Block: B, Basundhara Dhaka', '01794264988', 'User', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `ComplaintID` int(11) NOT NULL,
  `CitizenID` int(11) DEFAULT NULL,
  `Subject` varchar(100) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Status` enum('Pending','In Progress','Resolved') DEFAULT 'Pending',
  `DateFiled` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`ComplaintID`, `CitizenID`, `Subject`, `Description`, `Status`, `DateFiled`) VALUES
(2, 7, 'water leakage', 'there is a water leakage in my house\r\n', 'Pending', '2025-04-15'),
(3, 9, 'water leakage', 'hjkSJ', 'Resolved', '2025-04-15');

-- --------------------------------------------------------

--
-- Table structure for table `educational_institutes`
--

CREATE TABLE `educational_institutes` (
  `InstituteID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `Status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `educational_institutes`
--

INSERT INTO `educational_institutes` (`InstituteID`, `Name`, `Address`, `Type`, `Status`) VALUES
(1, 'North South University', 'Basundhora R/A ', 'University', 'Active'),
(2, 'Independent University', 'Basundhora R/A	', 'University', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `emergencyrequests`
--

CREATE TABLE `emergencyrequests` (
  `RequestID` int(11) NOT NULL,
  `CitizenID` int(11) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `DateRequested` date DEFAULT NULL,
  `Status` enum('Pending','Responded') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergencyrequests`
--

INSERT INTO `emergencyrequests` (`RequestID`, `CitizenID`, `Type`, `Description`, `DateRequested`, `Status`) VALUES
(3, 7, 'Medical', 'my father got heart attack. can someone please help me?\r\n', '2025-04-15', 'Responded'),
(4, 9, 'Medical', 'jsbsjakhcs', '2025-04-15', 'Responded');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `EventID` int(11) NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Location` varchar(100) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Status` enum('Upcoming','Completed') DEFAULT 'Upcoming'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`EventID`, `Title`, `Description`, `Location`, `Date`, `Status`) VALUES
(2, 'Pohela Boishak', 'There will be a Pohela boishak celebration ', 'Shabag, dhaka', '2025-04-15', 'Upcoming');

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `CitizenID` int(11) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `created_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `CitizenID`, `otp`, `created_at`, `expires_at`) VALUES
(86, 8, '681205', '2025-08-02 20:09:02', '2025-08-02 20:14:02');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `ServiceID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `traffic_violations`
--

CREATE TABLE `traffic_violations` (
  `ViolationID` int(11) NOT NULL,
  `CitizenID` int(11) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `DateOccurred` date DEFAULT NULL,
  `FineAmount` decimal(10,2) DEFAULT NULL,
  `Status` enum('Pending','Paid') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `traffic_violations`
--

INSERT INTO `traffic_violations` (`ViolationID`, `CitizenID`, `Description`, `DateOccurred`, `FineAmount`, `Status`) VALUES
(1, 7, 'Over Speeding', '2025-04-14', 2000.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `transport`
--

CREATE TABLE `transport` (
  `TransportID` int(11) NOT NULL,
  `RouteName` varchar(100) DEFAULT NULL,
  `VehicleType` varchar(50) DEFAULT NULL,
  `Timing` varchar(50) DEFAULT NULL,
  `Status` enum('Running','Delayed','Cancelled') DEFAULT 'Running'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transport`
--

INSERT INTO `transport` (`TransportID`, `RouteName`, `VehicleType`, `Timing`, `Status`) VALUES
(1, 'Demra-Badda-Jamuna Future park', 'bus', '9.00 am 15-4-2025', 'Running'),
(2, 'Badda-Kuril-Uttara', 'bus', '10.00 am 15-4-2025', 'Running');

-- --------------------------------------------------------

--
-- Table structure for table `utilitybills`
--

CREATE TABLE `utilitybills` (
  `BillID` int(11) NOT NULL,
  `CitizenID` int(11) DEFAULT NULL,
  `BillType` varchar(50) DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `Status` enum('Unpaid','Paid') DEFAULT 'Unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilitybills`
--

INSERT INTO `utilitybills` (`BillID`, `CitizenID`, `BillType`, `Amount`, `DueDate`, `Status`) VALUES
(1, 9, 'gas bill', 1000.00, '2025-04-17', 'Unpaid');

-- --------------------------------------------------------

--
-- Table structure for table `wastemanagement`
--

CREATE TABLE `wastemanagement` (
  `WasteID` int(11) NOT NULL,
  `Area` varchar(100) DEFAULT NULL,
  `Schedule` varchar(100) DEFAULT NULL,
  `Status` enum('Scheduled','Completed') DEFAULT 'Scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wastemanagement`
--

INSERT INTO `wastemanagement` (`WasteID`, `Area`, `Schedule`, `Status`) VALUES
(2, 'basundhara r/a d block', '12 april 11 am', 'Scheduled');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `citizens`
--
ALTER TABLE `citizens`
  ADD PRIMARY KEY (`CitizenID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`ComplaintID`),
  ADD KEY `CitizenID` (`CitizenID`);

--
-- Indexes for table `educational_institutes`
--
ALTER TABLE `educational_institutes`
  ADD PRIMARY KEY (`InstituteID`);

--
-- Indexes for table `emergencyrequests`
--
ALTER TABLE `emergencyrequests`
  ADD PRIMARY KEY (`RequestID`),
  ADD KEY `CitizenID` (`CitizenID`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`EventID`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CitizenID` (`CitizenID`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`ServiceID`);

--
-- Indexes for table `traffic_violations`
--
ALTER TABLE `traffic_violations`
  ADD PRIMARY KEY (`ViolationID`),
  ADD KEY `CitizenID` (`CitizenID`);

--
-- Indexes for table `transport`
--
ALTER TABLE `transport`
  ADD PRIMARY KEY (`TransportID`);

--
-- Indexes for table `utilitybills`
--
ALTER TABLE `utilitybills`
  ADD PRIMARY KEY (`BillID`),
  ADD KEY `CitizenID` (`CitizenID`);

--
-- Indexes for table `wastemanagement`
--
ALTER TABLE `wastemanagement`
  ADD PRIMARY KEY (`WasteID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `citizens`
--
ALTER TABLE `citizens`
  MODIFY `CitizenID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `ComplaintID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `educational_institutes`
--
ALTER TABLE `educational_institutes`
  MODIFY `InstituteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `emergencyrequests`
--
ALTER TABLE `emergencyrequests`
  MODIFY `RequestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `ServiceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `traffic_violations`
--
ALTER TABLE `traffic_violations`
  MODIFY `ViolationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transport`
--
ALTER TABLE `transport`
  MODIFY `TransportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `utilitybills`
--
ALTER TABLE `utilitybills`
  MODIFY `BillID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wastemanagement`
--
ALTER TABLE `wastemanagement`
  MODIFY `WasteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`CitizenID`) REFERENCES `citizens` (`CitizenID`);

--
-- Constraints for table `emergencyrequests`
--
ALTER TABLE `emergencyrequests`
  ADD CONSTRAINT `emergencyrequests_ibfk_1` FOREIGN KEY (`CitizenID`) REFERENCES `citizens` (`CitizenID`);

--
-- Constraints for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD CONSTRAINT `otp_codes_ibfk_1` FOREIGN KEY (`CitizenID`) REFERENCES `citizens` (`CitizenID`);

--
-- Constraints for table `traffic_violations`
--
ALTER TABLE `traffic_violations`
  ADD CONSTRAINT `traffic_violations_ibfk_1` FOREIGN KEY (`CitizenID`) REFERENCES `citizens` (`CitizenID`);

--
-- Constraints for table `utilitybills`
--
ALTER TABLE `utilitybills`
  ADD CONSTRAINT `utilitybills_ibfk_1` FOREIGN KEY (`CitizenID`) REFERENCES `citizens` (`CitizenID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
