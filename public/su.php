<!--
File Creator: Irving Salinas

File Description: Takes care of users trying to create an account.


All Coding Sections: Irving Salinas
-->
<?php
require_once '../src/controllers/homePageController.php';
$controller = new homePageController();
$playlist = $controller->collectPlaylists(); // variables: playlist_title
$randomAlbums = $controller->collectRandomAlbums(); // variables: album_title, stage_name
$randomSongs = $controller->collectRandomSongs(); // song_title, listens, stage_name

?>
<!DOCTYPE html>
<html lang="">
<head>
    <title>Amplify</title>
    <link rel="stylesheet" type="text/css" href="css/homePage.css">
    <link rel="icon" href="./images/amplifyIcon.png" type="image/x-icon">
</head>
<body>
<div>
    <button onclick="updateUserInfo()">Create Account</button>
    <div id="popup4" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeupdateUserInfo()">&times;</span>
            <h2>Create Account</h2> <br>

            <form action="<?php echo $controller->handleFormSubmit(); ?>" method="post" name="newAccount">
                <label for="user_id">Update Information: </label>
                <h3>username</h3>
                <input type="text" id="username" name="username"> <br><br>
                <h3>password</h3>
                <input type="text" id="password" name="password"> <br><br>
                <h3>first name</h3>
                <input type="text" id="first_name" name="first_name"> <br><br>
                <h3>last name</h3>
                <input type="text" id="last_name" name="last_name"> <br><br>
                <h3>email</h3>
                <input type="text" id="email" name="email"> <br><br>
                <input type="submit" value="Submit" name="newAccount">
            </form>
        </div>
    </div>
    <script type="text/javascript" src="js/homePage.js"></script>



</body>
</html><?php