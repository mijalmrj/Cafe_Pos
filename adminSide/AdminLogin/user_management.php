<?php
require_once "../config.php";
session_start();



// Handle Create, Update, Delete
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if adding a new user
    if (isset($_POST['add_user'])) {
        // Validate inputs
        if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
            $role = $_POST['role'];

            $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $link->prepare($query);
            $stmt->bind_param("ssss", $username, $email, $password, $role);
            $stmt->execute();
        } else {
            // Handle the case where one or more fields are missing
            echo "<p style='color:red;'>Please fill in all fields.</p>";
        }
    } elseif (isset($_POST['update_user'])) {
        // Validate inputs for updating user
        if (isset($_POST['user_id'], $_POST['username'], $_POST['role'])) {
            $user_id = $_POST['user_id'];
            $username = $_POST['username'];
            $email = isset($_POST['email']) ? $_POST['email'] : ''; // Ensure email is set
            $role = $_POST['role'];

            $query = "UPDATE users SET username=?, email=?, role=? WHERE user_id=?";
            $stmt = $link->prepare($query);
            $stmt->bind_param("sssi", $username, $email, $role, $user_id);
            $stmt->execute();
        } else {
            // Handle the case where one or more fields are missing
            echo "<p style='color:red;'>Please fill in all fields.</p>";
        }
    } elseif (isset($_POST['delete_user'])) {
        // Validate user_id is set
        if (isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];

            $query = "DELETE FROM users WHERE user_id=?";
            $stmt = $link->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }
    }
}


// Fetch users for listing
$query = "SELECT * FROM users";
$result = $link->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        /* Header styles */
        header {
            background: #333;
            color: #ffffff;
            padding: 15px 0;
            text-align: center;
        }
        nav ul {
            list-style: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            color: #ffffff;
            text-decoration: none;
        }
        /* Form styles */
        form {
            margin: 20px 0;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Footer styles */
        footer {
            background: #333;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>User Management</h1>
            <nav>
                <ul>
                    <li><a href="../frontend/index.php">Home</a></li>
                    <li><a href="admin_dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Add New User</h2>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="role" required>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>
                </select>
                <button type="submit" name="add_user">Add User</button>
            </form>

            <h2>User List</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <button onclick="document.getElementById('updateForm_<?php echo $row['user_id']; ?>').style.display='block'">Edit</button>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                            <button type="submit" name="delete_user">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- Update User Form -->
                <div id="updateForm_<?php echo $row['user_id']; ?>" style="display:none;">
                    <h3>Update User</h3>
                    <form action="" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                        <input type="text" name="username" value="<?php echo $row['username']; ?>" required>
                        <select name="role" required>
                            <option value="admin" <?php if ($row['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                            <option value="staff" <?php if ($row['role'] == 'staff') echo 'selected'; ?>>Staff</option>
                            <option value="customer" <?php if ($row['role'] == 'customer') echo 'selected'; ?>>Customer</option>
                        </select>
                        <button type="submit" name="update_user">Update User</button>
                        <button type="button" onclick="document.getElementById('updateForm_<?php echo $row['user_id']; ?>').style.display='none'">Cancel</button>
                    </form>
                </div>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
    

    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Northside Café</p>
        </div>
    </footer>
</body>
</html>

<?php
mysqli_close($link);
?>
