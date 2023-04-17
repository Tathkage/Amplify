<?php

require_once '../src/config/config.php';

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Collects all songs created by artist
// artist id hard coded until we get user code
$sql = "SELECT songs.song_title, songs.listens
        FROM songs
        JOIN song_artists ON songs.song_id = song_artists.song_id
        JOIN artists ON song_artists.artist_id = artists.artist_id
        WHERE artists.artist_id = 1";
$result = $conn->query($sql);

// Check if query execution was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Store songs in array
$songs = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $songs[] = $row;
    }
}

// Collects all albums created by artist
// artist id hard coded until we get user code
$sql = "SELECT albums.album_title, albums.release_date, albums.release_time
        FROM albums
        JOIN album_artists ON albums.album_id = album_artists.album_id
        JOIN artists ON album_artists.artist_id = artists.artist_id
        WHERE artists.artist_id = 1";
$result = $conn->query($sql);

// Check if query execution was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Store albums in array
$albums = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $albums[] = $row;
    }
}
