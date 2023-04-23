<?php
require_once '../src/controllers/homePageController.php';

$controller = new homePageController();
$playlist = $controller->collectPlaylists();
$randomAlbums = $controller->collectRandomAlbums();
$randomSongs = $controller->collectRandomSongs();

?>
<!DOCTYPE html>
<html lang="">
    <head>
        <title>Amplify</title>
        <link rel="stylesheet" type="text/css" href="css/homePage.css">
    </head>
    <body>
    <header>
        <a href="homePage.php"><button>AMPLIFY</button></a>

        <nav>
            <ul class ="nav_links">
                <li><a href="artists.php">Artists</a></li>
                <li><a href="indAlbum.php">ind Album</a></li>
                <li><a href="index.php">Index</a></li>
            </ul>
        </nav>
        <a class="cta" href="#"><button>Username</button></a>
    </header>
    <h1>BROWSE</h1>
        <div class = "Submission-Form">
        <div class = "child">
            <form class="form" action = "query_song" method = 'post'>
                <h2>Search for a song</h2>

                <div class="form__item">
                    <label htmlFor = "name" class="form__label">Item Name</label>
                    <input type = "text" name = "name" class="form__input" placeholder="Enter song name">

                    <br>
                    <input type="submit" value="Submit" class="form_submit">
                </div>
            </form>
        </div>

            <div class = "child">
                <form class="form" action = "query_album" method = 'post'>
                    <h2>Search for an album</h2>

                    <div class="form__item">
                        <label htmlFor = "name" class="form__label">Item Name</label>
                        <input type = "text" name = "name" class="form__input" placeholder="Enter album name">

                        <br>
                        <input type="submit" value="Submit" class="form_submit">
                    </div>
                </form>
            </div>
        </div>
        <h3>Music For You</h3>
        <table class="content-table">
            <thead>
            <tr>
                <th>Song Name</th>
                <th>Song Artist</th>
                <th>Song Length</th>
            </tr>
            </thead>
            <tr>
                <tbody>
                <td>My song</td>
                <td>My Artist</td>
                <td>30 seconds</td>
                </tr>
            </tbody>
        </table>

        <button onclick="newPlaylistPopup()">New Playlist</button>
        <div id="popup" class="popup">
            <div class="popup-content">
                <span class="close" onclick="closeNewPlaylistPopup()">&times;</span>
                <h2>New Playlist</h2> <br>

                <!-- form for creating new playlist -->
                <form action="<?php echo $controller->handleFormSubmit(); ?> " method="post">
                    <label for="playlist_title">Title: </label>
                    <input type="text" id="playlist_title" name="playlist_title"> <br><br>

                    <input type="submit" value="Submit" name="playlistForm">
                </form>
            </div>
        </div>
        <script type="text/javascript" src="js/homePage.js"></script>
    </body>
</html>