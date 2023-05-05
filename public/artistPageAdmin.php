<!--
File Creator: Wayland Moody

File Description:

    This file is the front end view for the Artist Page (Admin access). It allows the admin certain functionalities like viewing songs, and albums as well as
    delete songs and album etc. The page receives its information from the artistPageAdminController file to
    populate database elements on the page.

All Coding Sections: Wayland Moody
-->

<?php
require_once '../src/controllers/artistPageAdminController.php';
$controller = new artistPageAdminController();

// collection of needed variables
$admin_name = $controller->collectAdminName()[0];
$albums = $controller->collectAlbums() ?? [];
$allArtists = $controller->collectAllArtists() ?? [];
$songs = $controller->collectAllSongs() ?? [];
$songArtists = $controller->showCollaborators($songs, "song");
$albumArtists = $controller->showCollaborators($albums, "album");
$nonAlbumSongs = $controller->collectNonAlbumSongs() ?? [];

?>

<!DOCTYPE html>
<html lang="">
<head>
    <title>Artist Page</title>
    <link rel="stylesheet" type="text/css" href="css/artistPageAdmin.css">
    <link rel="icon" href="./images/amplifyIcon.png" type="image/x-icon">
</head>

<body>
<h1>Hello <?php echo $admin_name['username']; ?></h1>
<div class="Admin-container">

    <a href="http://localhost:81/homePage.php">Back To Home</a> <br><br>
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
                <label for="Artists">Artists: </label><br>
                <select id="artists" name="artists[]" multiple>
                    <?php foreach ($allArtists as $row): ?>
                        <option value="<?php echo $row['artist_id']; ?>"><?php echo $row['stage_name']; ?></option>
                    <?php endforeach; ?>
                </select> <br><br>

                <input type="submit" value="Submit" name="songForm">
            </form>
        </div>
    </div>

    <!-- table for songs -->
    <h2>All Songs</h2>
    <table>
        <thead>
        <tr>
            <th>Title</th>
            <th>Album</th>
            <th>Artists</th>
            <th>Length</th>
            <th>Listens</th>
            <th>Release Date</th>
            <th>Release time</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>

        <!-- Loops through songs array along with songCollaborators to get needed information -->
        <?php foreach ($songs as $index => $row): ?>
            <tr>

                <!-- link allows you to pass id to next page -->
                <td>
                    <a href="http://localhost:81/indSongAdmin.php?songid=<?php echo $row['song_id']; ?>"><?php echo $row['song_title']; ?></a>
                </td>
                <td><?php if ($row['album_title']) {
                        echo $row['album_title'];
                    } else echo 'no album'
                    ?></td>
                <td><?php foreach ($songArtists[$index] as $name): ?>
                        <?php echo $name['stage_name']; ?> |
                    <?php endforeach; ?>
                </td>
                <td><?php echo $row['length']; ?></td>
                <td><?php echo $row['listens']; ?></td>
                <td><?php echo $row['release_date']; ?></td>
                <td><?php echo $row['release_time']; ?></td>
                <td>
                    <form action="<?php echo $controller->handleFormSubmit(); ?> " name="deleteSongForm" method="post">
                        <input type="hidden" name="song_id" value="<?php echo $row['song_id']; ?>">
                        <input type="submit" value="Delete Song" name="deleteSongForm">
                    </form>
                </td>
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
                <label for="albumArtists">Artists: </label><br>
                <select id="albumArtists" name="albumArtists[]" multiple>
                    <?php foreach ($allArtists as $row): ?>
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
    <h2>All Albums</h2>
    <table>
        <thead>
        <tr>
            <th>Title</th>
            <th>Artists</th>
            <th>Release Date</th>
            <th>Release Time</th>
            <th>Delete</th>
            <th>Edit</th>
        </tr>
        </thead>
        <tbody>

        <!-- Loops through albums and albumCollaborators to show needed information -->
        <?php foreach ($albums as $index => $row): ?>
            <tr>
                <!-- link allows you to pass id to next page -->
                <td>
                    <a href="http://localhost:81/indAlbumAdmin.php?albumid=<?php echo $row['album_id']; ?>"><?php echo $row['album_title']; ?></a>
                </td>
                <td><?php foreach ($albumArtists[$index] as $name): ?>
                        <?php echo $name['stage_name']; ?> |
                    <?php endforeach; ?>
                </td>
                <td><?php echo $row['release_date']; ?></td>
                <td><?php echo $row['release_time']; ?></td>
                <td>
                    <form action="<?php echo $controller->handleFormSubmit(); ?> " name="deleteAlbumForm" method="post">
                        <input type="hidden" name="album_id" value="<?php echo $row['album_id']; ?>">
                        <input type="submit" value="Delete Album" name="deleteAlbumForm">
                    </form>
                </td>
                <td>
                    <button onclick="editAlbumPopup(<?php echo $row['album_id']; ?>)">Edit Album Information</button>
                    <div id="id<?php echo $row['album_id']; ?>" class="popup">
                        <div class="popup-content">
                            <span class="close"
                                  onclick="closeEditAlbumPopup(<?php echo $row['album_id']; ?>)">&times;</span>
                            <h2>Edit Album</h2>

                            <!--  form for creating new album  -->
                            <form action="<?php echo $controller->handleFormSubmit(); ?> " name="editAlbumForm"
                                  method="post">

                                <label for="release_date">Release Date: </label>
                                <input type="date" id="release_date" name="release_date" min="YYYY-MM-DD"
                                       max="YYYY-MM-DD" value="<?php echo $row['release_date'] ?>">
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
                                </select> <br><br>

                                <label for="album_title">Album Title: </label>
                                <input type="text" id="album_title" name="album_title"
                                       value="<?php echo $row['album_title'] ?>"> <br><br>

                                <input type="hidden" name="album_id" value="<?php echo $row['album_id']; ?>">

                                <input type="submit" value="Submit" name="editAlbumForm">
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <br>

</div>
<script type="text/javascript" src="js/artistPageAdmin.js"></script>
</body>
</html>
