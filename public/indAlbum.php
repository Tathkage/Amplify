<?php

?>
<!DOCTYPE html>
<html lang="">
<head>
    <title>Amplify</title>
    <link rel="stylesheet" type="text/css" href="css/albumStyle.css">
</head>
<body>
<h1>Amplify: Albums</h1>
<div class="album-container">
    <h2>Album Title</h2>
    <p><strong>Songs:</strong> 10 | <strong>Reviews:</strong> 5 | <strong>Release Date:</strong> January 1, 2023</p>
    <h2>Songs</h2>
    <ul class="songs-list">
        <li>Song 1</li>
        <li>Song 2</li>
        <li>Song 3</li>
        <li>Song 4</li>
        <li>Song 5</li>
    </ul>

    <button>Add Album to Playlist</button>
    <h2>Album Reviews</h2>
    <ul class="reviews-list">
        <li> Review 1</li>
        <li> Review 2</li>
        <li> Review 3</li>
    </ul>

    <h2>Add Review</h2>
    <form method="post" action="add_review.php">
        <label for="review-text">Review:</label>
        <textarea id="review-text" name="review_text"></textarea>
        <input type="submit" value="Submit">
    </form>
</div>
</body>
</html>