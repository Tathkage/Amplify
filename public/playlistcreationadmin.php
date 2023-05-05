<?php
require_once '../src/controllers/homePageController.php';

$controller = new homePageController();

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

    <button onclick="newPlaylistPopup()">New Playlist</button>
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeNewPlaylistPopup()">&times;</span>
            <h2>New Playlist</h2> <br>

            <form action="<?php echo $controller->handleFormSubmit(); ?> " method="post" name="AdminPlaylistForm">
                <label for="playlist_title">Title: </label>
                <input type="text" id="playlist_title" name="playlist_title"> <br><br>

                <input type="submit" value="Submit" name="AdminPlaylistForm">
            </form>
        </div>
    </div>


<button onclick="deletePlaylistPopup()">Delete Playlist</button>
<div id="popup2" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closedeletePlaylistPopup()">&times;</span>
        <h2>Delete Playlist</h2> <br>

        <form action="<?php echo $controller->handleFormSubmit(); ?>" method="post" name="deletePlaylistAdmin">
            <label for="playlist_id">Playlist Id: </label>
            <input type="text" id="playlist_id" name="playlist_id"> <br><br>

            <input type="submit" value="Submit" name="deletePlaylistAdmin">
        </form>
    </div>
</div>


<button onclick="updatePlaylistPopup()">Update Playlist Ownership</button>
<div id="popup3" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeupdatePlaylistPopup()">&times;</span>
        <h2>Delete Playlist</h2> <br>

        <form action="<?php echo $controller->handleFormSubmit(); ?>" method="post" name="UpdateOwnership">
            <label for="playlist_id">Playlist Id and New User ID: </label>
            <input type="text" id="playlist_id" name="playlist_id"> <br><br>
            <input type="text" id="user_id" name="user_id"> <br><br>
            <input type="submit" value="Submit" name="UpdateOwnership">
        </form>
    </div>
</div>
<script type="text/javascript" src="js/homePage.js"></script>
</body>
</html>