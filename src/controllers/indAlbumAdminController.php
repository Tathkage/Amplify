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
    public function getAlbumInfo($album_id) {
        $this->connect();

        // Collect album info by album_id
        $sql = "SELECT albums.album_id, albums.album_title, albums.release_date, albums.release_time
            FROM albums
            WHERE albums.album_id = ?";

        // Prepare statement
        $stmt = mysqli_prepare($this->conn, $sql);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "i", $album_id);

        // Execute statement
        mysqli_stmt_execute($stmt);

        // Store result
        $result = mysqli_stmt_get_result($stmt);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store album info in array
        $album = array();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $album[] = $row['album_title'];
            $album[] = $row['release_date'];
            $album[] = $row['release_time'];
        }

        // Clean up
        mysqli_stmt_close($stmt);
        $this->disconnect();

        return $album;
    }

    // Get all songs inside of album
    public function getAlbumSongs($album_id) {
        $this->connect();

        // Collects all songs created by artist
        $sql = "SELECT albums.album_id, albums.album_title, albums.release_date, albums.release_time,
            songs.song_title, songs.length, songs.listens
            FROM albums
            JOIN songs ON albums.album_id = songs.album_id
            WHERE albums.album_id = ?";

        // Prepare statement
        $stmt = mysqli_prepare($this->conn, $sql);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "i", $album_id);

        // Execute statement
        mysqli_stmt_execute($stmt);

        // Store result
        $result = mysqli_stmt_get_result($stmt);

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

        // Clean up
        mysqli_stmt_close($stmt);
        $this->disconnect();

        return $albumSongs;
    }

    // Get all reviews on album
    public function getAlbumReviews($album_id) {
        $this->connect();

        // Collects all reviews for an album
        $sql = "SELECT reviews.review_id, reviews.user_id, users.username, reviews.comment, reviews.rating
            FROM reviews
            JOIN users ON reviews.user_id = users.user_id
            WHERE reviews.album_id = ?";

        // Prepare statement
        $stmt = mysqli_prepare($this->conn, $sql);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "i", $album_id);

        // Execute statement
        mysqli_stmt_execute($stmt);

        // Store result
        $result = mysqli_stmt_get_result($stmt);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store reviews in array
        $albumReviews = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $albumReviews[] = $row;
            }
        }

        // Clean up
        mysqli_stmt_close($stmt);
        $this->disconnect();

        return $albumReviews;
    }

    // Get all album collaborators
    public function getAlbumCollaborators($album_id) {
        $this->connect();

        $sql = "SELECT artists.artist_id, artists.stage_name
            FROM album_artists
            JOIN artists ON album_artists.artist_id = artists.artist_id
            WHERE album_artists.album_id = $album_id";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        $collaborators = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $collaborators[] = $row;
            }
        }

        $this->disconnect();
        return $collaborators;
    }

    // Get all album non-collaborators
    public function getNonCollaborators($album_id) {
        $this->connect();

        $sql = "SELECT artists.artist_id, artists.stage_name
            FROM artists
            WHERE artists.artist_id NOT IN (
                SELECT album_artists.artist_id
                FROM album_artists
                WHERE album_artists.album_id = $album_id
            )";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        $nonCollaborators = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $nonCollaborators[] = $row;
            }
        }

        $this->disconnect();
        return $nonCollaborators;
    }

    //////////////////////////
    // SQL Delete Functions //
    //////////////////////////

    // Remove artist from album
    function deleteAlbumCollaborator($artistID) {
        $this->connect();

        // Delete the corresponding entity from the album_artists table
        $stmt = $this->conn->prepare("DELETE FROM album_artists WHERE artist_id = ?");
        $stmt->bind_param("i", $artistID);
        $stmt->execute();

        $this->disconnect();
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

    // Save information for new review
    public function saveAlbumCollaborator($artistID, $albumID) {
        $this->connect();

        // Query to insert into the album_artists table
        $albumArtistInput = mysqli_prepare($this->conn, 'INSERT INTO album_artists (album_id, artist_id) VALUES (?,?)');
        mysqli_stmt_bind_param($albumArtistInput, 'ii', $albumID, $artistID);
        mysqli_stmt_execute($albumArtistInput);
        mysqli_stmt_close($albumArtistInput);

        $this->disconnect();
    }

    /////////////////////////////
    // Handle Form Submissions //
    /////////////////////////////

    // Handle what to function to do depending on form
    public function handleFormSubmit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
            $this->saveAlbumReview();
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['collab_id'])) {
            $artistID = $_POST['collab_id'];
            $this->deleteAlbumCollaborator($artistID);

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['noncollab_id'])) {
            $artistID = $_POST['noncollab_id'];
            $albumID = $_POST['album_id'];
            $this->saveAlbumCollaborator($artistID, $albumID);

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    }
}