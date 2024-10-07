<?php
require_once "../config.php";
session_start();

$userId = $_SESSION['logged_user_id'];

// Initialize the username variable
$username = '';

// Query the database to get the user's username
$sql = "SELECT username FROM users WHERE user_id = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<title>User settings</title>
		<link rel="stylesheet" href="css/styles.css">
	</head>

	<body>
        <div class="container">

            <!--Navigation bar-->
            <header class="navbar">
                <div class="navhome">
                    <a href="index.php"><img src="logos/coffee_logo.png" id="coffee-logo"></a>
                    <a href="index.php"><h2>Northside Caf&eacute;</h2></a>
                </div>
                <div class="navlinks">
                    <div class="adminlinks">
                        <a href="settings.html"><img src="logos/settings.png"></a>
                        <a href="sales/all_sales.php"><img src="logos/analytics.png"></a>
                    </div>
                    <div class="generallinks">
                        <a href="sales/staff_sales.php"><img src="logos/print.png"></a>
                        <a href="user.php"><img src="logos/user.png"></a>
                    </div>
                </div>
            </header>

            <video autoplay muted loop id="user-settings-video">
                <source src="videos/beans.mp4" type="video/mp4">
            </video>

            <div class="user-settings">
                <div class="user-menu">
                    <img src="logos/white-userlogo.png" id="userlogo">
                    <!-- Display the user's username here -->
                    <h1>HELLO, <span class="needsbackend"><?php echo htmlspecialchars($username); ?>.</span></h1>
                    <a href="validatedetails.html" class="user-links">CHANGE PASSWORD</a>
                    <a href="validatedetails.html" class="user-links">MODIFY PERSONAL DETAILS</a>
                    <a href="login.html" class="action-button-link">
                        <button class="action-button">LOG OUT</button>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
