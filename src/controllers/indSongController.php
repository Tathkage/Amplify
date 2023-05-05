<!--
File Creator: Tathluach Chol

File Description:
    This file supports the front end of the user song page view. It handles the back end code that gets specific
    values from the database given specific values from the front end. This allows the user to see specific
    song elements and interact with elements on the page.

All Coding Sections: Tathluach Chol
-->

<?php

require_once '../src/config/config.php';

class indSongController {
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

    // Get information on the current song
    public function getSongInfo($song_id) {
        $this->connect();

        // Collects all songs created by artist
        $sql = "SELECT songs.song_title, songs.listens, songs.length, songs.release_date, songs.release_time
        FROM songs
        WHERE songs.song_id = $song_id";

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
            $song[] = $row['length'];
            $song[] = $row['release_date'];
            $song[] = $row['release_time'];
        }
        $this->disconnect();
        return $song;
    }

    // Get all reviews on the current song
    public function getSongReviews($song_id) {
        $this->connect();

        // Collects all reviews for a given song
        $sql = "SELECT reviews.review_id, reviews.user_id, users.username, reviews.comment, reviews.rating
            FROM reviews
            JOIN users ON reviews.user_id = users.user_id
            WHERE reviews.song_id = $song_id";

        $result = mysqli_query($this->conn, $sql);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store reviews in array
        $songReviews = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $songReviews[] = $row;
            }
        }
        $this->disconnect();
        return $songReviews;
    }

    // Get all playlists created by the user
    public function getUserPlaylist($user_id) {
        $this->connect();

        $sql = "SELECT playlists.playlist_id, playlists.playlist_title
            FROM playlists
            WHERE playlists.user_id = $user_id";

        $result = mysqli_query($this->conn, $sql);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store playlists in array
        $userPlaylists = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userPlaylists[] = $row;
            }
        }
        $this->disconnect();
        return $userPlaylists;
    }

    //////////////////////////
    // SQL INSERT Functions //
    //////////////////////////

    // Save information for new review
    function saveSongReview($user_id, $song_id, $album_id ='NULL', $comment = 'Default comment.', $rating = 5) {

        $this->connect();

        // Get information from review form
        $comment = $_POST['comment'];
        $rating = $_POST['rating'];

        // Handle empty cases
        if (empty($comment) || empty($rating)) {
            return;
        }

        if ($album_id === 'NULL' || empty($album_id) ) {
            $album_id = NULL;
        }

        // Query for inputting new review
        $reviewInput = mysqli_prepare($this->conn, 'INSERT INTO reviews (user_id, song_id, album_id, comment, rating) VALUES (?,?,?,?,?)');
        mysqli_stmt_bind_param($reviewInput, 'ssiss', $user_id, $song_id, $album_id, $comment, $rating);
        mysqli_stmt_execute($reviewInput);
        mysqli_stmt_close($reviewInput);

        $this->disconnect();
    }

    // Save information to add song to playlist
    function savePlaylistSong($song_id, $playlist_id) {

        $this->connect();

        // Handle empty cases
        if (empty($song_id) || empty($playlist_id)) {
            return;
        }


        // Query for inputting new song_playlists
        $songPlaylistsInput = mysqli_prepare($this->conn, 'INSERT INTO song_playlists (playlist_id, song_id) VALUES (?,?)');
        mysqli_stmt_bind_param($songPlaylistsInput, 'ii', $playlist_id, $song_id);
        mysqli_stmt_execute($songPlaylistsInput);
        mysqli_stmt_close($songPlaylistsInput);

        $this->disconnect();

    }

    // Handle which information to save based off form submitted
    public function handleFormSubmit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
            $user_id = $_POST['user_id'];
            $song_id = $_POST['song_id'];
            $this->saveSongReview($user_id, $song_id);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['playlist_id'])) {
            $song_id = $_POST['song_id'];
            $playlist_id = $_POST['playlist_id'];
            $this->savePlaylistSong($song_id, $playlist_id);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    }
}