a<?php
require_once '../src/controllers/artistPageController.php';
$controller = new artistPageController();

// Access the data array defined in artistPageController.php
$songs = $controller->collectSongs() ?? [];
$albums = $controller->collectAlbums() ?? [];
$nonAlbumSongs = $controller->collectNonAlbumSongs() ?? [];
$otherArtists = $controller->collectOtherArtists() ?? [];
$songCollaborators = $controller->showCollaborators($songs, "song");
$albumCollaborators = $controller->showCollaborators($albums, "album");
?>

<!DOCTYPE html>
<html lang="">
<head>
    <title>Artist Page</title>
    <link rel="stylesheet" type="text/css" href="css/artistPage.css">
    <link rel="icon" href="./images/amplifyIcon.png" type="image/x-icon">
</head>
<body>
<h1>Hello Artist</h1>
<div class="Artists-container">
    <a href="index.php"> Edit Artist </a>
    <br>

    <!-- pop up for new song -->
    <button onclick="newSongPopup()">New Song</button>
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeNewSongPopup()">&times;</span>
            <h2>New Song</h2>

            <!--  form for creating new song  -->
            <form action="<?php echo $controller->handleFormSubmit(); ?> " method="post">
                <label for="song_title">Title: </label>
                <input type="text" id="song_title" name="song_title"> <br><br>

                <label for="length">Length: </label>
                <input type="text" id="length" name="length" placeholder="HH:MM:SS"> <br><br>

                <!-- option selection for album -->
                <label for="album_id">Album: </label>
                <select id="album_id" name="album_id">
                    <option value="">No Album</option>
                    <?php foreach ($albums as $row): ?>
                        <option value="<?php echo $row['album_id']; ?>"><?php echo $row['album_title']; ?></option>
                    <?php endforeach; ?>
                </select> <br><br>

                <label for="release_date">Release Date: </label>
                <input type="date" id="release_date" name="release_date" min="YYYY-MM-DD" max="YYYY-MM-DD">
                <br><br>

                <!-- time selection for release time of song -->
                <label for="release_time">Release Time: </label>
                <select id="release_time" name="release_time">
                    <?php
                    $start_time = strtotime("9:00 AM");
                    $end_time = strtotime("10:00 PM");
                    $interval = 15 * 60; // 15 minutes

                    // loop through based on interval gap of 15 minutes
                    for ($i = $start_time; $i <= $end_time; $i += $interval) {
                        echo "<option value='" . date("H:i:s", $i) . "'>" . date("h:i A", $i) . "</option>";
                    }
                    ?>
                </select> <br><br>

                <!-- collaborator selection -->
                <label for="collaborators">Collaborators: </label><br>
                <select id="collaborators" name="collaborators[]" multiple>
                    <?php foreach ($otherArtists as $row): ?>
                        <option value="<?php echo $row['artist_id']; ?>"><?php echo $row['stage_name']; ?></option>
                    <?php endforeach; ?>
                </select> <br><br>

                <input type="submit" value="Submit" name="songForm">
            </form>
        </div>
    </div>

    <!-- table for songs -->
    <h2>Your Songs</h2>
    <table>
        <thead>
        <tr>
            <th>Title</th>
            <th>Album</th>
            <th>Collaborators</th>
            <th>Length</th>
            <th>Listens</th>
        </tr>
        </thead>
        <tbody>

        <!-- Loops through songs array along with songCollaborators to get needed information -->
        <?php foreach ($songs as $index => $row): ?>
            <tr>

                <td><?php echo $row['song_title']; ?></td>
                <td><?php if ($row['album_title']) {
                        echo $row['album_title'];
                    } else echo 'no album'
                    ?></td>
                <td><?php foreach ($songCollaborators[$index] as $name): ?>
                        <?php echo $name['stage_name']; ?> |
                    <?php endforeach; ?>
                </td>
                <td><?php echo $row['length']; ?></td>
                <td><?php echo $row['listens']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <br>


    <!-- popup for new album -->
    <button onclick="newAlbumPopup()">New Album</button>
    <div id="popup2" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeNewAlbumPopup()">&times;</span>
            <h2>New Album</h2>

            <!--  form for creating new album  -->
            <form action="<?php echo $controller->handleFormSubmit(); ?> " name="albumForm" method="post">
                <label for="album_title">Album Title: </label>
                <input type="text" id="album_title" name="album_title"> <br><br>

                <!-- Collaborator Selection -->
                <label for="collaborators">Collaborators: </label><br>
                <select id="collaborators" name="collaborators[]" multiple>
                    <?php foreach ($otherArtists as $row): ?>
                        <option value="<?php echo $row['artist_id']; ?>"><?php echo $row['stage_name']; ?></option>
                    <?php endforeach; ?>
                </select> <br><br>

                <!-- Form for adding new songs to album -->
                <div id="song-list">
                    <h3>Enter Songs: </h3>
                    <div class="song-entry">
                        <label for="song_title">Song Title: </label>
                        <input type="text" id="song_title" name="song_title[]">

                        <label for="length">Length: </label>
                        <input type="text" id="length" name="length[]"> <br><br>
                    </div>
                </div>
                <button type="button" id="add-song">Add Song</button>
                <br><br>

                <!-- Selection for adding previously created songs to album -->
                <label for="songs">Add Previously Created Songs?: </label> <br> <br>
                <select id="songs" name="songs[]" multiple>
                    <?php foreach ($nonAlbumSongs as $row): ?>
                        <option value="<?php echo $row['song_id']; ?>"><?php echo $row['song_title']; ?></option>
                    <?php endforeach; ?>
                </select> <br><br>

                <label for="release_date">Release Date: </label>
                <input type="date" id="release_date" name="release_date" min="YYYY-MM-DD" max="YYYY-MM-DD">
                <br><br>

                <!-- Release time selection -->
                <label for="release_time">Release Time: </label>
                <select id="release_time" name="release_time">
                    <?php
                    $start_time = strtotime("9:00 AM");
                    $end_time = strtotime("10:00 PM");
                    $interval = 15 * 60; // 15 minutes

                    // loop through based on interval gap of 15 minutes
                    for ($i = $start_time; $i <= $end_time; $i += $interval) {
                        echo "<option value='" . date("H:i:s", $i) . "'>" . date("h:i A", $i) . "</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Submit" name="albumForm">
            </form>
        </div>
    </div>

    <!-- table for albums -->
    <h2>Your Albums</h2>
    <table>
        <thead>
        <tr>
            <th>Title</th>
            <th>Collaborators</th>
            <th>Release Date</th>
            <th>Release Time</th>
        </tr>
        </thead>
        <tbody>

        <!-- Loops through albums and albumCollaborators to show needed information -->
        <?php foreach ($albums as $index => $row): ?>
            <tr>
                <td><?php echo $row['album_title']; ?></td>
                <td><?php foreach ($albumCollaborators[$index] as $name): ?>
                        <?php echo $name['stage_name']; ?> |
                    <?php endforeach; ?>
                </td>
                <td><?php echo $row['release_date']; ?></td>
                <td><?php echo $row['release_time']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <br>
</div>
<script type="text/javascript" src="js/artistPage.js"></script>
</body>
</html>
