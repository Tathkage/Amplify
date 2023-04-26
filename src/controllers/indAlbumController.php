<?php

require_once '../src/config/config.php';

class indAlbumController
{
    private $conn;

    // Connect to database
    public function connect()
    {
        $this->conn =  mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // disconnect from database
    public function disconnect()
    {
        $this->conn->close();
    }

    // Function to get default song for testing
    public function defaultAlbum()
    {
        $this->connect();

        // Collects all songs created by artist
        // playlist_id hard coded for now
        $sql = "SELECT albums.album_id, albums.album_title, albums.release_date, albums.release_time
            FROM albums
            WHERE albums.album_id = 38";

        $result = mysqli_query($this->conn, $sql);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store songs in array
        $album = array();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $album[] = $row['album_title'];
            $album[] = $row['release_date'];
            $album[] = $row['release_time'];
        }
        $this->disconnect();
        return $album;
    }

    public function albumSongs()
    {
        $this->connect();

        // Collects all songs created by artist
        // song_id hard coded for now
        $sql = "SELECT albums.album_id, albums.album_title, albums.release_date, albums.release_time,
                songs.song_title, songs.length, songs.listens
                FROM albums
                JOIN songs ON albums.album_id = songs.album_id
                WHERE albums.album_id = 38";


        $result = mysqli_query($this->conn, $sql);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store songs in array
        $songReviews = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $songReviews[] = $row;
            }
        }
        $this->disconnect();
        return $songReviews;
    }

    public function albumReviews()
    {
        $this->connect();

        // Collects all songs created by artist
        // song_id hard coded for now
        $sql = "SELECT reviews.review_id, reviews.user_id, users.username, reviews.comment, reviews.rating
                FROM reviews
                JOIN users ON reviews.user_id = users.user_id
                WHERE reviews.album_id = 38";


        $result = mysqli_query($this->conn, $sql);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store songs in array
        $songReviews = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $songReviews[] = $row;
            }
        }
        $this->disconnect();
        return $songReviews;
    }
}