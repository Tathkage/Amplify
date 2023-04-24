<?php
require_once '../src/controllers/indSongController.php';
$controller = new indSongController();

// Access the data array defined in artistsController.php
$song = $controller->defaultSong() ?? [];
$reviews = $controller->songReviews() ?? [];

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
    <h2><?php echo $song[0]; ?></h2>
    <p><strong>Views:</strong> <?php echo $song[1]; ?> | <strong>Reviews:</strong> 5 | <strong>Release Date:</strong> <?php echo $song[2]; ?> </p>
    <h2>Reviews</h2>
    <ul>
        <li>Review 1</li>
        <li>Review 2</li>
        <li>Review 3</li>
        <!-- Add more reviews here -->
    </ul>
    <h2>Add Review</h2>
    <form method="post" action="add_review.php">
        <label for="review-text">Review:</label>
        <textarea id="review-text" name="review_text"></textarea>
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
    <h2>Add to Playlist</h2>
    <form method="post" action="add_to_playlist.php">
        <label for="playlist-name">Playlist Name:</label>
        <input type="text" id="playlist-name" name="playlist_name">
        <input type="submit" value="Add">
    </form>
</div>
</body>
</html>