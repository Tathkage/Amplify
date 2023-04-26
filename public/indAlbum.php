<?php
require_once '../src/controllers/indAlbumController.php';

$controller = new indAlbumController();

// Access the data array defined in artistsController.php
$album = $controller->defaultAlbum() ?? [];
$reviews = $controller->albumReviews() ?? [];
$songs = $controller->albumSongs() ?? [];
?>
<!DOCTYPE html>
<html lang="">
<head>
    <title>Amplify</title>
    <link rel="stylesheet" type="text/css" href="css/albumStyle.css">
    <link rel="icon" href="./images/amplifyIcon.png" type="image/x-icon">
</head>
<body>
<h1>Amplify: Albums</h1>
<div class="album-container">
    <h2><?php echo $album[0]; ?></h2>
    <p><strong>Songs:</strong> 10 | <strong>Reviews:</strong> 5 | <strong>Release Date:</strong> <?php echo $album[1]; ?></p>
    <h2>Songs</h2>
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

    <button>Add Album to Playlist</button>
    <h2>Album Reviews</h2>
    <table>
        <thead>
        <tr>
            <th>Username</th>
            <th>Rating</th>
            <th>Review</th>
        </tr>
        </thead>
        <tbody>

        <!-- Loops through albums and albumCollaborators to show needed information -->
        <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?php echo $review['username']; ?></td>
                <td><?php echo $review['rating']; ?></td>
                <td><?php echo $review['comment']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

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
</div>
</body>
</html>