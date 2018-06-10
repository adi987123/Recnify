-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2018 at 09:25 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `image_recognition`
--

-- --------------------------------------------------------

--
-- Table structure for table `batch`
--

CREATE TABLE `batch` (
  `batch_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `batch_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `is_deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `batch`
--

INSERT INTO `batch` (`batch_id`, `subject_id`, `batch_name`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`) VALUES
(1, 1, 'Summer', '2018-06-07 00:00:00', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `examination`
--

CREATE TABLE `examination` (
  `examination_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_option_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `examination_session`
--

CREATE TABLE `examination_session` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `session_status` int(1) NOT NULL,
  `session_timer` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `is_deleted` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `examination_session`
--

INSERT INTO `examination_session` (`session_id`, `user_id`, `batch_id`, `session_status`, `session_timer`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`) VALUES
(1, 1, 1, 0, '00:44:25', '2018-06-03 00:00:00', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `option_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `is_correct` int(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL,
  `is_deleted` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `question_id`, `option_text`, `is_correct`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`) VALUES
(1, 1, 'default', 0, '2018-05-31 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(2, 1, 'null', 0, '2018-05-31 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(3, 1, 'String', 1, '2018-05-31 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(4, 1, 'volatile', 0, '2018-05-31 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(5, 2, 'public void main( String args [] )', 0, '2018-05-31 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(6, 2, 'public void static main( String args [] )', 0, '2018-05-31 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(7, 2, 'final public static void main(String[] arr)', 0, '2018-05-31 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(8, 2, 'public static void main( String[] arr)', 1, '2018-05-31 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(9, 4, 'Yes', 1, '2018-06-01 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(10, 4, 'No', 0, '2018-06-01 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(11, 3, 'Yes', 1, '2018-06-01 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(12, 3, 'No', 0, '2018-06-01 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(13, 5, 'char c1 = 064770;', 0, '2018-06-01 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(14, 5, 'char c2 = \'face\';', 1, '2018-06-01 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(15, 5, 'char c3 = 0xbeef;', 0, '2018-06-01 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(16, 5, 'char c4 = \'\\uface\';', 0, '2018-06-01 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `is_deleted` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `subject_id`, `question_text`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`) VALUES
(1, 1, 'Which of these is NOT a valid keyword or reserved word in Java ?', '2018-05-31 00:00:00', NULL, NULL, 0),
(2, 1, 'Which declaration of the main() method is valid ?', '2018-05-31 00:00:00', NULL, NULL, 0),
(3, 1, 'Is it possible in Java to create arrays of length zero ?', '2018-06-01 00:00:00', NULL, NULL, 0),
(4, 1, 'Is 3 * 4 equivalent to 3 << 2 ?', '2018-06-01 00:00:00', NULL, NULL, 0),
(5, 1, 'Which one of the following is invalid declaration of a char ?', '2018-06-01 00:00:00', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `is_deleted` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`) VALUES
(1, 'Java', '2018-06-03 00:00:00', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `is_deleted` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batch`
--
ALTER TABLE `batch`
  ADD PRIMARY KEY (`batch_id`);

--
-- Indexes for table `examination`
--
ALTER TABLE `examination`
  ADD PRIMARY KEY (`examination_id`);

--
-- Indexes for table `examination_session`
--
ALTER TABLE `examination_session`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`,`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batch`
--
ALTER TABLE `batch`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `examination`
--
ALTER TABLE `examination`
  MODIFY `examination_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `examination_session`
--
ALTER TABLE `examination_session`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
