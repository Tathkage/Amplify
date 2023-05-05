<!--
File Creator: Tathluach Chol

File Description:
    This file supports the front end of the user playlist page view. It handles the back end code that gets specific
    values from the database given specific values from the front end. This allows the user to see specific
    playlist elements and interact with elements on the page.

All Coding Sections: Tathluach Chol
-->

<?php

require_once '../src/config/config.php';

class indPlaylistController {
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

    // Get information on current playlist
    public function getPlaylistInfo($playlist_id) {
        $this->connect();

        // Collects all songs created by artist
        $sql = "SELECT playlists.playlist_id, playlists.user_id, playlists.playlist_title
            FROM playlists
            WHERE playlists.playlist_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $playlist_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store songs in array
        $playlist = array();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $playlist[] = $row['playlist_id'];
            $playlist[] = $row['user_id'];
            $playlist[] = $row['playlist_title'];
        }
        $this->disconnect();
        return $playlist;
    }

    // Get all songs in a playlist
    public function getPlaylistSongs($playlist_id) {
        $this->connect();

        // Collects all songs created by artist
        $sql = "SELECT playlists.playlist_id, playlists.user_id, playlists.playlist_title, songs.song_title, songs.length
            FROM playlists
            JOIN song_playlists ON playlists.playlist_id = song_playlists.playlist_id
            JOIN songs ON song_playlists.song_id = songs.song_id
            WHERE playlists.playlist_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $playlist_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store songs in array
        $playlistSongs = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $playlistSongs[] = $row;
            }
        }
        $this->disconnect();
        return $playlistSongs;
    }

    //////////////////////////
    // SQL Delete Functions //
    //////////////////////////

    // Function to delete song from playlist
    function deletePlaylistSong($selectedSong) {
        $this->connect();

        // Find the song_id corresponding to the selected song title
        $stmt = $this->conn->prepare("SELECT song_id FROM songs WHERE song_title = ?");
        $stmt->bind_param("s", $selectedSong);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            // Handle error: no matching song found
            return;
        }
        $row = $result->fetch_assoc();
        $songId = $row['song_id'];

        // Delete the corresponding entity from the song_playlists table
        $stmt = $this->conn->prepare("DELETE FROM song_playlists WHERE song_id = ?");
        $stmt->bind_param("i", $songId);
        $stmt->execute();

        $this->disconnect();
    }

    //////////////////////////
    // SQL Update Functions //
    //////////////////////////

    // Function to edit the playlists name
    function editPlaylistName($playlistID, $newPlaylistName) {
        $this->connect();

        // Update the playlist title for the given playlist ID
        $stmt = $this->conn->prepare("UPDATE playlists SET playlist_title = ? WHERE playlist_id = ?");
        $stmt->bind_param("si", $newPlaylistName, $playlistID);
        $stmt->execute();

        $this->disconnect();
    }

    /////////////////////////////
    // Handle Form Submissions //
    /////////////////////////////

    // Handle what to function to do depending on form
    public function handleFormSubmit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_song'])) {
            $selectedSong = $_POST['selected_song'];
            $this->deletePlaylistSong($selectedSong);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['playlist_name'])) {
            $playlistID = $_POST['playlist_id'];
            $playlistName = $_POST['playlist_name'];
            $this->editPlaylistName($playlistID, $playlistName);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    }
}