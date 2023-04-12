<?php
// Database credentials
const DB_HOST = 'localhost';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_NAME = 'amplify';

// Create connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD);

// Check connection
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

// Create the tables if they don't exist
$sql = "
CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` int NOT NULL,
  `admin_password` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `albums` (
  `album_id` int NOT NULL,
  `album_title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `release_date` date NOT NULL,
  `release_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `album_artists` (
  `album_artists_id` int NOT NULL,
  `album_id` int NOT NULL,
  `artist_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `artists` (
  `artist_id` int NOT NULL,
  `user_id` int NOT NULL,
  `stage_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `playlists` (
  `playlist_id` int NOT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int NOT NULL,
  `user_id` int NOT NULL,
  `song_id` int DEFAULT NULL,
  `album_id` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `rating` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `songs` (
  `song_id` int NOT NULL,
  `song_title` int NOT NULL,
  `length` time NOT NULL,
  `listens` int NOT NULL,
  `album_id` int DEFAULT NULL,
  `release_date` int NOT NULL,
  `release_time` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `song_artists` (
  `song_artist_id` int NOT NULL,
  `song_id` int NOT NULL,
  `artist_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS 'song_playlists' (
    'song_playlist_id' int NOT NULL,
    'song_id' int not NULL,
    'playlist_id' int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS users (
user_id int NOT NULL,
username varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
password varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
email varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
date_joined datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS user_albums (
user_album_id int NOT NULL,
user_id int NOT NULL,
album_id int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS user_playlists (
user_playlist_id int NOT NULL,
user_id int NOT NULL,
playlist_id int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS user_reviews (
user_review_id int NOT NULL,
user_id int NOT NULL,
review_id int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS user_songs (
user_song_id int NOT NULL,
user_id int NOT NULL,
song_id int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS songs_playlists (
song_playlist_id int NOT NULL,
song_id int NOT NULL,
playlist_id int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

// Close the connection
$conn->close();
?>