<?php
// Including database connection code here
require_once "../config.php";
session_start();

// Define variables and initialize them to empty values
$email = $username = $password = $Contact_number = "";
$email_err = $username_err = $password_err = $Contact_number_err = "";

// Check if the form was submitted.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate user name
    if (empty(trim($_POST["member_name"]))) {
        $username_err = "Please enter your user name.";
    } else {
        $username = trim($_POST["member_name"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate phone number
    if (empty(trim($_POST["Contact_number"]))) {
        $Contact_number_err = "Please enter your phone number.";
    } else {
        $Contact_number = trim($_POST["Contact_number"]);
    }

    // Check input errors before inserting into the database
    if (empty($email_err) && empty($username_err) && empty($password_err) && empty($Contact_number_err)) {
        // Start a transaction
        mysqli_begin_transaction($link);

        // Prepare an insert statement for users table
        $sql_users = "INSERT INTO users (email, username, password, Contact_number) VALUES (?, ?, ?, ?)";
        if ($stmt_users = mysqli_prepare($link, $sql_users)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt_users, "ssss", $param_email, $param_username, $param_password, $param_Contact_number);

            // Set parameters
            $param_email = $email;
            $param_username = $username;
            // Store the password as plain text (not recommended for production)
            $param_password = $password;
            $param_Contact_number = $Contact_number;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt_users)) {
                // Commit the transaction
                mysqli_commit($link);

                // Registration successful, redirect to the login page
                header("location: register_process.php");
                exit;
            } else {
                // Rollback the transaction if there was an error
                mysqli_rollback($link);
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close the statement
            mysqli_stmt_close($stmt_users);
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
            margin: 0; /* Remove default margin */
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
          .background-video video 
          {
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

        .register-container a {
            text-decoration: none;
        }

        h1, h2, p {
            text-align: center;
            font-family: 'Montserrat', serif;
        }

        .form-group {
            margin-bottom: 15px; /* Add space between form elements */
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
    }

    .btn-dark{
            color: white;
           
        }
    </style>
    <?php
// ... existing PHP code ...
$email_err = $username_err = $password_err = $Contact_number_err = "";

// Check if the form was submitted.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... existing PHP code ...
} else {
    // Initialize error variables if the form was not submitted
    $email_err = $username_err = $password_err = $Contact_number_err = "";
}

?>
</head>
<body>
<div class="background-video">
    <video autoplay muted loop>
        <source src="../image/coffeebeans.mp4" type="video/mp4"></video></div>
    <div class="register-container">
        <a class="nav-link" href="../home/home.php#hero"> <h1 style="font-family:Copperplate; color:white;">Northside </h1><span class="sr-only"></span></a><br>
       
        <form action="register.php" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
                                <span class="text-danger"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group">
                <label> User Name</label>
                <input type="text" name="member_name" class="form-control" placeholder="Enter user Name" required>
                                <span class="text-danger"><?php echo $username_err; ?></span>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                                <span class="text-danger"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="Contact number" class="form-control" placeholder="Enter Phone Number" required>
                                <span class="text-danger"><?php echo $Contact_number_err; ?></span>
            </div>

            <button style="background-color:black;" class="btn-dark" type="submit" name="register" value="Register">Register</button>
        </form>

        <p style="margin-top:1em; color:white;">Already have an memberships? <a href="../customerLogin/login.php" >Proceed to Login</a></p>
    </div>
</body>
</html>
