-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2025 at 07:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flight_booking1`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(11) NOT NULL,
  `bio` varchar(255) NOT NULL,
  `logo_img` varchar(255) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`, `address`, `email`, `password`, `bio`, `logo_img`, `tel`, `account_number`) VALUES
(2, 'Turkish Airlines', '125 ST New York ,NY', 'turkish@gmail.com', '1234', 'Welcome to Turkish Airlines', 'download.jpg', '1228595410', '22542245'),
(3, 'Egypt Air', '98 st Madinet nasr', 'egy@gmail.com', '1234', 'Welcome to EgyptAir', 'images.png', '1228595410', '9000');

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

CREATE TABLE `flights` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `source` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `transit` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`transit`)),
  `passenger_limit` int(11) NOT NULL,
  `fees` int(11) DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `company_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`id`, `name`, `source`, `destination`, `transit`, `passenger_limit`, `fees`, `start_datetime`, `end_datetime`, `is_completed`, `company_id`) VALUES
(18, 'TA 1999-1999-9342', 'Cairo', 'Bali', '\"\\\"[\\\\\\\"Dubai\\\\\\\"]\\\"\"', 121, 100, '2024-12-29 20:24:00', '2025-01-05 16:24:00', 0, 2),
(19, 'EA 898-8998-6785', 'Rio De Janiero', 'Cairo', '\"\\\"[\\\\\\\"London\\\\\\\",\\\\\\\"Paris\\\\\\\",\\\\\\\"Rome\\\\\\\"]\\\"\"', 100, 200, '2024-12-28 18:18:00', '2024-12-30 18:24:00', 0, 3),
(20, 'EA 894-8928-6786', 'Paris', 'Hong Kong', '\"\\\"[\\\\\\\"Rome\\\\\\\",\\\\\\\"Dubai\\\\\\\"]\\\"\"', 98, 100, '2024-12-29 18:25:00', '2024-12-30 18:25:00', 0, 3),
(21, 'TA NY-Pa-3723894-23489342', 'New York', 'Paris', '\"\\\"[\\\\\\\"Chicago\\\\\\\",\\\\\\\"London\\\\\\\"]\\\"\"', 199, 200, '2025-01-12 19:20:00', '2025-01-25 19:20:00', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `content`, `date`) VALUES
(1, 26, 2, 'ahmed is the best\r\n', NULL),
(2, 26, 2, 'dwa', NULL),
(3, 26, 2, 'ahe,d is the \r\n', NULL),
(4, 26, 2, 'dwadw', NULL),
(5, 26, 2, 'wda', '2024-12-29'),
(6, 26, 2, 'wda', '2024-12-29'),
(7, 26, 2, 'dwa', '2024-12-29'),
(8, 2, 26, 'dwa', '2024-12-29'),
(9, 2, 26, 'dwa', '2024-12-29'),
(10, 2, 26, 'dwa\r\n', '2024-12-29'),
(12, 2, 26, 'okay baby', '2024-12-30'),
(13, 2, 26, 'okay baby', '2024-12-30'),
(14, 2, 26, 'okay baby', '2024-12-30'),
(15, 2, 26, 'grgr', '2024-12-30'),
(16, 2, 26, 'grgr', '2024-12-30'),
(17, 2, 26, 'hello', '2024-12-30'),
(18, 34, 2, 'ana fady', '2024-12-30'),
(19, 2, 34, 'ezyk ya king wa74ny', '2024-12-30'),
(20, 34, 2, 'ana fol ya 8aly ezay el 3yal', '2024-12-30'),
(21, 33, 2, 'Hazem beeh is sayin hello ', '2024-12-30'),
(22, 33, 2, 'Hazem beeh is sayin hello ', '2024-12-30'),
(23, 33, 2, 'Hazem beeh is sayin hello ', '2024-12-30'),
(24, 35, 3, 'Hello I am tamer ', '2024-12-30'),
(25, 3, 35, 'hello hapipi', '2024-12-30'),
(26, 37, 3, 'HI iam rana ', '2025-01-12'),
(27, 37, 3, 'Rana ', '2025-01-12'),
(28, 37, 3, 'Rana ', '2025-01-12'),
(29, 37, 2, 'gdfsgfds', '2025-01-12'),
(30, 37, 2, 'ghjmhg', '2025-01-12'),
(31, 37, 2, 'ghjmhg', '2025-01-12');

-- --------------------------------------------------------

--
-- Table structure for table `passenger`
--

CREATE TABLE `passenger` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `tel` int(11) NOT NULL,
  `account_number` int(191) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `passport_img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passenger`
--

INSERT INTO `passenger` (`id`, `name`, `email`, `password`, `tel`, `account_number`, `photo`, `passport_img`) VALUES
(22, 'Ahmed Adel', 'ahmedelshaer989@gmail.com', 'aaaaaa', 1145299481, 1, 'japan.jpeg', 'karneeh.jpg'),
(25, 'Flight Comp', 'airway@gmail.com', '123456', 1228595410, 2, 'Dv_lWneX4AEormv.jpg', ''),
(26, 'fdo', 'a@a.com', '1234', 1228595410, 2099, '1671624899758.jpg', ''),
(27, 'fadfaf', 'fadsfasdf@gmail.com', 'fdsfsadfd', 0, 0, '1671624905540.jpg', ''),
(28, 'ad', 'admin@gmail.com', 'adasdds', 0, 0, '1671625139929.jpg', ''),
(29, 'Ahmed', 'ahmed@gmail.com', '1234', 1228595410, 7, '1671624898025.jpg', '1671625315757.jpg'),
(30, 'ali', 'ali@gmail.com', '123454', 1228595410, 67468, '1671625315757.jpg', '1671625088805.jpg'),
(31, 'khaled', 'khaled@gmail.com', '1234', 1228595410, 377, '1671625011106.jpg', '1671624905540.jpg'),
(32, 'hoda', 'hoda@gmail.com', '1234', 1228595410, 500, '1671625315757.jpg', '1671625139929.jpg'),
(33, 'hazem', 'hazem@gmail.com', '1234', 1228595410, 2147483524, '1671625315757.jpg', '1671625088805.jpg'),
(34, 'Fady', 'fady123@gmail.com', '1234', 1228595410, 200, '1671625315757.jpg', '1671625139929.jpg'),
(35, 'Tamer', 'tamer@gmail.com', '1234', 1228595410, 1000, 'MV5BZjE3MGU1NjQtOWU2Ni00ZGVlLWJkNWItZDUxYTBmYzQ3YmZmXkEyXkFqcGc@._V1_.jpg', 'Egypt.webp'),
(36, 'dsfsdaf', 'dfsfafdaf@gmail.com', 'dsfafdsadf', 1228595410, 2147483647, '1671624947628.jpg', '1671624963252.jpg'),
(37, 'Rana', 'rana@gmail.com', '1234', 1228595411, 500, '1671625994847.jpg', '1671625011106.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `passengers_flights`
--

CREATE TABLE `passengers_flights` (
  `id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `status` enum('registered','pending','','') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passengers_flights`
--

INSERT INTO `passengers_flights` (`id`, `flight_id`, `passenger_id`, `status`) VALUES
(14, 18, 34, 'registered'),
(15, 18, 32, 'pending'),
(18, 18, 33, 'pending'),
(19, 20, 35, 'pending'),
(21, 20, 34, 'registered'),
(23, 20, 37, 'registered'),
(24, 21, 37, 'registered');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flights`
--
ALTER TABLE `flights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyconst` (`company_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passenger`
--
ALTER TABLE `passenger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passengers_flights`
--
ALTER TABLE `passengers_flights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `flightconst` (`flight_id`),
  ADD KEY `passengerconst` (`passenger_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `flights`
--
ALTER TABLE `flights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `passenger`
--
ALTER TABLE `passenger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `passengers_flights`
--
ALTER TABLE `passengers_flights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `flights`
--
ALTER TABLE `flights`
  ADD CONSTRAINT `companyconst` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `passengers_flights`
--
ALTER TABLE `passengers_flights`
  ADD CONSTRAINT `flightconst` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `passengerconst` FOREIGN KEY (`passenger_id`) REFERENCES `passenger` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
