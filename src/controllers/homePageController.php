<!--
File Creator: Rohan Dhawan

File Description:

    This file takes care of all controller functions for the playlistcreation.php, playlistcreationadmin.php, homePage.php,
    homePageAdmin.php. All types of queries are utilised, SELECT, UPDATE, INSERT, and DELETE are all queries that are
    integral to the design of the database.

Majority Coding Sections: Rohan Dhawan
-->
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

        $sql = "SELECT * FROM song_names";

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
    function deletePlaylist() {
        $this->connect();
        $playlist_title = $_POST['playlist_title'];
        if (empty($playlist_title)) {
            return;
        }
        $deleteQuery = mysqli_prepare($this->conn, 'DELETE FROM playlists WHERE user_id = ? AND playlist_title = ?');
        mysqli_stmt_bind_param($deleteQuery, 'is', $this->user_id, $playlist_title);
        mysqli_stmt_execute($deleteQuery);
        $num_rows_deleted = mysqli_stmt_affected_rows($deleteQuery);
        mysqli_stmt_close($deleteQuery);

        if ($num_rows_deleted > 0) {
            echo "Playlist '{$playlist_title}' deleted successfully!";
        } else {
            echo "No playlists found with title '{$playlist_title}' for user '{$this->user_id}'.";
        }

        $this->disconnect();

    }
    function deletePlaylistId()
    {
        $this->connect();
        $playlist_id = $_POST['playlist_id'];
        if (empty($playlist_id)) {
            return;
        }
        $deleteQuery = mysqli_prepare($this->conn, 'DELETE FROM playlists WHERE playlist_id = ?');
        mysqli_stmt_bind_param($deleteQuery, 'i', $playlist_id);
        mysqli_stmt_execute($deleteQuery);
        $num_rows_deleted = mysqli_stmt_affected_rows($deleteQuery);
        mysqli_stmt_close($deleteQuery);

        if ($num_rows_deleted > 0) {
            echo "Playlist with ID '{$playlist_id}' deleted successfully!";
        } else {
            echo "No playlists found with ID '{$playlist_id}'.";
        }

        $this->disconnect();
    }
    function saveAllPlaylistData()
    {
        $this->connect();

        $playlist_title = $_POST['playlist_title'];


        if (empty($playlist_title)) {
            return;
        }

        $userIdsQuery = mysqli_query($this->conn, "SELECT user_id FROM users");

        while ($row = mysqli_fetch_assoc($userIdsQuery)) {
            $user_id = $row['user_id'];
            $playlistInput = mysqli_prepare($this->conn, '  INTO playlists (user_id, playlist_title) VALUES (?,?)');
            mysqli_stmt_bind_param($playlistInput, 'is', $user_id, $playlist_title);
            mysqli_stmt_execute($playlistInput);
            mysqli_stmt_close($playlistInput);
        }

        mysqli_free_result($userIdsQuery);

        $this->disconnect();

    }

    public function updatePlaylistUserId() {
        $this->connect();
        $playlist_id = $_POST['playlist_id'];
        $user_id = $_POST['user_id'];
        $updateQuery = mysqli_prepare($this->conn, 'UPDATE playlists SET user_id = ? WHERE playlist_id = ?');
        mysqli_stmt_bind_param($updateQuery, 'ii', $user_id, $playlist_id);
        mysqli_stmt_execute($updateQuery);
        $num_rows_updated = mysqli_stmt_affected_rows($updateQuery);
        mysqli_stmt_close($updateQuery);

        $this->disconnect();

        return $num_rows_updated > 0;
    }
    public function updateUserInfo() {
        $this->connect();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $user_id = 2;
        $updateQuery = mysqli_prepare($this->conn, 'UPDATE users SET username = ?, password = ?, first_name = ?, last_name = ?, email = ? WHERE user_id = ?');
        mysqli_stmt_bind_param($updateQuery, 'sssssi', $username, $password, $first_name, $last_name, $email, $user_id);
        mysqli_stmt_execute($updateQuery);
        $num_rows_updated = mysqli_stmt_affected_rows($updateQuery);
        mysqli_stmt_close($updateQuery);

        $this->disconnect();

        return $num_rows_updated > 0;
    }
    public function newAccount() {
        $this->connect();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];

        $insertQuery = mysqli_prepare($this->conn, 'INSERT INTO users (username, password, first_name, last_name, email) VALUES (?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($insertQuery, 'sssss', $username, $password, $first_name, $last_name, $email);
        mysqli_stmt_execute($insertQuery);
        $num_rows_inserted = mysqli_stmt_affected_rows($insertQuery);
        mysqli_stmt_close($insertQuery);

        $this->disconnect();

        return $num_rows_inserted > 0;
    }

    // function to handle which action to take based on form version
    public function handleFormSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['playlistForm'])) {
                $this->saveNewPlaylistData();
            }
            if (isset($_POST['AdminPlaylistForm'])) {
                $this->saveAllPlaylistData();
            }if (isset($_POST['deletePlaylistForm'])) {
                $this->deletePlaylist();
            }
            if (isset($_POST['deletePlaylistAdmin'])) {
                $this->deletePlaylistId();
            }
            if(isset($_POST['UpdateOwnership'])){
                $this->updatePlaylistUserId();
            }
            if(isset($_POST['updateUser'])){
                $this->updateUserInfo();
            }
            if(isset($_POST['newAccount'])){
                $this->newAccount();
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