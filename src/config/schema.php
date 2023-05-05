<?php
require_once 'config.php';

// Create connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS amplify";
if ($conn->query($sql) !== true) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db(DB_NAME);
//-- Table structure for table `songs`
//--

// Create the tables if they don't exist
$sql = "DROP TABLE IF EXISTS album_artists, song_artists, song_playlists, admins, reviews, artists, songs, playlists, users, albums;

DROP VIEW IF EXISTS potential_collabs, flagged_album_reviews;

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_password` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `album_id` int(11) NOT NULL,
  `album_title` varchar(100) NOT NULL,
  `release_date` date NOT NULL,
  `release_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`album_id`, `album_title`, `release_date`, `release_time`) VALUES
(81, 'try ', '2023-05-09', '09:00:00'),
(82, 'glass shoe', '2023-05-11', '13:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `album_artists`
--

CREATE TABLE `album_artists` (
  `album_artists_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `artist_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `album_artists`
--

INSERT INTO `album_artists` (`album_artists_id`, `album_id`, `artist_id`) VALUES
(79, 81, 1),
(80, 82, 1);

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `artist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stage_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`artist_id`, `user_id`, `stage_name`) VALUES
(1, 2, 'BiG WAY'),
(2, 3, 'ROH');

-- --------------------------------------------------------

--
-- Stand-in structure for view `flagged_album_reviews`
-- (See below for the actual view)
--
CREATE TABLE `flagged_album_reviews` (
`review_id` int(11)
,`user_id` int(11)
,`username` varchar(20)
,`comment` text
,`rating` int(11)
,`album_id` int(11)
,`song_id` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `playlists`
--

CREATE TABLE `playlists` (
  `playlist_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `playlist_title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `playlists`
--

INSERT INTO `playlists` (`playlist_id`, `user_id`, `playlist_title`) VALUES
(7, 1, 'My Playlist'),
(8, 3, 'My Playlist'),
(9, 2, 'My Playlist');

-- --------------------------------------------------------

--
-- Stand-in structure for view `potential_collabs`
-- (See below for the actual view)
--
CREATE TABLE `potential_collabs` (
`stage_name` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `flagged_album_reviews`
-- (See below for the actual view)
--
CREATE TABLE `flagged_album_reviews` (
`comment` text
,`rating` int
,`review_id` int
,`user_id` int
,`username` varchar(20)
);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `song_id` int(11) DEFAULT NULL,
  `album_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `song_id` int(11) NOT NULL,
  `song_title` varchar(100) NOT NULL,
  `length` time NOT NULL,
  `listens` int(11) NOT NULL,
  `album_id` int(11) DEFAULT NULL,
  `release_date` date NOT NULL,
  `release_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`song_id`, `song_title`, `length`, `listens`, `album_id`, `release_date`, `release_time`) VALUES
(107, 'why', '00:01:25', 0, NULL, '2023-05-16', '12:45:00'),
(108, 'never broke again', '00:02:30', 0, 81, '2023-05-29', '13:15:00'),
(109, 'if this is it pt3', '00:03:20', 0, 81, '2023-05-16', '13:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `song_artists`
--

CREATE TABLE `song_artists` (
  `song_artist_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL,
  `artist_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `song_artists`
--

INSERT INTO `song_artists` (`song_artist_id`, `song_id`, `artist_id`) VALUES
(101, 107, 1),
(103, 109, 1),
(104, 109, 2);

-- --------------------------------------------------------

--
-- Table structure for table `song_playlists`
--

CREATE TABLE `song_playlists` (
  `song_playlist_id` int(11) NOT NULL,
  `playlist_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(320) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `email`) VALUES
(1, 'deez', 'ben_dover', 'Rohan', 'Dhawan', 'rohan@gmail.com'),
(2, 'nutz', 'yes', 'Wayland', 'Moody', 'wayland@gmail.com'),
(3, 'got_eem', 'nope', 'Tathluach', 'Chol', 'tathluach@gmail.com');

-- --------------------------------------------------------

--
-- Structure for view `flagged_album_reviews`
--
DROP TABLE IF EXISTS `flagged_album_reviews`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `flagged_album_reviews`  AS SELECT `reviews`.`review_id` AS `review_id`, `reviews`.`user_id` AS `user_id`, `users`.`username` AS `username`, `reviews`.`comment` AS `comment`, `reviews`.`rating` AS `rating`, `reviews`.`album_id` AS `album_id`, `reviews`.`song_id` AS `song_id` FROM (`reviews` join `users` on(`reviews`.`user_id` = `users`.`user_id`)) WHERE `reviews`.`comment` like '%first curse%' OR `reviews`.`comment` like '%second curse%' OR `reviews`.`comment` like '%third curse%''%third curse%'  ;

-- --------------------------------------------------------

--
-- Structure for view `potential_collabs`
--
DROP TABLE IF EXISTS `potential_collabs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `potential_collabs`  AS SELECT `artists`.`stage_name` AS `stage_name` FROM `artists` ORDER BY rand() ASC LIMIT 10;

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
  ADD KEY `Artist to User` (`user_id`),
  ADD KEY `stage_name` (`stage_name`);

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
  MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `album_artists`
--
ALTER TABLE `album_artists`
  MODIFY `album_artists_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `artist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `playlists`
--
ALTER TABLE `playlists`
  MODIFY `playlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `song_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `song_artists`
--
ALTER TABLE `song_artists`
  MODIFY `song_artist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `song_playlists`
--
ALTER TABLE `song_playlists`
  MODIFY `song_playlist_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
COMMIT;";

// Execute the query
if ($conn->multi_query($sql) === TRUE) {
    echo "database created";
} else {
    echo "Error creating table: " . $conn->error;
}

// Close the connection
$conn->close();
?>