<!--
File Creator: Tathluach Chol

File Description:
    This file creates the front end view for when an admin looks at an album and gets values from the back end
    to populate the elements on the page.

All Coding Sections: Tathluach Chol
-->

<?php
require_once '../src/controllers/indAlbumAdminController.php';

// Instantiate a new instance of the indAlbumController class
$controller = new indAlbumAdminController();

// Get the album, reviews, and songs data from the back-end
$album = $controller->defaultAlbum() ?? [];
$reviews = $controller->getAlbumReviews() ?? [];
$songs = $controller->getAlbumSongs() ?? [];

// Get the album ID from the URL parameter
$album_id = $_GET['albumid'];
$album_id = 38;
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

    <!-- Display the album details on the page -->
    <h2><?php echo $album_id; ?></h2>
    <p><strong>Songs:</strong> 10 | <strong>Reviews:</strong> 5 | <strong>Release Date:</strong> <?php echo $album[1]; ?></p>

    <!-- Display the list of songs on the album -->
    <h2>Songs</h2>
    <table>
        <thead>
        <tr>
            <th>Song Title</th>
            <th>Length</th>
        </tr>
        </thead>
        <tbody>

        <!-- Loop through each song and display its title and length -->
        <?php foreach ($songs as $song): ?>
            <tr>
                <td><?php echo $song['song_title']; ?></td>
                <td><?php echo $song['length']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Display the list of reviews for the album -->
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

        <!-- Loop through each review and display the reviewer's username, rating, and comment -->
        <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?php echo $review['username']; ?></td>
                <td><?php echo $review['rating']; ?></td>
                <td><?php echo $review['comment']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Display a form to allow the user to submit a new review -->
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