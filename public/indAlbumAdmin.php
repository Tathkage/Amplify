<!--
File Creator: Tathluach Chol

File Description:
    This file creates the front end view for when an admin looks at an album and gets values from the back end
    to populate the elements on the page.

All Coding Sections: Tathluach Chol
-->

<?php
require_once '../src/controllers/indAlbumAdminController.php';

$controller = new indAlbumAdminController();

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
</div>
</body>
</html>