<?php

require_once '../src/config/config.php';

class artistsController
{
    private $conn;

    // Connect to database
    public function connect()
    {
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function disconnect()
    {
        $this->conn->close();
    }

    public function collectSongs()
    {
        $this->connect();
        // Collects all songs created by artist
        // artist id hard coded until we get user code
        $sql = "SELECT songs.song_title, songs.listens
            FROM songs
            JOIN song_artists ON songs.song_id = song_artists.song_id
            JOIN artists ON song_artists.artist_id = artists.artist_id
            WHERE artists.artist_id = 1";
        $result = $this->conn->query($sql);

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

    public function collectAlbums()
    {
        $this->connect();
        // Collects all albums created by artist
        // artist id hard coded until we get user code
        $sql = "SELECT albums.album_title, albums.release_date, albums.release_time, albums.album_id
            FROM albums
            JOIN album_artists ON albums.album_id = album_artists.album_id
            JOIN artists ON album_artists.artist_id = artists.artist_id
            WHERE artists.artist_id = 1";
        $result = $this->conn->query($sql);

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

    function saveNewSongData()
    {
        $this->connect();
        //    get information from song form
        $song_title = $_POST['song_title'];
        $length = $_POST['length'];
        $album_id = $_POST['album_id'];
        $release_date = $_POST['release_date'];
        $release_time = $_POST['release_time'];

        //    handle empty cases
        if (empty($song_title) || empty($length) || empty($release_date) || empty($release_time)) {
            return;
        }

        if (empty($album_id)) {
            $album_id = NULL;
        }

        $songInput = mysqli_prepare($this->conn, 'INSERT INTO songs (song_title, length, album_id, release_date, release_time ) VALUES (?,?,?,?,?)');

        //    put parameters into songInput
        mysqli_stmt_bind_param($songInput, 'siiss', $song_title, $length, $album_id, $release_date, $release_time);

        //    execute input statement
        mysqli_stmt_execute($songInput);

        //   Close statement
        mysqli_stmt_close($songInput);

        $collect_id = $this->conn->prepare("SELECT song_id FROM songs WHERE song_title = ?");
        $collect_id->bind_param("s", $song_title);
        $collect_id->execute();
        $result = $collect_id->get_result();
        $row = $result->fetch_assoc();
        $song_id = $row['song_id'];
        $artist_id = 1;

        $songArtistsInput = mysqli_prepare($this->conn, 'INSERT INTO song_artists (song_id, artist_id) VALUES (?,?)');

        //    put parameters into songInput
        mysqli_stmt_bind_param($songArtistsInput, 'ii', $song_id, $artist_id);

        //    execute input statement
        mysqli_stmt_execute($songArtistsInput);

        //   Close statement
        mysqli_stmt_close($songArtistsInput);

        $this->disconnect();

    }

    public function handleFormSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->saveNewSongData();
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    }
}