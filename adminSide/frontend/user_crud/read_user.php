<?php

require_once '../../config.php'; 

// In deploying northside cafe, we would store this key securely (like AWS Secrets Manager or environment variables)
// For simplicity in this project  to deliver it to our teachers, it is hardcoded here.
$encryption_key = 'e954bdd837af6f46fdd159c53285c8e19002d1b0b2e49c301379e0a8a9df7601'; 

// Query to select all users, including the 'iv' column for decryption
$sql = "SELECT user_id, username, role, Email, Contact_number FROM users";
$result = mysqli_query($link, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .no-data {
            text-align: center;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>User List</h2>
        <a href="create_user.php"><button class="action-button">Add User</button></a>

        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>User ID</th>';
            echo '<th>Username</th>';
            echo '<th>Role</th>';
            echo '<th>Email</th>';
            echo '<th>Contact Number</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // get and display user details
            while ($row = mysqli_fetch_assoc($result)) {
                // decrypt the email using the stored IV

                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                echo '<td>' . htmlspecialchars($row['role']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Contact_number']) . '</td>';
                echo '<td>
                        <a href="update_user.php?user_id=' . $row["user_id"] . '">EDIT</a> 
                        <a href="delete_user.php?user_id=' . $row["user_id"] . '">DELETE</a>
                    </td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p class="no-data">No users found.</p>';
        }

        mysqli_close($link);
        ?>
    </div>
</body>

</html>
