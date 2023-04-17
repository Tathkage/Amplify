<?php
require_once '../src/controllers/artistsController.php';
$controller = new artistsController();

// Access the data array defined in artistsController.php
$songs = $controller->collectSongs() ?? [];
$albums = $controller->collectAlbums() ?? [];
?>

<!DOCTYPE html>
<html lang="">
<head>
    <title>Artist Page</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h1>Hello Artist</h1>
<!-- link to new album (right now it is back to index page)-->
<a href = "index.php"> Edit Artist </a>
<br>
<!-- link to new song (right now it is back to index page)-->

<br>

<button onclick="openPopup()">New Song</button>
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <h2>New Song</h2>

        <!--  form for creating new song  -->
        <form onsubmit="return <?php echo $controller->handleFormSubmit(); ?> " method="post">
            <label for="song_title">Title: </label>
            <input type="text" id="song_title" name="song_title"> <br><br>

            <label for="length">Length: </label>
            <input type="text" id="length" name="length"> <br><br>

            <label for="album_id">Album: </label>
            <select id="album_id" name="album_id">
                <?php foreach ($albums as $row): ?>
                    <option value="<?php echo $row['album_id']; ?>"><?php echo $row['album_title']; ?></option>
                <?php endforeach; ?>
            </select> <br><br>

            <label for="release_date">Release Date: </label>
            <input type="date" id="release_date" name="release_date" min="YYYY-MM-DD" max="YYYY-MM-DD">
            <br><br>

            <label for="release_time">Release Time: </label>
            <select id="release_time" name="release_time">
                <?php
                $start_time = strtotime("9:00 AM");
                $end_time = strtotime("10:00 PM");
                $interval = 15 * 60; // 15 minutes

                // loop through based on interval gap of 15 minutes
                for ($i = $start_time; $i <= $end_time; $i += $interval) {
                    echo "<option value='" . date("HH:ii:ss", $i) . "'>" . date("h:i A", $i) . "</option>";
                }
                ?>
            </select>

            <input type="submit" value="Submit">
        </form>
    </div>
</div>

<!-- table for songs -->
<h2>Your Songs</h2>
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Listens</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($songs as $row): ?>
            <tr>
                <td><?php echo $row['song_title']; ?></td>
                <td><?php echo $row['listens']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>

<!-- link to new album (right now it is back to index page)-->
<a href = "index.php"> New album </a>

<!-- table for albums -->
<h2>Your Albums</h2>
<table>
    <thead>
    <tr>
        <th>Title</th>
        <th>Release Date</th>
        <th>Release Time</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($albums as $row): ?>
        <tr>
            <td><?php echo $row['album_title']; ?></td>
            <td><?php echo $row['release_date']; ?></td>
            <td><?php echo $row['release_time']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br>

<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
