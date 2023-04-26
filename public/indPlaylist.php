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
        <label for="delete-song">Delete Song:</label>
        <select id="delete-song" name="delete_song">
            <?php foreach($songs as $song): ?>
                <option value="<?php echo $song['song_title']; ?>"><?php echo $song['song_title']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Delete">
    </form>
</div>
</body>
</html>