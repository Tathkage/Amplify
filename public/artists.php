<?php
require_once '../src/controllers/artistsController.php';;

// Access the data array defined in indexController.php
$songs = $songs ?? [];
$albums = $albums ?? [];
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
<a href = "index.php"> New song </a>

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
