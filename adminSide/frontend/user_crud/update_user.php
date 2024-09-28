<?php

require_once '../../config.php'; 

// If we deployed northside cafe pos, we would store this key securely (in AWS Secrets Manager or environment variables)
// For simplicity in sending project to our teachers, it is hardcoded here.
$encryption_key = 'e954bdd837af6f46fdd159c53285c8e19002d1b0b2e49c301379e0a8a9df7601'; 

//  variables
$user_id = "";
$username = "";
$role = "";
$email = "";
$contact_number = "";

// check if form is submitted corectly
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];

    //  generate a secure random IV for AES encryption
    $encryption_iv = random_bytes(16); 

    // encrypting  email
    $encrypted_email = openssl_encrypt($email, 'AES-128-CTR', $encryption_key, 0, $encryption_iv);

    // converting  IV 
    $iv_hex = bin2hex($encryption_iv);

    // Update user details
    $sql = "UPDATE users SET username=?, role=?, Email=?, Contact_number=?, iv=? WHERE user_id=?";
    $query = mysqli_prepare($link, $sql); 
    mysqli_stmt_bind_param($query, 'sssssi', $username, $role, $encrypted_email, $contact_number, $iv_hex, $user_id);

    if (mysqli_stmt_execute($query)) {
        echo "<p>User updated successfully!</p>";
    } else {
        echo "<p>Error updating user: " . mysqli_error($link) . "</p>";
    }

    mysqli_stmt_close($query); 
}

// Fetch user details for the given ID (including decryption of email)
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $sql = "SELECT username, role, Email, Contact_number, iv FROM users WHERE user_id=?";
    $query = mysqli_prepare($link, $sql); 
    mysqli_stmt_bind_param($query, 'i', $user_id);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $username, $role, $encrypted_email, $contact_number, $iv_hex);
    mysqli_stmt_fetch($query);
    mysqli_stmt_close($query);

    // Convert the IV from hexadecimal back to binary
    $encryption_iv = hex2bin($iv_hex);

    // Decrypt the email using the stored IV and encryption key
    $email = openssl_decrypt($encrypted_email, 'AES-128-CTR', $encryption_key, 0, $encryption_iv);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        button {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Update User</h2>
        <a href="read_user.php"><button class="action-button">Back</button></a>

        <form method="POST" action="update_user.php">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Admin" <?php echo ($role == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="Staff" <?php echo ($role == 'Staff') ? 'selected' : ''; ?>>Staff</option>
                <option value="Cashier" <?php echo ($role == 'Cashier') ? 'selected' : ''; ?>>Cashier</option>
                <option value="customer" <?php echo ($role == 'customer') ? 'selected' : ''; ?>>Customer</option>
            </select>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="contact_number">Contact Number:</label>
            <input type="number" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>" required>

            <button type="submit">Update User</button>
        </form>
    </div>
</body>

</html>

<?php
// Close the database connection
mysqli_close($link);
?>