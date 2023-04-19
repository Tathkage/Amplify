<?php

?>
<!DOCTYPE html>
<html lang="">
<head>
    <title>Amplify</title>
    <link rel="stylesheet" type="text/css" href="css/songStyle.css">
</head>
<body>
<h1>Amplify: Songs</h1>
<div class="song-container">
    <h2>Song Title</h2>
    <p><strong>Views:</strong> 1000 | <strong>Reviews:</strong> 5 | <strong>Release Date:</strong> April 20th, 2023</p>
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