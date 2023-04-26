<?php

require_once '../src/config/config.php';

class indPlaylistController
{
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

    // Function to get default song for testing
    public function defaultPlaylist()
    {
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

    public function playlistSongs()
    {
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
        $songReviews = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $songReviews[] = $row;
            }
        }
        $this->disconnect();
        return $songReviews;
    }

    //////////////////////////
    // SQL INSERT Functions //
    //////////////////////////


}