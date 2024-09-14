<?php include '../inc/dashHeader.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Staff to an memberships</title>
    <meta charset="UTF-8">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 1300px; padding-left: 200px; padding-top: 80px  }
    </style>
</head>
<body>
    <div class="wrapper">
    <h1>Johnny's Dining & Bar</h1>
    <h3>Assign Staff to an memberships</h3>
    <p>Please choose an memberships to Assign for the Staff Properly</p>
    
    <form action="update_staff.php" method="post" class="ht-600 w-50">
        
        <div class="form-group">
        <label for="memberships_id" class="form-label">memberships ID:</label>
        <select id="memberships_id" name="memberships_id" required>
            <option value="">Select an memberships</option>
            <?php
            // Assuming you have a database connection established
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "cafedb";

            $link = new mysqli($servername, $username, $password, $dbname);

            if ($link->connect_error) {
                die("Connection failed: " . $link->connect_error);
            }

            // Query to retrieve membershipss without staff assigned
            $membershipsQuery = "SELECT memberships_id FROM membershipss WHERE staff_id IS NULL";
            $membershipsResult = $link->query($membershipsQuery);

            while ($row = $membershipsResult->fetch_assoc()) {
                echo "<option value='" . $row['memberships_id'] . "'>" . $row['memberships_id'] . "</option>";
            }

            $link->close();
            ?>
        </select>
        </div>
        
        <br>
        <div class="form-group">
        <label for="staff_id" class="form-label">Staff ID:</label>
        <select id="staff_id" name="staff_id" required>
            <option value="">Select a staff</option>
            <?php
            // Assuming you have a database connection established
            $link = new mysqli($servername, $username, $password, $dbname);

            if ($link->connect_error) {
                die("Connection failed: " . $link->connect_error);
            }

            // Query to retrieve staffs not used by any memberships
            $staffQuery = "SELECT staff_id FROM Staffs WHERE staff_id NOT IN (SELECT staff_id FROM membershipss WHERE staff_id IS NOT NULL)";
            $staffResult = $link->query($staffQuery);

            while ($row = $staffResult->fetch_assoc()) {
                echo "<option value='" . $row['staff_id'] . "'>" . $row['staff_id'] . "</option>";
            }

            $link->close();
            ?>
        </select>
        </div>
        <br>
        
        <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Assign memberships to Staff">
        </div>
    </form>
    </div>
</body>
</html>
