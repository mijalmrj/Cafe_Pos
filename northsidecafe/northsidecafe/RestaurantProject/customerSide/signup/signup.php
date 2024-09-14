<?php

    $servername = "localhost";
    $username = "root";
    $password = "";

    $database = "signup";

    // Create a connection
    $link = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if($link) {
        echo "Connected successfully";
    } else {
        die("Error: " . mysqli_connect_error());
    }
?>

<?php

$showAlert = false;
$showError = false;
$exists = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $email = $_POST["email"];

    // Check if username or email already exists in the 'users' table
    $sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($link, $sql);

    $num = mysqli_num_rows($result);

    if ($num == 0) {
        // Ensure passwords match
        if ($password == $cpassword) {
            // Hash the password
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the 'users' table
            $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hash', '$email')";
            $result = mysqli_query($link, $sql);

            if ($result) {
                $showAlert = true;
            }
        } else {
            $showError = "Passwords do not match!";
        }
    } else {
        $exists = "Username or email already exists!";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>

<?php
    if ($showAlert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Success!</strong> Your account has been created. You can now login.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
              </button></div>';
    }

    if ($showError) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error!</strong> ' . $showError . '
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
              </button></div>';
    }

    if ($exists) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error!</strong> ' . $exists . '
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
              </button></div>';
    }
?>

<div class="container my-4">
    <h1 class="text-center">Signup Here</h1>
    <form action="signup.php" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="cpassword">Confirm Password</label>
            <input type="password" class="form-control" id="cpassword" name="cpassword" required>
            <small id="emailHelp" class="form-text text-muted">Make sure to type the same password</small>
        </div>
        <button type="submit" class="btn btn-primary">SignUp</button>
    </form>
</div>

</body>

</html>
