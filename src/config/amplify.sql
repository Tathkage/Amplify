-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2023 at 05:19 PM
-- Server version: 8.0.32
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amplify`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int NOT NULL,
  `admin_password` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `album_id` int NOT NULL,
  `album_title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `release_date` date NOT NULL,
  `release_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`album_id`, `album_title`, `release_date`, `release_time`) VALUES
(38, 'Jesus Is King', '2019-10-25', '09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `album_artists`
--

CREATE TABLE `album_artists` (
  `album_artists_id` int NOT NULL,
  `album_id` int NOT NULL,
  `artist_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `album_artists`
--

INSERT INTO `album_artists` (`album_artists_id`, `album_id`, `artist_id`) VALUES
(38, 38, 1),
(39, 38, 4);

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `artist_id` int NOT NULL,
  `user_id` int NOT NULL,
  `stage_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`artist_id`, `user_id`, `stage_name`) VALUES
(1, 2, 'WAY'),
(4, 4, 'Ye');

-- --------------------------------------------------------

--
-- Table structure for table `playlists`
--

CREATE TABLE `playlists` (
  `playlist_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `playlist_title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int NOT NULL,
  `user_id` int NOT NULL,
  `song_id` int DEFAULT NULL,
  `album_id` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `rating` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `song_id` int NOT NULL,
  `song_title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `length` time NOT NULL,
  `listens` int NOT NULL,
  `album_id` int DEFAULT NULL,
  `release_date` date NOT NULL,
  `release_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`song_id`, `song_title`, `length`, `listens`, `album_id`, `release_date`, `release_time`) VALUES
(79, 'Selah', '00:00:00', 0, 38, '2019-10-25', '09:00:00'),
(80, 'Follow God', '00:00:00', 0, 38, '2019-10-25', '09:00:00'),
(81, 'Closed on Sunday', '00:00:00', 0, 38, '2019-10-25', '09:00:00'),
(82, 'Use This Gospel', '00:00:00', 0, 38, '2019-10-25', '09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `song_artists`
--

CREATE TABLE `song_artists` (
  `song_artist_id` int NOT NULL,
  `song_id` int NOT NULL,
  `artist_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `song_artists`
--

INSERT INTO `song_artists` (`song_artist_id`, `song_id`, `artist_id`) VALUES
(72, 79, 1),
(73, 79, 4),
(74, 80, 1),
(75, 80, 4),
(76, 81, 1),
(77, 81, 4),
(78, 82, 1),
(79, 82, 4);

-- --------------------------------------------------------

--
-- Table structure for table `song_playlists`
--

CREATE TABLE `song_playlists` (
  `song_playlist_id` int NOT NULL,
  `playlist_id` int NOT NULL,
  `song_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(320) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `email`) VALUES
(1, 'deez', 'ben_dover', 'Rohan', 'Dhawan', 'rohan@gmail.com'),
(2, 'nutz', 'yes', 'Wayland', 'Moody', 'wayland@gmail.com'),
(3, 'got_eem', 'nope', 'Tathluach', 'Chol', 'tathluach@gmail.com'),
(4, 'ye', 'kim', 'Kanye', 'West', 'kanyewest@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD KEY `Admin to User` (`user_id`);

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`album_id`);

--
-- Indexes for table `album_artists`
--
ALTER TABLE `album_artists`
  ADD PRIMARY KEY (`album_artists_id`),
  ADD KEY `AA to Album` (`album_id`),
  ADD KEY `AA to Artist` (`artist_id`);

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`artist_id`),
  ADD KEY `Artist to User` (`user_id`);

--
-- Indexes for table `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`playlist_id`),
  ADD KEY `Playlist User` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD KEY `Review to User` (`user_id`),
  ADD KEY `Review to Song` (`song_id`),
  ADD KEY `Review to Album` (`album_id`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`song_id`),
  ADD KEY `Song to Album` (`album_id`);

--
-- Indexes for table `song_artists`
--
ALTER TABLE `song_artists`
  ADD PRIMARY KEY (`song_artist_id`),
  ADD KEY `SA to Song` (`song_id`),
  ADD KEY `SA to Artist` (`artist_id`);

--
-- Indexes for table `song_playlists`
--
ALTER TABLE `song_playlists`
  ADD PRIMARY KEY (`song_playlist_id`),
  ADD KEY `SP to Playlist` (`playlist_id`),
  ADD KEY `SP to Song` (`song_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `password` (`password`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `album_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `album_artists`
--
ALTER TABLE `album_artists`
  MODIFY `album_artists_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `artist_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `playlists`
--
ALTER TABLE `playlists`
  MODIFY `playlist_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `song_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `song_artists`
--
ALTER TABLE `song_artists`
  MODIFY `song_artist_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `song_playlists`
--
ALTER TABLE `song_playlists`
  MODIFY `song_playlist_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `Admin to User` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `album_artists`
--
ALTER TABLE `album_artists`
  ADD CONSTRAINT `AA to Album` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `AA to Artist` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`) ON DELETE CASCADE;

--
-- Constraints for table `artists`
--
ALTER TABLE `artists`
  ADD CONSTRAINT `Artist to User` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `playlists`
--
ALTER TABLE `playlists`
  ADD CONSTRAINT `Playlist User` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `Review to Album` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Review to Song` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Review to User` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `songs`
--
ALTER TABLE `songs`
  ADD CONSTRAINT `Song to Album` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`) ON DELETE SET NULL;

--
-- Constraints for table `song_artists`
--
ALTER TABLE `song_artists`
  ADD CONSTRAINT `SA to Artist` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `SA to Song` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE;

--
-- Constraints for table `song_playlists`
--
ALTER TABLE `song_playlists`
  ADD CONSTRAINT `SP to Playlist` FOREIGN KEY (`playlist_id`) REFERENCES `playlists` (`playlist_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `SP to Song` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
