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
<div>
<button onclick="newPlaylistPopup()">New Playlist</button>
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeNewPlaylistPopup()">&times;</span>
        <h2>New Playlist</h2> <br>

        <form action="<?php echo $controller->handleFormSubmit(); ?> " method="post" name="playlistForm">
            <label for="playlist_title">Title: </label>
            <input type="text" id="playlist_title" name="playlist_title"> <br><br>

            <input type="submit" value="Submit" name="playlistForm">
        </form>
    </div>
</div>


<button onclick="deletePlaylistPopup()">Delete Playlist</button>
<div id="popup2" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closedeletePlaylistPopup()">&times;</span>
        <h2>Delete Playlist</h2> <br>

        <form action="<?php echo $controller->handleFormSubmit(); ?>" method="post" name="deletePlaylistForm">
            <label for="playlist_title">Title: </label>
            <input type="text" id="playlist_title" name="playlist_title"> <br><br>

            <input type="submit" value="Submit" name="deletePlaylistForm">
        </form>
    </div>
</div>

<button onclick="updateUserInfo()">Update Information</button>
<div id="popup4" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeupdateUserInfo()">&times;</span>
        <h2>Update your info</h2> <br>

        <form action="<?php echo $controller->handleFormSubmit(); ?>" method="post" name="updateUser">
            <label for="user_id">Update Information: </label>
            <h3>username</h3>
            <input type="text" id="username" name="username"> <br><br>
            <h3>password</h3>
            <input type="text" id="password" name="password"> <br><br>
            <h3>first name</h3>
            <input type="text" id="first_name" name="first_name"> <br><br>
            <h3>last name</h3>
            <input type="text" id="last_name" name="last_name"> <br><br>
            <h3>email</h3>
            <input type="text" id="email" name="email"> <br><br>
            <input type="submit" value="Submit" name="updateUser">
        </form>
    </div>
</div>
<script type="text/javascript" src="js/homePage.js"></script>
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



</body>
</html>