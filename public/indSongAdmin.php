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

// Get the song ID from the URL parameter
$song_id = $_GET['songid'];

$user_id = $_GET['userid'];

// Get the song, reviews, and playlists data from the back-end
$song = $controller->getSongInfo($song_id) ?? [];
$reviews = $controller->getSongReviews($song_id) ?? [];
$flaggedReviews = $controller->getFlaggedReviews($song_id) ?? [];
$playlists = $controller->getUserPlaylists($user_id) ?? [];
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
    <h2><?php echo $song[0]; ?></h2>
    <p><strong>Views:</strong> <?php echo $song[1]; ?> | <strong>Reviews:</strong> 5 | <strong>Length:</strong> <?php echo $song[2]; ?> | <strong>Release Date:</strong> <?php echo $song[3]; ?> </p>

    <!-- Display reviews in a table -->
    <h2>Song Reviews</h2>
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

    <!-- Display flagged reviews in a table -->
    <h2>Flagged Song Reviews</h2>
    <table>
        <thead>
        <tr>
            <th>Review ID</th>
            <th>User ID</th>
            <th>Username</th>
            <th>Rating</th>
            <th>Review</th>
        </tr>
        </thead>
        <tbody>

        <!-- Loop through each flagged review and display its information -->
        <?php foreach ($flaggedReviews as $flaggedReview): ?>
            <tr>
                <td><?php echo $flaggedReview['review_id']; ?></td>
                <td><?php echo $flaggedReview['user_id']; ?></td>
                <td><?php echo $flaggedReview['username']; ?></td>
                <td><?php echo $flaggedReview['rating']; ?></td>
                <td><?php echo $flaggedReview['comment']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- A form to remove or update review from song -->
    <form action="<?php echo $controller->handleFormSubmit(); ?> " name="reviewChangeForm" method="post">
        <label for="review_id">Review ID:</label>
        <input type="text" name="review_id">
        <label for="review_comment">Review Comment:</label>
        <input type="text" name="review_comment">
        <input type="submit" name="change_review" value="Submit">
    </form>

    <!-- Allow users to add reviews through a form -->
    <h2>Add Review</h2>
    <form action="<?php echo $controller->handleFormSubmit(); ?>" name="reviewForm" method="post">
        <input type="hidden" name="song_id" value="<?php echo $song_id; ?>">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <label for="review">Review:</label>
        <textarea id="review" name="comment"></textarea>
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
    <form action="<?php echo $controller->handleFormSubmit(); ?>" name="playlistForm" method="post">
        <input type="hidden" name="song_id" value="<?php echo $song_id; ?>">
        <label for="playlist_id">Playlist Name:</label>
        <select id="playlist_id" name="playlist_id">
            <?php foreach ($playlists as $playlist): ?>
                <option value="<?= htmlspecialchars($playlist['playlist_id']) ?>"><?= htmlspecialchars($playlist['playlist_title']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="add_to_playlist" value="Add">
    </form>
</div>
</body>
</html>