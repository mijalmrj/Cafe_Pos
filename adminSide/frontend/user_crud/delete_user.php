<?php
// Include database configuration
require_once '../../config.php'; // Adjust the path as necessary

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize the user_id
    $user_id = mysqli_real_escape_string($link, $_POST['user_id']);

    // SQL query to delete user
    $sql = "DELETE FROM users WHERE user_id = ?";

    // Prepare and execute the statement
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "User deleted successfully!";
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
    <title>Delete User</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Adjust the path if necessary -->
</head>
<body>
    <div class="container">
        <h2>Delete User</h2>
        <form action="delete_user.php" method="post">
            <label for="user_id">User ID:</label>
            <input type="number" id="user_id" name="user_id" required>

            <input type="submit" value="Delete User">
        </form>
    </div>
</body>
</html>
