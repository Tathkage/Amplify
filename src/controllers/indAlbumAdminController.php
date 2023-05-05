<!--
File Creator: Tathluach Chol

File Description:
    This file supports the front end of the admin album page view. It handles the back end code that gets specific
    values from the database given specific values from the front end. This allows the user to see specific
    album elements and interact with elements on the page.

All Coding Sections: Tathluach Chol
-->

<?php

require_once '../src/config/config.php';

class indAlbumAdminController {
    private $conn;

    ///////////////////////////////////
    // Database Connection Functions //
    ///////////////////////////////////

    // Connect to database
    public function connect() {
        $this->conn =  mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Disconnect from database
    public function disconnect() {
        $this->conn->close();
    }


    //////////////////////////
    // SQL SELECT Functions //
    //////////////////////////

    // Get information on current album
    public function defaultAlbum() {
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

    // Get all songs inside of album
    public function albumSongs() {
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
        $albumSongs = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $albumSongs[] = $row;
            }
        }
        $this->disconnect();
        return $albumSongs;
    }

    // Get all reviews on album
    public function albumReviews() {
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
        $albumReviews = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $albumReviews[] = $row;
            }
        }
        $this->disconnect();
        return $albumReviews;
    }

    //////////////////////////
    // SQL INSERT Functions //
    //////////////////////////

    // Save information for new review
    function saveAlbumReview($user_id = 3, $song_id ='NULL', $album_id ='NULL', $comment = 'Default comment.', $rating = 5) {

        $this->connect();

        // Get information from page
        $user_id = 3;
        $album_id = 38;

        // Get information from review form
        $comment = $_POST['comment'];
        $rating = $_POST['rating'];

        // Handle empty cases
        if (empty($comment) || empty($rating)) {
            return;
        }

        if ($song_id === 'NULL' || empty($song_id) ) {
            $song_id = NULL;
        }

        // Query for inputting new review
        $reviewInput = mysqli_prepare($this->conn, 'INSERT INTO reviews (user_id, song_id, album_id, comment, rating) VALUES (?,?,?,?,?)');
        mysqli_stmt_bind_param($reviewInput, 'ssiss', $user_id, $song_id, $album_id, $comment, $rating);
        mysqli_stmt_execute($reviewInput);
        mysqli_stmt_close($reviewInput);

        $this->disconnect();

    }

    // Handle which information to save based off form submitted
    public function handleFormSubmit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
            $this->saveAlbumReview();
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    }
}