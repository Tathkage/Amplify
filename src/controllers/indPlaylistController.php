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
    public function defaultPlaylist() {
        $this->connect();

        // Collects all songs created by artist
        // playlist_id hard coded for now
        $sql = "SELECT playlists.playlist_id, playlists.user_id, playlists.playlist_title
            FROM playlists
            WHERE playlists.playlist_id = 7";

        $result = mysqli_query($this->conn, $sql);

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
    public function playlistSongs() {
        $this->connect();

        // Collects all songs created by artist
        // song_id hard coded for now
        $sql = "SELECT playlists.playlist_id, playlists.user_id, playlists.playlist_title, songs.song_title, songs.length
                FROM playlists
                JOIN song_playlists ON playlists.playlist_id = song_playlists.playlist_id
                JOIN songs ON song_playlists.song_id = songs.song_id
                WHERE playlists.playlist_id = 7";

        $result = mysqli_query($this->conn, $sql);

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
    /// //////////////////////

    // Function to delete song from playlist
    function deleteSong($selectedSong) {
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


    // Handle what to delete depending on form
    public function handleFormSubmit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_song'])) {
            $selectedSong = $_POST['selected_song'];
            $this->deleteSong($selectedSong);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    }
}