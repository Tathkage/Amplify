<?php
require_once '../src/controllers/indPlaylistController.php';

$controller = new indPlaylistController();

// Access the data array defined in artistsController.php
$playlist = $controller->defaultPlaylist() ?? [];
$songs = $controller->playlistSongs() ?? [];
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
    <h2><?php echo $playlist[2]; ?></h2>
    <p><strong>Songs:</strong> 3 | <strong>Length:</strong> 3 Minutes</p>
    <h2>Playlist Songs</h2>
    <table>
        <thead>
        <tr>
            <th>Song Title</th>
            <th>Length</th>
        </tr>
        </thead>
        <tbody>

        <!-- Loops through albums and albumCollaborators to show needed information -->
        <?php foreach ($songs as $song): ?>
            <tr>
                <td><?php echo $song['song_title']; ?></td>
                <td><?php echo $song['length']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <h2>Edit Playlist</h2>
    <form method="post" action="edit_playlist.php">
        <label for="add-song">Add Song:</label>
        <input type="text" id="add-song" name="add_song">
        <input type="submit" value="Add">
        <label for="delete-song">Delete Song:</label>
        <select id="delete-song" name="delete_song">
            <option value="Song 1">Song 1</option>
            <option value="Song 2">Song 2</option>
            <option value="Song 3">Song 3</option>
        </select>
        <input type="submit" value="Delete">
    </form>
</div>
</body>
</html>