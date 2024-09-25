<?php
require_once "../config.php";

$email = $password = $username = $contact_number = "";
$email_err = $password_err = $username_err = $contact_number_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a user name.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["Contact_number"]))) {
        $contact_number_err = "Please enter a contact number.";
    } else {
        $contact_number = trim($_POST["Contact_number"]);
    }

    if (empty($email_err) && empty($password_err) && empty($username_err) && empty($contact_number_err)) {
        // SQL statement to register user as a customer
        $sql = "INSERT INTO users (email, password, username, contact_number, role) VALUES (?, ?, ?, ?, 'customer')";
    
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind parameters to the prepared statement
            mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_password, $param_username, $param_contact_number);
    
            // Assign parameters
            $param_email = $email;
            $param_password = $password;  // Use plain text password
            $param_username = $username;
            $param_contact_number = $contact_number;
    
            if (mysqli_stmt_execute($stmt)) {
                echo "Registration successful.";
            } else {
                echo "Something went wrong. Please try again.";
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
    <title>Registration Form</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color:black;
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

        .register-container {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            padding: 20px;
            width: 400px;
            max-width: 90%;
        }

        h1, h2, p {
            text-align: center;
            font-family: 'Montserrat', serif;
        }

        .form-group {
            margin-bottom: 15px; 
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: black;
            color: white;
        }
    </style>
</head>
<body>
<div class="background-video">
    <video autoplay muted loop>
        <source src="../image/coffeebeans.mp4" type="video/mp4">
    </video>
</div>
    <div class="register-container">
        <a class="nav-link" href="../home/home.php#hero">
            <h1 style="font-family:Copperplate; color:white;">Northside </h1>
        </a><br>
       
        <form action="register.php" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
                <span class="text-danger"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group">
                <label>User Name</label>
                <input type="text" name="username" class="form-control" placeholder="Enter User Name" required>
                <span class="text-danger"><?php echo $username_err; ?></span>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                <span class="text-danger"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="Contact_number" class="form-control" placeholder="Enter Phone Number" required>
                <span class="text-danger"><?php echo $contact_number_err; ?></span>
            </div>

            <button type="submit" name="register" value="Register">Register</button>
        </form>

        <p style="margin-top:1em; color:white;">Already have an account? <a href="../customerLogin/login.php">Proceed to Login</a></p>
    </div>
</body>
</html>
