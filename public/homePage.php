<?php
require_once '../src/controllers/homePageController.php';

$controller = new homePageController();
$playlist = $controller->collectPlaylists(); // variables: playlist_title
$randomAlbums = $controller->collectRandomAlbums(); // variables: album_title, stage_name
$randomSongs = $controller->collectRandomSongs(); // song_title, listens, stage_name
?>
<!DOCTYPE html>
<html lang="">
    <head>
        <title>Amplify</title>
        <link rel="stylesheet" type="text/css" href="css/homePage.css">
        <link rel="icon" href="./images/amplifyIcon.png" type="image/x-icon">
    </head>
    <body>
    <header>
        <a href="homePage.php"><button>AMPLIFY</button></a>

        <nav>
            <ul class ="nav_links">
                <li><a href="artistPage.php">Artists</a></li>
                <li><a href="indAlbum.php">ind Album</a></li>
                <li><a href="index.php">Index</a></li>
                <li><a href="playlistcreation.php">Playlist Creation</a></li>
            </ul>
        </nav>
        <a class="cta" href="#"><button>Username</button></a>
    </header>
    <h1>BROWSE</h1>
        <div class = "Submission-Form">
                <div>
                    <!-- Add this HTML form to your homePage.php file -->
                    <form method="post">
                        <label for="song-search">Search for songs:</label>
                        <input type="text" id="song-search" name="song-search" required>
                        <button type="submit">Search</button>
                    </form>
                    <?php
                    // Check if form has been submitted
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Get search term from form
                        $searchTerm = $_POST["song-search"];

                        // Call searchSongs function to search for songs
                        $songs = $controller->searchSongs($searchTerm);

                        // Display search results
                        if (count($songs) > 0) {
                            echo "<h2>Search results for '{$searchTerm}'</h2>";
                            echo "<ul>";
                            foreach ($songs as $song) {
                                echo "<li>{$song['song_title']} by {$song['stage_name']}</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>No results found for '{$searchTerm}'</p>";
                        }
                    }
                    ?>
                </div>
                <div>
                    <!-- Add this HTML form to your homePage.php file -->
                    <form method="post">
                        <label for="album-search">Search for albums:</label>
                        <input type="text" id="album-search" name="album-search" required>
                        <button type="submit">Search</button>
                    </form>
                    <?php
                    // Check if form has been submitted
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Get search term from form
                        $searchTerm = $_POST["song-search"];

                        // Call searchAlbums function to search for albums
                        $albums = $controller->searchAlbums($searchTerm);

                        // Display search results
                        if (count($albums) > 0) {
                            echo "<h2>Search results for '{$searchTerm}'</h2>";
                            echo "<ul>";
                            foreach ($albums as $album) {
                                echo "<li>{$album['album_title']} by {$album['stage_name']}</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>No results found for '{$searchTerm}'</p>";
                        }
                    }
                    ?>
                </div>

            <div class = "child">
                <form class="form" action = "User_Info" method = 'post'>
                    <h2>Edit Your Info</h2>

                    <div class="form__item">
                        <label htmlFor = "name" class="form__label">Previous Username</label>
                        <input type = "text" name = "name" class="form__input" placeholder="Enter previous username">
                        <label htmlFor = "name" class="form__label">Username</label>
                        <input type = "text" name = "name" class="form__input" placeholder="Enter username">
                        <label htmlFor = "name" class="form__label">Password</label>
                        <input type = "text" name = "name" class="form__input" placeholder="Enter password">
                        <label htmlFor = "name" class="form__label">First Name</label>
                        <input type = "text" name = "name" class="form__input" placeholder="Enter first name">
                        <label htmlFor = "name" class="form__label">Last Name</label>
                        <input type = "text" name = "name" class="form__input" placeholder="Enter last name">
                        <label htmlFor = "name" class="form__label">Email</label>
                        <input type = "text" name = "name" class="form__input" placeholder="Enter email">
                        <br>
                        <input type="submit" value="Submit" class="form_submit">
                    </div>
                </form>
            </div>
        </div>

    </body>
</html>