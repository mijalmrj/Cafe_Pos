<?php
// Include your database connection code here
require_once "../config.php";
session_start(); // Start the session

// Define variables for email and password
$email = $password = "";
$email_err = $password_err = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before checking authentication
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement to fetch user_id, password, and role
        $sql = "SELECT user_id, password, role FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Get the result
                $result = mysqli_stmt_get_result($stmt);

                // Check if a matching record was found
                if (mysqli_num_rows($result) == 1) {
                    // Fetch the result row
                    $row = mysqli_fetch_assoc($result);

                    if ($password === $row["password"]) {
                        // Password is correct, start a new session and store the necessary user details in the session
                        $_SESSION["loggedin"] = true;
                        $_SESSION["user_id"] = $row['user_id'];
                        $_SESSION["email"] = $email;
                        $_SESSION["role"] = $row['role']; // Store role in session

                        // Redirect to the menu page
                        header("location: menu.php");
                        exit;
                    } else {
                        // Password is incorrect
                        $password_err = "Invalid password. Please try again.";
                    }
                } else {
                    // No matching records found
                    $email_err = "No account found with this email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0; /* Remove default margin */
            background-color: black;
            background-image: url('../image/loginBackground.jpg'); /* Set the background image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
        }
        .background-video { 
            position: fixed;
            top: 0;
            left: 0; 
            width: 100%;
            height: 100%; 
            z-index: -1; 
            overflow: hidden;
        }
        .background-video video {
            position: absolute;
            top: 50%;
            left: 50%; 
            min-width: 100%;
            min-height: 100%; 
            transform: translate(-50%, -50%); 
            object-fit: cover;
        }
        .login_wrapper {
            width: 400px; /* Adjust the container width as needed */
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            font-family: 'Montserrat', serif;
        }
        p {
            font-family: 'Montserrat', serif;
        }
        .form-group {
            margin-bottom: 15px; /* Add space between form elements */
        }
        ::placeholder {
            font-size: 12px; /* Adjust the font size as needed */
        }
        .text-danger {
            font-size: 13px;
            color: red;
        }
        .btn {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #444;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="email"],
        input[type="password"] {
            width: 95%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .nav-link {
            text-decoration: none;
            color: black;
            text-align: center;
        }
        .register-link {
            text-decoration: none;
            color: black;
            text-align: center;
            display: block;
        }
    </style>
</head>
<body>
<div class="background-video">
    <video autoplay muted loop>
        <source src="../image/coffeebeans.mp4" type="video/mp4">
    </video>
</div>
<div class="login-container">
    <div class="login_wrapper">
        <a class="nav-link" href="../home/home.php#hero">
            <h1 class="text-center" style="font-family:Copperplate;">Northside Cafe</h1>
        </a>
        <div class="wrapper">
            <form action="login.php" method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter User Email" required>
                    <span class="text-danger"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter User Password" required>
                    <span class="text-danger"><?php echo $password_err; ?></span>
                </div>
                <button class="btn" type="submit">Login</button>
            </form>
            <p style="margin-top:1em;">
                <a href="register.php" class="register-link">Don't have an account? Proceed to Register</a>
            </p>
            <p style="margin-top:1em;">
                <a href="reset-password.php" class="register-link">Forget Password</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
