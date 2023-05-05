<!--
File Creator: Wayland Moody

File Description:

    This file is the back end page for the artistPageAdmin.php Page.
    It executes queries and returns the results back to the artistPageAdmin

All Coding Sections: Wayland Moody
-->

<?php

require_once '../src/config/config.php';

class artistPageAdminController
{

    private $conn;

    ///////////////////////////////////
    // Database Connection Functions //
    ///////////////////////////////////

    // Connect to database
    public function connect()
    {
        $this->conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
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

    /////////////////////////////////////////////////
    // INFORMATION COLLECTION FUNCTIONS: 7 SELECTS //
    /////////////////////////////////////////////////

    public function collectAdminName()
    {
        $this->connect();

        //
        $sql = "SELECT username FROM users WHERE user_id = 2";

        $name = mysqli_query($this->conn, $sql);

        if (!$name) {
            die("Query failed: " . $this->conn->error);
        }

        // Store songs in array
        $adminName = array();
        if ($name->num_rows > 0) {
            while ($row = $name->fetch_assoc()) {
                $adminName[] = $row;
            }
        }
        $this->disconnect();
        return $adminName;
    }

    // function to collect albums for viewing
    public function collectAlbums()
    {
        $this->connect();

        // Collects all albums
        $sql = "SELECT albums.album_title, albums.release_date, albums.release_time, albums.album_id, albums.album_id
            FROM albums
            LEFT JOIN album_artists ON albums.album_id = album_artists.album_id
            LEFT JOIN artists ON album_artists.artist_id = artists.artist_id";

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

    //  Function to show all artists
    function collectAllArtists()
    {
        $this->connect();

        $sql = "SELECT artists.artist_id, artists.stage_name FROM artists;";

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

    // function to collect songs for viewing
    public function collectAllSongs()
    {
        $this->connect();

        // Collects all songs created by artist
        $sql = "SELECT songs.song_title, songs.listens, songs.album_id, songs.song_id, albums.album_title, songs.length, songs.release_date, songs.release_time
            FROM songs
            LEFT JOIN albums ON songs.album_id = albums.album_ID";

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

    // function to collect songs with no album for new album creation
    function collectNonAlbumSongs()
    {
        $this->connect();

        $sql = "SELECT songs.song_id, songs.song_title
            FROM songs
            WHERE songs.album_id is NULL";

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

    // Function to show collaborators for song or album (input is the array) and type is either song or album
    function showCollaborators($input, $type)
    {
        $this->connect();
        $artistArray = array();

        // loop through song list to collect all collaborators for each song
        foreach ($input as $value) {

            if ($type === "song") {
                $songID = $value['song_id'];
                $sql = mysqli_prepare($this->conn, 'SELECT artists.stage_name
                FROM songs
                JOIN song_artists ON songs.song_id = song_artists.song_id
                JOIN artists ON song_artists.artist_id = artists.artist_id
                WHERE songs.song_id = ?');
                mysqli_stmt_bind_param($sql, 'i', $songID);
                mysqli_stmt_execute($sql);
            } else {
                $albumID = $value['album_id'];
                $sql = mysqli_prepare($this->conn, 'SELECT artists.stage_name
                FROM albums
                JOIN album_artists ON albums.album_id = album_artists.album_id
                JOIN artists ON album_artists.artist_id = artists.artist_id
                WHERE albums.album_id = ?');
                mysqli_stmt_bind_param($sql, 'i', $albumID);
                mysqli_stmt_execute($sql);
            }

            $result = mysqli_stmt_get_result($sql);
            mysqli_stmt_close($sql);

            if (!$result) {
                die("Query failed: " . $this->conn->error);
            }

            // Store collaborators in array
            $artists = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $artists[] = $row;
                }
            }

            // adds collaborators to new row of artistArray
            $artistArray[] = $artists;
        }

        $this->disconnect();
        return $artistArray;
    }

    ///////////////////////////////////////////////
    // Data Saving Functions: 4 INSERTS 1 UPDATE //
    ///////////////////////////////////////////////

    // function to save information for new song
    function saveNewSongData($song_title = 'default', $length = '00:00:00', $album_id = NULL, $release_date = '/01/01/2022', $release_time = '12:45:00', $artists = [], $albumCreation = false)
    {
        $this->connect();

        // only take post values if it is a post from individual song creation
        if ($albumCreation === false) {
            //    get information from song form
            $song_title = $_POST['song_title'];
            $length = $_POST['length'];
            $release_date = $_POST['release_date'];
            $release_time = $_POST['release_time'];
            $artists = $_POST['artists'];
        }

        //    handle empty cases
        if (empty($song_title) || empty($length) || empty($release_date) || empty($release_time)) {
            return;
        }

        if ($album_id === 'NULL' || empty($album_id)) {
            $album_id = NULL;
        }

        // query for inputting new song
        $songInput = mysqli_prepare($this->conn, 'INSERT INTO songs (song_title, length, release_date, release_time, album_id) VALUES (?,?,?,?,?)');
        mysqli_stmt_bind_param($songInput, 'ssssi', $song_title, $length, $release_date, $release_time, $album_id);
        mysqli_stmt_execute($songInput);
        mysqli_stmt_close($songInput);

        // add artists to song if needed
        $song_id = mysqli_insert_id($this->conn);
        if (!empty($artists)) {
            foreach ($artists as $artist_id) {
                $songArtistsInput = mysqli_prepare($this->conn, 'INSERT INTO song_artists (song_id, artist_id) VALUES (?,?)');
                mysqli_stmt_bind_param($songArtistsInput, 'ii', $song_id, $artist_id);
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
        $albumArtists = $_POST['albumArtists'];
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
        if (!empty($albumArtists)) {
            foreach ($albumArtists as $artist_id) {
                $songArtistsInput = mysqli_prepare($this->conn, 'INSERT INTO album_artists (album_id, artist_id) VALUES (?,?)');
                mysqli_stmt_bind_param($songArtistsInput, 'ii', $album_id, $artist_id);
                mysqli_stmt_execute($songArtistsInput);
                mysqli_stmt_close($songArtistsInput);
            }
        }

        // add song to album if the song has an artist on the album, if not don't add the song
        if (!empty($previousSongs)) {
            foreach ($previousSongs as $song_id) {
                $foreignKeyInsert = mysqli_prepare($this->conn, 'UPDATE songs
                    INNER JOIN song_artists ON songs.song_id = song_artists.song_id
                    INNER JOIN album_artists ON song_artists.artist_id = album_artists.artist_id
                    INNER JOIN albums ON album_artists.album_id = albums.album_id
                    SET songs.album_id = albums.album_id
                    WHERE songs.album_id IS NULL
                    AND albums.album_id = ?
                    AND songs.song_id = ?
                    LIMIT 1;');
                mysqli_stmt_bind_param($foreignKeyInsert, 'ii', $album_id, $song_id);
                mysqli_stmt_execute($foreignKeyInsert);
                mysqli_stmt_close($foreignKeyInsert);
            }
        }


        // insert all songs created for album into database
        foreach ($newSongs as $title => $length) {
            // add new row to song table
            if (empty($title) || empty($length)) {
                continue;
            }
            $this->saveNewSongData($title, $length, $album_id, $release_date, $release_time, $albumArtists, true);
        }
        if ($this->conn) {
            $this->disconnect();
        }
    }

    /////////////////////////////////////////
    // Data Changing Functions: 1 UPDATE ///
    ///////////////////////////////////////

    function editAlbum()
    {
        $this->connect();

        //    get information from edit album form
        $release_date = $_POST['release_date'];
        $release_time = $_POST['release_time'];
        $album_title = $_POST['album_title'];
        $album_id = $_POST['album_id'];

        // edit row from table
        $editAlbumStatement = mysqli_prepare($this->conn, 'UPDATE albums SET release_date = ?, release_time = ?, album_title = ? WHERE album_id = ?');
        mysqli_stmt_bind_param($editAlbumStatement, 'sssi', $release_date, $release_time, $album_title, $album_id);
        mysqli_stmt_execute($editAlbumStatement);
        mysqli_stmt_close($editAlbumStatement);

        if ($this->conn) {
            $this->disconnect();
        }

    }

    /////////////////////////////////////////
    // Data Deleting Functions: 2 DELETES //
    ///////////////////////////////////////

    // function that deletes song give song id, it will delete all records including in album_song table
    function deleteSong($song_id)
    {
        $this->connect();

        // add new row to album table
        $deleteSongStatement = mysqli_prepare($this->conn, 'DELETE FROM songs WHERE song_id = ?');
        mysqli_stmt_bind_param($deleteSongStatement, 'i', $song_id);
        mysqli_stmt_execute($deleteSongStatement);
        mysqli_stmt_close($deleteSongStatement);

        if ($this->conn) {
            $this->disconnect();
        }
    }

    // function that deletes album given the specific album_id this will delete and all records including in album_artist table
    function deleteAlbum($album_id)
    {
        $this->connect();

        // add new row to album table
        $deleteAlbumStatement = mysqli_prepare($this->conn, 'DELETE FROM albums WHERE album_id = ?');
        mysqli_stmt_bind_param($deleteAlbumStatement, 'i', $album_id);
        mysqli_stmt_execute($deleteAlbumStatement);
        mysqli_stmt_close($deleteAlbumStatement);

        if ($this->conn) {
            $this->disconnect();
        }
    }

    ///////////////////
    // Form Handling //
    ///////////////////

    // function to handle which action to take based on form version album_id is optional
    public function handleFormSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['songForm'])) {
                $this->saveNewSongData();
            } elseif (isset($_POST['albumForm'])) {
                $this->saveNewAlbumData();
            } elseif (isset($_POST['deleteAlbumForm'])) {
                $this->deleteAlbum($_POST['album_id']);
            } elseif (isset($_POST['deleteSongForm'])) {
                $this->deleteSong($_POST['song_id']);
            } elseif (isset($_POST['editAlbumForm'])) {
                $this->editAlbum();
            }

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    }
}

