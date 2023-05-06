<!--
File Creator: Rohan Dhawan

File Description:

    This file is for searching for playlists based on user_id's and playlist_id's while also displaying curated songs
    for the admin currently using this. This is mainly for admins to be able to verify if a combination of a playlist_id
    and user_id are valid and also tells the admin the name of the playlist.

All Coding Sections: Rohan Dhawan
-->
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
<div class="Submission-Form">
    <div>
        <form method="post">
            <label for="playlist-search">Search for playlists:</label>
            <input type="text" id="playlist-search" name="playlist-search" required>
            <label for="user-id">Enter your user ID:</label>
            <input type="text" id="user-id" name="user-id" required>
            <button type="submit">Search</button>
        </form>

        <?php
        // Check if form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get search term and user ID from form
            $searchTerm = $_POST["playlist-search"];
            $userId = $_POST["user-id"];

            // Call displayPlaylist function to search for playlists
            $playlists = $controller->displayPlaylist($searchTerm, $userId);

            // Display search results
            if (!empty($playlists)) {
                echo "<h2>Search results for '{$searchTerm}'</h2>";
                echo "<ul>";
                foreach ($playlists as $playlist) {
                    echo "<li>{$playlist['playlist_name']} by {$playlist['username']}</li>";
                }
                echo "</ul>";
            }
        }
        ?>
    </div>



    <div>
        <form method="not">
            <label for="album-search">Search for albums:</label>
            <input type="text" id="album-search" name="album-search" required>
            <button type="submit">Search</button>
        </form>
        <?php
        // Check if form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get search term from form
            $searchTerm = $_POST["album-search"];

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
            }
        }
        ?>
    </div>

    <div class = "child">
        <form class="form" action = "User_Info" method = 'not'>
            <h2>Edit Your Info</h2>

            <div class="form__item">
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
<h3>Music For You</h3>
<table class="content-table">
    <thead>
    <tr>
        <th>Song Name</th>
        <th>Song Artist</th>
        <th>Listens</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($randomSongs as $song): ?>
        <tr>
            <td><?= $song['song_title'] ?></td>
            <td><?= $song['stage_name'] ?></td>
            <td><?= $song['listens'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<h3>Albums For You</h3>
<table class="content-table">
    <thead>
    <tr>
        <th>Album Title</th>
        <th>Artist</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($randomAlbums as $album): ?>
        <tr>
            <td><?php echo $album['album_title']; ?></td>
            <td><?php echo $album['stage_name']; ?></td>
        </tr>
    <?php endforeach; ?>
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

            <input type="submit" value="Submit" name="adminPlaylistForm">
        </form>
    </div>
</div>
<script type="text/javascript" src="js/homePage.js"></script>


</body>
</html>