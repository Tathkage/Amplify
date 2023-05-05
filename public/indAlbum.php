<!--
File Creator: Tathluach Chol

File Description:
    This file creates the front end view for when a user looks at an album and gets values from the back end
    to populate the elements on the page.

All Coding Sections: Tathluach Chol
-->

<?php
require_once '../src/controllers/indAlbumController.php';

// Instantiate a new instance of the indAlbumController class
$controller = new indAlbumController();

// Get the album ID from the URL parameter
$album_id = $_GET['albumid'];
$album_id = 38;

$user_id = $_GET['userid'];
$user_id = 3;

// Get the album, reviews, and songs data from the back-end
$album = $controller->getAlbumInfo($album_id) ?? [];
$reviews = $controller->getAlbumReviews($album_id) ?? [];
$songs = $controller->getAlbumSongs($album_id) ?? [];
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
    <h2><?php echo $album[0]; ?></h2>
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
    <form action="<?php echo $controller->handleFormSubmit(); ?>" name="reviewForm" method="post">
        <input type="hidden" name="album_id" value="<?php echo $album_id; ?>">
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
</div>
</body>
</html>