<!--
File Creator: Tathluach Chol

File Description:
    This file creates the front end view for when an admin looks at an album and gets values from the back end
    to populate the elements on the page.

All Coding Sections: Tathluach Chol
-->

<?php
require_once '../src/controllers/indAlbumAdminController.php';

// Instantiate a new instance of the indAlbumAdminController class
$controller = new indAlbumAdminController();

// Get the album ID from the URL parameter
$album_id = $_GET['albumid'];
$user_id = $_GET['userid'];

// Get the album, reviews, songs, collaborators, and non-collaborators data from the back-end
$album = $controller->getAlbumInfo($album_id) ?? [];
$reviews = $controller->getAlbumReviews($album_id) ?? [];
$songs = $controller->getAlbumSongs($album_id) ?? [];
$collaborators = $controller->getAlbumCollaborators($album_id) ?? [];
$nonCollaborators = $controller->getNonCollaborators($album_id) ?? [];
$flaggedReviews = $controller->getFlaggedReviews() ?? [];
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
    <p><strong>Songs:</strong> 10 | <strong>Reviews:</strong> 5 | <strong>Release
            Date:</strong> <?php echo $album[1]; ?></p>

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

    <!-- Allow admins to add reviews through a form -->
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

    <!-- Display the list of collaborators for the album -->
    <h2>Album Collaborators</h2>
    <table>
        <thead>
        <tr>
            <th>Artist ID</th>
            <th>Stage Name</th>

        </tr>
        </thead>
        <tbody>

        <!-- Show potential album collaborators -->
        <?php foreach ($collaborators as $collaborator): ?>
            <tr>
                <td><?php echo $collaborator['artist_id']; ?></td>
                <td><?php echo $collaborator['stage_name']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Allow admins to add an artist as a collaborator through a form -->
    <h2>Add to Album</h2>
    <form action="<?php echo $controller->handleFormSubmit(); ?> " name="addCollaborator" method="post">
        <input type="hidden" name="album_id" value="<?php echo $album_id; ?>">
        <label for="noncollab_id">Artist Stage Name:</label>
        <select id="noncollab_id" name="noncollab_id">
            <?php foreach ($nonCollaborators as $nonCollaborator): ?>
                <option
                    value="<?= htmlspecialchars($nonCollaborator['artist_id']) ?>"><?= htmlspecialchars($nonCollaborator['stage_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Add">
    </form>

    <!-- Allow admins to remove an artist from the album through a form -->
    <h2>Remove from Album</h2>
    <form action="<?php echo $controller->handleFormSubmit(); ?>" name="removeCollaborator" method="post">
        <label for="collab_id">Artist Stage Name:</label>
        <select id="collab_id" name="collab_id">
            <?php foreach ($collaborators as $collaborator): ?>
                <option
                    value="<?= htmlspecialchars($collaborator['artist_id']) ?>"><?= htmlspecialchars($collaborator['stage_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Remove">
    </form>

</div>
</body>
</html>