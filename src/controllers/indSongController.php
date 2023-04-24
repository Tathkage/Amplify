<?php

require_once '../src/config/config.php';

class indSongController
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

    // Disconnect from database
    public function disconnect()
    {
        $this->conn->close();
    }

    // Function to get default song for testing
    public function defaultSong()
    {
        $this->connect();

        // Collects all songs created by artist
        // song_id hard coded for now
        $sql = "SELECT songs.song_title, songs.listens, songs.release_date, songs.release_time
            FROM songs
            WHERE songs.song_id = 22";

        $result = mysqli_query($this->conn, $sql);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store songs in array
        $song = array();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $song[] = $row['song_title'];
            $song[] = $row['listens'];
            $song[] = $row['release_date'];
            $song[] = $row['release_time'];
        }
        $this->disconnect();
        return $song;
    }

    // Function to get default song for testing
    public function songReviews()
    {
        $this->connect();

        // Collects all songs created by artist
        // song_id hard coded for now
        $sql = "SELECT reviews.review_id, reviews.user_id, reviews.comment, reviews.rating
            FROM reviews
            WHERE reviews.song_id = 22";

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

    // Function to save information for new review
    function saveReviewData()
    {
        $this->connect();


        $this->disconnect();

    }
}