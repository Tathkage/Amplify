<?php

require_once '../src/config/config.php';

class artistPageController
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

    // disconnect from database
    public function disconnect()
    {
        if (mysqli_connect_errno()) {
            $this->conn->close();
        }
    }

    public function collectPotentialCollabs() {
        $this->connect();

        $sql = "SELECT *  FROM potential_collabs";

        $otherArtists = mysqli_query($this->conn, $sql);

        if (!$otherArtists) {
            die("Query failed: " . $this->conn->error);
        }

        // Store songs in array
        $Artists = array();
        if ($otherArtists->num_rows > 0) {
            while ($row = $otherArtists->fetch_assoc()) {
                $Artists[] = $row;
            }
        }
        $this->disconnect();
        return $Artists;
    }

    public function collectStageName() {
        $this->connect();

        //
        $sql = "SELECT stage_name FROM artists WHERE artist_id = 1";

        $name = mysqli_query($this->conn, $sql);

        if (!$name) {
            die("Query failed: " . $this->conn->error);
        }

        // Store songs in array
        $artistName = array();
        if ($name->num_rows > 0) {
            while ($row = $name->fetch_assoc()) {
                $artistName[] = $row;
            }
        }
        $this->disconnect();
        return $artistName;
    }


    // function to collect songs for viewing
    public function collectSongs()
    {
        $this->connect();

        // Collects all songs created by artist
        // artist_id hard coded until we get user code
        $sql = "SELECT songs.song_title, songs.listens, songs.album_id, songs.song_id, albums.album_title, songs.length
            FROM songs
            JOIN song_artists ON songs.song_id = song_artists.song_id
            JOIN artists ON song_artists.artist_id = artists.artist_id
            LEFT JOIN albums ON songs.album_id = albums.album_ID
            WHERE artists.artist_id = 1";

        $result = mysqli_query($this->conn, $sql);

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

    // function to collect albums for vewing
    public function collectAlbums()
    {
        $this->connect();
        // Collects all albums created by artist
        // artist_id hard coded until we get user code
        $sql = "SELECT albums.album_title, albums.release_date, albums.release_time, albums.album_id
            FROM albums
            JOIN album_artists ON albums.album_id = album_artists.album_id
            JOIN artists ON album_artists.artist_id = artists.artist_id
            WHERE artists.artist_id = 1";

        $result = mysqli_query($this->conn, $sql);

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

    // function to collect songs with no album for new album creation
    function collectNonAlbumSongs() {
        $this->connect();

        $sql = "SELECT songs.song_id, songs.song_title
            FROM songs
            JOIN song_artists ON songs.song_id = song_artists.song_id
            JOIN artists ON song_artists.artist_id = artists.artist_id
            WHERE artists.artist_id = 1 AND songs.album_id is NULL";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store non album songs in array
        $nonAlbumSongs = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $nonAlbumSongs[] = $row;
            }
        }
        $this->disconnect();
        return $nonAlbumSongs;
    }

    //  Function to show other artists for potential collabs
    function collectOtherArtists() {
        $this->connect();

        $sql = "SELECT artists.artist_id, artists.stage_name FROM artists WHERE artists.artist_id != 1 ";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store artists in array
        $artists = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $artists[] = $row;
            }
        }
        $this->disconnect();
        return $artists;
    }

    // Function to show collaborators for song or album (input is the array) and type is either song or album
    function showCollaborators($input, $type) {
        $this->connect();
        $collabArray = array();

        // loop through song list to collect all collaborators for each song
        foreach ($input as $value) {

            if ($type === "song") {
                $songID = $value['song_id'];
                $sql = mysqli_prepare($this->conn, 'SELECT artists.stage_name
                FROM songs
                JOIN song_artists ON songs.song_id = song_artists.song_id
                JOIN artists ON song_artists.artist_id = artists.artist_id
                WHERE artists.artist_id != 1 AND songs.song_id = ?');
                mysqli_stmt_bind_param($sql, 'i', $songID);
                mysqli_stmt_execute($sql);
            }

            else {
                $albumID = $value['album_id'];
                $sql = mysqli_prepare($this->conn, 'SELECT artists.stage_name
                FROM albums
                JOIN album_artists ON albums.album_id = album_artists.album_id
                JOIN artists ON album_artists.artist_id = artists.artist_id
                WHERE artists.artist_id != 1 AND albums.album_id = ?');
                mysqli_stmt_bind_param($sql, 'i', $albumID);
                mysqli_stmt_execute($sql);
            }

            $result = mysqli_stmt_get_result($sql);
            mysqli_stmt_close($sql);

            if (!$result) {
                die("Query failed: " . $this->conn->error);
            }

            // Store collaborators in array
            $collaborators = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $collaborators[] = $row;
                }
            }

            // adds collaborators to new row of collabArray
            $collabArray[] = $collaborators;
        }

        $this->disconnect();
        return $collabArray;
    }

    // function to save information for new song
    function saveNewSongData($song_title = 'default', $length = '00:00:00', $album_id ='NULL', $release_date ='/01/01/2022', $release_time = '12:45:00', $collaborators = [], $albumCreation = false)
    {
        $this->connect();

        // only take post values if it is a post from individual song creation
        if ($albumCreation === false) {
            //    get information from song form
            $song_title = $_POST['song_title'];
            $length = $_POST['length'];
            $album_id = $_POST['album_id'];
            $release_date = $_POST['release_date'];
            $release_time = $_POST['release_time'];
            $collaborators = $_POST['collaborators'];
        }

        //    handle empty cases
        if (empty($song_title) || empty($length) || empty($release_date) || empty($release_time)) {
            return;
        }

        if ($album_id === 'NULL' || empty($album_id) ) {
            $album_id = NULL;
        }

        // query for inputting new song
        $songInput = mysqli_prepare($this->conn, 'INSERT INTO songs (song_title, length, album_id, release_date, release_time ) VALUES (?,?,?,?,?)');
        mysqli_stmt_bind_param($songInput, 'ssiss', $song_title, $length, $album_id, $release_date, $release_time);
        mysqli_stmt_execute($songInput);
        mysqli_stmt_close($songInput);


        //  query for inputting new song_artists
        $song_id = mysqli_insert_id($this->conn);
        $artist_id = 1;
        $songArtistsInput = mysqli_prepare($this->conn, 'INSERT INTO song_artists (song_id, artist_id) VALUES (?,?)');
        mysqli_stmt_bind_param($songArtistsInput, 'ii', $song_id, $artist_id);
        mysqli_stmt_execute($songArtistsInput);
        mysqli_stmt_close($songArtistsInput);

        // add collaborators to song if needed
        if (!empty($collaborators)) {
            foreach ($collaborators as $collaborator_id) {
                $songArtistsInput = mysqli_prepare($this->conn, 'INSERT INTO song_artists (song_id, artist_id) VALUES (?,?)');
                mysqli_stmt_bind_param($songArtistsInput, 'ii', $song_id, $collaborator_id);
                mysqli_stmt_execute($songArtistsInput);
                mysqli_stmt_close($songArtistsInput);
            }
        }

        $this->disconnect();

    }
    // function to save information for new album
    function saveNewAlbumData()
    {
        $this->connect();
        //    get information from album form
        $album_title = $_POST['album_title'];
        $release_date = $_POST['release_date'];
        $release_time = $_POST['release_time'];
        $previousSongs = $_POST['songs'];
        $songTitles = $_POST['song_title'];
        $songLengths = $_POST['length'];
        $collaborators = $_POST['collaborators'];
        $newSongs = array_combine($songTitles, $songLengths);



        //    handle empty cases
        if (empty($album_title) || empty($release_date) || empty($release_time)) {
            return;
        }

        // add new row to album table
        $albumInput = mysqli_prepare($this->conn, 'INSERT INTO albums (album_title, release_date, release_time) VALUES (?,?,?)');
        mysqli_stmt_bind_param($albumInput, 'sss', $album_title, $release_date, $release_time);
        mysqli_stmt_execute($albumInput);
        mysqli_stmt_close($albumInput);

        // add new rows to album artists
        $album_id = mysqli_insert_id($this->conn);
        $artist_id = 1;
        $albumArtistsInput = mysqli_prepare($this->conn, 'INSERT INTO album_artists (album_id, artist_id) VALUES (?,?)');
        mysqli_stmt_bind_param($albumArtistsInput, 'ii', $album_id, $artist_id);
        mysqli_stmt_execute($albumArtistsInput);
        mysqli_stmt_close($albumArtistsInput);

        // give songs album id foreign key if necessary
        if (!empty($previousSongs)) {
            foreach ($previousSongs as $song_id) {
                $foreignKeyInsert = mysqli_prepare($this->conn, 'UPDATE songs set album_id = ? where song_id = ?');
                mysqli_stmt_bind_param($foreignKeyInsert, 'ii', $album_id, $song_id);
                mysqli_stmt_execute($foreignKeyInsert);
                mysqli_stmt_close($foreignKeyInsert);
            }
        }

        // add collaborators to album if needed
        if (!empty($collaborators)) {
            foreach ($collaborators as $collaborator_id) {
                $songArtistsInput = mysqli_prepare($this->conn, 'INSERT INTO album_artists (album_id, artist_id) VALUES (?,?)');
                mysqli_stmt_bind_param($songArtistsInput, 'ii', $album_id, $collaborator_id);
                mysqli_stmt_execute($songArtistsInput);
                mysqli_stmt_close($songArtistsInput);
            }
        }

        // insert all songs created for album into database
        foreach ($newSongs as $title => $length) {
            // add new row to song table
            if (empty($title) || empty($length)) {
                continue;
            }
            $this->saveNewSongData($title, $length, $album_id, $release_date, $release_time, $collaborators, true);
        }
        if ($this->conn) {
            $this->disconnect();
        }
    }

    function changeName() {
        $this->connect();

        //    get information from album form
        $newName = $_POST['new_name'];

        //    handle empty cases
        if (empty($newName)) {
            return;
        }

        // Update Artists name in database
        $nameChange = mysqli_prepare($this->conn, 'UPDATE artists set stage_name = ? WHERE artist_id = 1');
        mysqli_stmt_bind_param($nameChange, 's', $newName);
        mysqli_stmt_execute($nameChange);
        mysqli_stmt_close($nameChange);

    }

    function deleteArtist() {
        $this->connect();


        // Delete artist from database
        $removeArtist = mysqli_prepare($this->conn, 'DELETE FROM artists WHERE artist_id = 1');
        mysqli_stmt_execute($removeArtist);
        mysqli_stmt_close($removeArtist);

        if ($this->conn) {
            $this->disconnect();
        }

    }
    // function to handle which action to take based on form version
    public function handleFormSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['songForm'])) {
                $this->saveNewSongData();
            }

            elseif (isset($_POST['albumForm'])) {
                $this->saveNewAlbumData();
            }

            elseif (isset($_POST['changeNameForm'])) {
                $this->changeName();
            }

            elseif (isset($_POST['deleteArtistForm'])) {
                $this->deleteArtist();
                header("Location: /homePage.php" );
                exit();
            }

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    }
}