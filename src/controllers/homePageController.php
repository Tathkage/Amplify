<?php

require_once '../src/config/config.php';

class homePageController {
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
        ORDER BY RAND() LIMIT 5";

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
    function saveNewPlaylistData()
    {
        $this->connect();

        //    get information from song form
        $playlist_title = $_POST['playlist_title'];

        //    handle empty cases
        if (empty($playlist_title)) {
            return;
        }

        // query for inputting new song
        $playlistInput = mysqli_prepare($this->conn, 'INSERT INTO playlists (user_id, playlist_title) VALUES (?,?)');
        mysqli_stmt_bind_param($playlistInput, 'is', $this->user_id, $playlist_title);
        mysqli_stmt_execute($playlistInput);
        mysqli_stmt_close($playlistInput);

        $this->disconnect();

    }

    // function to handle which action to take based on form version
    public function handleFormSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['playlistForm'])) {
                $this->saveNewPlaylistData();
            }

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    }
}
