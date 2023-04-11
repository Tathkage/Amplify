<?php
require_once '../src/controllers/indexController.php';

// Access the data array defined in indexController.php
$data = $data ?? [];

?>

<!DOCTYPE html>
<html lang="">
<head>
    <title>My Website</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h1>Welcome to My Website</h1>

<ul>
    <?php foreach ($data as $row): ?>
        <p><?php echo $row['user_id'] . '|   | ' . $row['username'] . '|   | ' . $row['password'] . '|   | ' . $row['first_name'] . '|   | ' . $row['last_name'] . '|   | ' . $row['email']; ?></p>
    <?php endforeach; ?>
</ul>

<script type="text/javascript" src="js/script.js"></script>
</body>
</html>