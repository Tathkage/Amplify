<?php

require_once '../src/config/config.php';

class indSongController
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

    // Disconnect from database
    public function disconnect()
    {
        $this->conn->close();
    }

    // Function to get default song for testing
    public function defaultSong()
    {
        $this->connect();

        // Collects all songs created by artist
        // song_id hard coded for now
        $sql = "SELECT songs.song_title, songs.listens, songs.length, songs.release_date, songs.release_time
            FROM songs
            WHERE songs.song_id = 79";

        $result = mysqli_query($this->conn, $sql);

        // Check if query execution was successful
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        // Store songs in array
        $song = array();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $song[] = $row['song_title'];
            $song[] = $row['listens'];
            $song[] = $row['length'];
            $song[] = $row['release_date'];
            $song[] = $row['release_time'];
        }
        $this->disconnect();
        return $song;
    }

    // Function to get default song for testing
    public function songReviews()
    {
        $this->connect();

        // Collects all songs created by artist
        // song_id hard coded for now
        $sql = "SELECT reviews.review_id, reviews.user_id, users.username, reviews.comment, reviews.rating
                FROM reviews
                JOIN users ON reviews.user_id = users.user_id
                WHERE reviews.song_id = 79";


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

    // Function to save information for new review
    function saveReviewData($review_id = 'default', $user_id = '100', $song_id ='NULL', $album_id ='/01/01/2022', $comment = '12:45:00', $rating = [], $albumCreation = false)
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
        mysqli_stmt_bind_param($songInput, 'siiss', $song_title, $length, $album_id, $release_date, $release_time);
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
}