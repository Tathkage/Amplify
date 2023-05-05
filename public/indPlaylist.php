<!--
File Creator: Tathluach Chol

File Description:
    This file creates the front end view for when a user looks at a playlist and gets values from the back end
    to populate the elements on the page.

All Coding Sections: Tathluach Chol
-->

<?php
require_once '../src/controllers/indPlaylistController.php';

// Instantiate a new instance of the indPlaylistController class
$controller = new indPlaylistController();

// Get the playlist ID from the URL parameter
$playlist_id = $_GET['playlistid'];
$playlist_id = 7;

// Get the playlist and songs data from the back-end
$playlist = $controller->getPlaylistInfo($playlist_id) ?? [];
$songs = $controller->getPlaylistSongs($playlist_id) ?? [];
?>

<!DOCTYPE html>
<html lang="">
<head>
    <title>Amplify</title>
    <link rel="stylesheet" type="text/css" href="css/playlistStyle.css">
    <link rel="icon" href="./images/amplifyIcon.png" type="image/x-icon">
</head>
<body>
<h1>Amplify: Playlists</h1>
<div class="playlist-container">

    <!-- Display the playlist details on the page -->
    <h2><?php echo $playlist[2]; ?></h2>
    <!-- A form to edit the name of a playlist -->
    <form action="<?php echo $controller->handleFormSubmit(); ?> " name="playlistNameChangeForm" method="post">
        <input type="hidden" name="playlist_id" value="<?php echo $playlist_id; ?>">
        <label for="playlist_name">New Playlist Name:</label>
        <input type="text" name="playlist_name">
        <input type="submit" value="Submit">
    </form>
    <p><strong>Songs:</strong> 3 | <strong>Length:</strong> 3 Minutes</p>

    <!-- Display the list of songs on the playlist -->
    <h2>Playlist Songs</h2>
    <table>
        <thead>
        <tr>
            <th>Song Title</th>
            <th>Length</th>
        </tr>
        </thead>
        <tbody>

        <!-- Loop through the list of songs and display their titles and lengths -->
        <?php foreach ($songs as $song): ?>
            <tr>
                <td><?php echo $song['song_title']; ?></td>
                <td><?php echo $song['length']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- A form to delete a selected song from the playlist -->
    <h2>Edit Playlist</h2>
    <form action="<?php echo $controller->handleFormSubmit(); ?> " name="songDeleteForm" method="post">
        <label for="delete-song">Delete Song:</label>
        <select id="delete-song" name="delete_song">
            <?php foreach($songs as $song): ?>
                <option value="<?php echo $song['song_title']; ?>"><?php echo $song['song_title']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="selected_song" id="selected-song" value="">
        <input type="submit" value="Delete" onclick="handleSubmit()">
    </form>

    <!-- A Javascript function to assign the selected song's title to the hidden input field -->
    <script>
        function handleSubmit() {
            var select = document.getElementById("delete-song");
            var selectedSong = select.options[select.selectedIndex].value;
            document.getElementById("selected-song").value = selectedSong;
        }
    </script>
</div>
</body>
</html>