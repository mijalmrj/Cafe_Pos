<?php
// Include database configuration
require_once '../../config.php'; 


// If we were to deploy the Northside Cafe POS system, 
// this key would be stored securely with AWS Secrets Manager or in the environment variables.
// but to be able to send this project to the CPRO teachers, we are hardcoding it for simplicity.
// This would not be safe practice in a real-world scenario.
$encryption_key = 'e954bdd837af6f46fdd159c53285c8e19002d1b0b2e49c301379e0a8a9df7601'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieving and sanitizing the form inputs
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = mysqli_real_escape_string($link, $_POST['password']);
    $role = mysqli_real_escape_string($link, $_POST['role']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $contact_number = mysqli_real_escape_string($link, $_POST['contact_number']);

    // Hashing the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // We dynamically generate a secure random IV for AES encryption
    $encryption_iv = random_bytes(16); 

    // Then we encrypt the email using AES-128-CTR with the encryption IV
    $encrypted_email = openssl_encrypt($email, "AES-128-CTR", $encryption_key, 0, $encryption_iv);

    // After that, IV is converted to hexadecimal for storage in the database
    $iv_hex = bin2hex($encryption_iv);

    // SQL query to insert new user with encrypted email and IV
    $sql = "INSERT INTO users (username, password, role, Email, Contact_number, iv) VALUES (?, ?, ?, ?, ?, ?)";

    // Preparing and executing statement
    if ($query = mysqli_prepare($link, $sql)) { 
        mysqli_stmt_bind_param($query, "ssssss", $username, $hashed_password, $role, $encrypted_email, $contact_number, $iv_hex);
        
        if (mysqli_stmt_execute($query)) {
            echo "User created successfully!";
        } else {
            echo "Error: " . mysqli_error($link);
        }
        
        mysqli_stmt_close($query); 
    } else {
        echo "Error: " . mysqli_error($link);
    }

    mysqli_close($link);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="../css/styles.css"> 
</head>
<body>
    <div class="container">
        <h2>Create New User</h2>
        <a href="read_user.php"><button class="action-button">Back</button></a>

        <form action="create_user.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Admin">Admin</option>
                <option value="Staff">Staff</option>
                <option value="Cashier">Cashier</option>
                <option value="Customer">Customer</option>
            </select>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required>
            
            <input type="submit" value="Create User">
        </form>
    </div>
</body>
</html>


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = mysqli_real_escape_string($link, $_POST['password']);
    $role = mysqli_real_escape_string($link, $_POST['role']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $contact_number = mysqli_real_escape_string($link, $_POST['contact_number']);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert new user
    $sql = "INSERT INTO users (username, password, role, Email, Contact_number) VALUES (?, ?, ?, ?, ?)";

    // Prepare and execute statement
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssss", $username, $hashed_password, $role, $email, $contact_number);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "User created successfully!";
        } else {
            echo "Error: " . mysqli_error($link);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($link);
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="../css/styles.css"> 
</head>
<body>
    <div class="container">
        <h2>Create New User</h2>
        <a href="read_user.php"><button class="action-button">Back</button></a>

        <form action="create_user.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Admin">Admin</option>
                <option value="Staff">Staff</option>
                <option value="Cashier">Cashier</option>
                <option value="customer">Customer</option>
            </select>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required>
            
            <input type="submit" value="Create User">
        </form>
    </div>
</body>
</html>
