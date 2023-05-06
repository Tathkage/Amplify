File Creator: Rohan Dhawan w/Irving Salinas
File Description:
This file takes care of all controller functions for the playlistcreation.php, playlistcreationadmin.php, homePage.php,
homePageAdmin.php. All types of queries are utilised, SELECT, UPDATE, INSERT, and DELETE are all queries that are
integral to the design of the database.
Majority Coding Sections: Rohan Dhawan
Irving Salinas: I added newAccount function for new users who sign up
-->
<?php

require_once '../src/config/config.php';

class adminPageController
{
    private $conn;
    private $user_id = 2;

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
        if (mysqli_connect_errno()) {
            $this->conn->close();
        }
    }

    // function to collect playlists for viewing
    public function collectPlaylists()
    {
        $this->connect();
        // Collects all playlist created by user
        // user_id hard coded until we get user code
        $sql = "SELECT playlists.playlist_id, playlists.playlist_title
            FROM playlists
            WHERE playlists.user_id = 2";

        $result = mysqli_query($this->conn, $sql);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store albums in array
        $playlists = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $playlists[] = $row;
            }
        }
        $this->disconnect();
        return $playlists;
    }

    //  function to collect random albums
    public function collectRandomAlbums(){
        $this->connect();

        $sql = "SELECT albums.album_title, albums.album_id, artists.stage_name
        FROM albums 
        JOIN album_artists ON albums.album_id = album_artists.album_id
        JOIN artists ON album_artists.artist_id = artists.artist_id
        ORDER BY RAND() LIMIT 10";

        $result = mysqli_query($this->conn, $sql);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store albums in array
        $albums = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $albums[] = $row;
            }
        }
        $this->disconnect();
        return $albums;
    }

    //  function to collect random songs
    public function collectRandomSongs(){
        $this->connect();

        $sql = "SELECT songs.song_title, songs.song_id, songs.listens, artists.stage_name
        FROM songs
        JOIN song_artists ON songs.song_id = song_artists.song_id
        JOIN artists ON song_artists.artist_id = artists.artist_id
        ORDER BY RAND() LIMIT 5";

        $result = mysqli_query($this->conn, $sql);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store songs in array
        $songs = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $songs[] = $row;
            }
        }
        $this->disconnect();
        return $songs;
    }

    // function to save information for new playlist


    // function to handle which action to take based on form version
    public function handleFormSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['playlistForm'])) {
                $this;
            }


            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    }

    public function searchSongs($searchTerm) {
        $this->connect();

        $sql = "SELECT songs.song_title, songs.song_id, songs.listens, artists.stage_name
            FROM songs
            JOIN song_artists ON songs.song_id = song_artists.song_id
            JOIN artists ON song_artists.artist_id = artists.artist_id
            WHERE songs.song_title LIKE '%{$searchTerm}%'
            ORDER BY songs.song_title ASC";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        $songs = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $songs[] = $row;
            }
        }

        $this->disconnect();
        return $songs;
    }
    public function searchAlbums($searchTerm) {
        $this->connect();

        $sql = "SELECT albums.album_title, albums.album_id, albums.release_date, artists.stage_name
            FROM albums
            JOIN album_artists ON albums.album_id = album_artists.album_id
            JOIN artists ON album_artists.artist_id = artists.artist_id
            WHERE albums.album_title LIKE '%{$searchTerm}%'
            ORDER BY albums.album_title ASC";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        $albums = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $albums[] = $row;
            }
        }

        $this->disconnect();
        return $albums;
    }
    public function displayPlaylist($playlistId, $userId) {
        $this->connect();

        // Get the playlist name and user who created it
        $sql = "SELECT playlist_title, user_id FROM playlists WHERE playlist_id = '{$playlistId}' LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        if ($result->num_rows == 0) {
            echo "<p>Playlist not found.</p>";
            $this->disconnect();
            return;
        }

        $row = $result->fetch_assoc();
        $playlistName = $row['playlist_title'];
        $playlistUserId = $row['user_id'];

        // Check if the user has permission to access this playlist
        if ($playlistUserId != $userId) {
            echo "<p>You do not have permission to access this playlist.</p>";
            $this->disconnect();
            return;
        }

        echo "<h2>{$playlistName}</h2>";

        $this->disconnect();
    }



}