<?php
include '../../config.php'; 

// Initialize variables for messages
$success_message = '';
$error_message = '';

// Handle form submission for adding a new category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $description = $_POST['description'];
    $icon_file = $_FILES['iconfile'];

    // Check if an image was uploaded
    if (isset($icon_file) && $icon_file['error'] === 0) {
        $image_data = addslashes(file_get_contents($icon_file['tmp_name']));
    } else {
        $image_data = null;
    }

    // Insert into the database
    $sql = "INSERT INTO categories (category_name, Description, image) 
            VALUES ('$category_name', '$description', '$image_data')";

    if ($link->query($sql) === TRUE) {
        $success_message = "New category added successfully";
    } else {
        $error_message = "Error: " . $link->error;
    }
}

// Handle delete category
if (isset($_GET['delete'])) {
    $category_id = $_GET['delete'];
    $delete_sql = "DELETE FROM categories WHERE category_id = $category_id";

    if ($link->query($delete_sql) === TRUE) {
        $success_message = "Category deleted successfully";
    } else {
        $error_message = "Error: " . $link->error;
    }
}

// Fetch categories to display in the table
$sql = "SELECT * FROM categories";
$result = $link->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script>
        function showSuccessMessage(message) {
            alert(message);
        }
    </script>
</head>

<body class="manage-body">
    <div class="container">
        <header class="navbar">
            <div class="navhome">
                <a href="../index.html"><img src="../logos/home.png" id="coffee-logo"></a>
                <a href="../index.html"><h2>Northside Café</h2></a>
            </div>
            <div class="navlinks">
                <div class="adminlinks">
                    <a href="../settings.html"><img src="../logos/settings.png"></a>
                    <a href="../report.html"><img src="../logos/analytics.png"></a>
                </div>
                <div class="generallinks">
                    <a href="../transactions_admin.html"><img src="../logos/print.png"></a>
                    <a href="../user.html"><img src="../logos/user.png"></a>
                </div>
            </div>
        </header>

        <div class="main-manage">
            <div class="manage-header">
                <img src="../logos/categories.png">
                <h1>MANAGE CATEGORIES</h1>
            </div>
            <hr>
            <div class="manage-table">
                <form method="POST" enctype="multipart/form-data">
                    <table id="category-table">
                        <tr>
                            <th>NAME</th>
                            <th>DESCRIPTION</th>
                            <th>ICON</th>
                            <th>ACTIONS</th>
                        </tr>

                        <tr class="menu-item">
                            <td><input type="text" name="category_name" placeholder="CATEGORY NAME" class="item-name" required></td>
                            <td><textarea name="description" placeholder="DESCRIPTION" required></textarea></td>
                            <td><input class="file-button" type="file" name="iconfile" required></td>
                            <td><button type="submit" name="add_category" class="action-button">ADD CATEGORY</button></td>
                        </tr>
                        
                        <!-- Display categories -->
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Description']); ?></td>
                                    <td>
                                        <?php if ($row['image']): ?>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>" width="50">
                                        <?php else: ?>
                                            No image
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit_category.php?id=<?php echo $row['category_id']; ?>" class="action-button">Edit</a>
                                        <a href="?delete=<?php echo $row['category_id']; ?>" class="action-button" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No categories found</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Show success or error message if available
        <?php if ($success_message): ?>
            showSuccessMessage("<?php echo $success_message; ?>");
        <?php endif; ?>
        <?php if ($error_message): ?>
            alert("<?php echo $error_message; ?>");
        <?php endif; ?>
    </script>
</body>

</html>

<?php
$link->close();
?>
