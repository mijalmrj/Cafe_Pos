<?php
require_once "../config.php";
session_start();


// Initialize variables
$username = $email = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

// Fetch current user data
$sql = "SELECT username, email FROM users WHERE user_id = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $fetched_username, $fetched_email);
        mysqli_stmt_fetch($stmt);
        $username = $fetched_username;
        $email = $fetched_email;
    } else {
        echo "Oops! Something went wrong. Please try again later.";
        exit;
    }
    mysqli_stmt_close($stmt);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
        // Optionally, add more validation (e.g., regex)
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password (optional)
    if (!empty(trim($_POST["password"]))) {
        if (strlen(trim($_POST["password"])) < 6) {
            $password_err = "Password must have at least 6 characters.";
        } else {
            $password = trim($_POST["password"]);
            // Hash the new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }

        // Validate confirm password
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Please confirm the password.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "Passwords do not match.";
            }
        }
    }

    // Check input errors before updating the database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        // Prepare an update statement
        if (!empty(trim($_POST["password"]))) {
            // Update username, email, and password
            $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE user_id = ?";
        } else {
            // Update username and email only
            $sql = "UPDATE users SET username = ?, email = ? WHERE user_id = ?";
        }

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            if (!empty(trim($_POST["password"]))) {
                mysqli_stmt_bind_param($stmt, "sssi", $param_username, $param_email, $param_password, $param_id);
                $param_password = $hashed_password;
            } else {
                mysqli_stmt_bind_param($stmt, "ssi", $param_username, $param_email, $param_id);
            }

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_id = $user_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Update successful
                $_SESSION["username"] = $username;
                $_SESSION["email"] = $email;
                $success_message = "Account updated successfully.";
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account - Cashier Dashboard</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Link to external CSS -->
    <style>
        /* Reset some default browser styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            color: #333;
            line-height: 1.6;
        }

        /* Container */
        .container {
            width: 90%;
            max-width: 800px;
            margin: auto;
            overflow: hidden;
            padding: 20px 0;
        }

        /* Header Styling */
        header {
            background-color: #b5651d;
            color: #fff;
            padding: 20px 0;
            margin-bottom: 20px;
        }

        header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header h1 {
            font-size: 2em;
            letter-spacing: 2px;
        }

        nav ul {
            list-style: none;
            display: flex;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1em;
            padding: 5px 10px;
            transition: background-color 0.3s ease, color 0.3s ease;
            border-radius: 4px;
        }

        nav ul li a:hover {
            background-color: #fff;
            color: #b5651d;
        }

        /* Main Content Styling */
        main .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        main h2 {
            margin-bottom: 20px;
            font-size: 1.8em;
            color: #b5651d;
        }

        /* Form Styling */
        form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .error {
            color: #b5651d;
            margin-top: 5px;
            font-size: 0.9em;
        }

        .success {
            color: green;
            margin-bottom: 15px;
            font-size: 1em;
        }

        /* Submit Button */
        .btn-submit {
            background-color: #b5651d;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #a04d0b;
        }

        /* Footer Styling */
        footer {
            background-color: #b5651d;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
        }

        footer p {
            font-size: 1em;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header .container {
                flex-direction: column;
                align-items: flex-start;
            }

            nav ul {
                flex-direction: column;
                width: 100%;
            }

            nav ul li {
                margin: 10px 0;
            }

            main .container {
                padding: 20px;
            }

            .summary h3 {
                font-size: 1.3em;
            }

            .summary p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Cashier Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="sale_management.php">Sale Management</a></li>
                    <li><a href="report.php">Report</a></li>
                    <li><a href="update_account.php">Update Account</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Update Account</h2>

            <?php 
            if (!empty($success_message)) {
                echo '<div class="success">' . $success_message . '</div>';
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    <span class="error"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <span class="error"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <label>New Password (leave blank to keep current password)</label>
                    <input type="password" name="password">
                    <span class="error"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password">
                    <span class="error"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn-submit" value="Update Account">
                </div>
            </form>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Northside Café</p>
        </div>
    </footer>
</body>
</html>
