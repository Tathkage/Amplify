<!--
File Creator: Tathluach Chol

File Description:
    This file creates the front end view for when an admin looks at a song and gets values from the back end
    to populate the elements on the page.

All Coding Sections: Tathluach Chol
-->

<?php
require_once '../src/controllers/indSongAdminController.php';

// Instantiate a new instance of the indSongController class
$controller = new indSongAdminController();

// Get the song, reviews, and playlistss data from the back-end
$song = $controller->getSongInfo() ?? [];
$reviews = $controller->getSongReviews() ?? [];
$playlists = $controller->getUserPlaylists() ?? [];

// Get the song ID from the URL parameter
$song_id = $_GET['songid'];

?>

<!DOCTYPE html>
<html lang="">
<head>
    <title>Amplify</title>
    <link rel="stylesheet" type="text/css" href="css/songStyle.css">
    <link rel="icon" href="./images/amplifyIcon.png" type="image/x-icon">
</head>
<body>
<h1>Amplify: Songs</h1>
<div class="song-container">

    <!-- Display the song details on the page -->
    <h2><?php echo $song_id; ?></h2>
    <p><strong>Views:</strong> <?php echo $song[1]; ?> | <strong>Reviews:</strong> 5 | <strong>Length:</strong> <?php echo $song[2]; ?> | <strong>Release Date:</strong> <?php echo $song[3]; ?> </p>
    <h2>Reviews</h2>

    <!-- Display reviews in a table -->
    <table>
        <thead>
        <tr>
            <th>Username</th>
            <th>Rating</th>
            <th>Review</th>
        </tr>
        </thead>
        <tbody>

        <!-- Loop through each review and display its information -->
        <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?php echo $review['username']; ?></td>
                <td><?php echo $review['rating']; ?></td>
                <td><?php echo $review['comment']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Allow users to add reviews through a form -->
    <h2>Add Review</h2>
    <form action="<?php echo $controller->handleFormSubmit(); ?> " name="reviewForm" method="post">
        <label for="review">Review:</label>
        <textarea id="review" name="comment"></textarea>
        <input type="hidden" id="rating" name="rating" value="">
        <div class="slider-rating-container">
            <label for="rating">Rating:</label>
            <select id="rating" name="rating">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <input type="submit" value="Submit">
    </form>

    <!-- Allow users to add the song to their playlists through a form -->
    <h2>Add to Playlist</h2>
    <form action="<?php echo $controller->handleFormSubmit(); ?> " name="playlistForm" method="post">
        <label for="playlist_id">Playlist Name:</label>
        <select id="playlist_id" name="playlist_id">
            <?php foreach ($playlists as $playlist): ?>
                <option value="<?= htmlspecialchars($playlist['playlist_title']) ?>"><?= htmlspecialchars($playlist['playlist_title']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Add">
    </form>
</div>
</body>
</html>