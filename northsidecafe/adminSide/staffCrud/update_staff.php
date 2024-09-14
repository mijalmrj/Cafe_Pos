<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafedb";

$link = new mysqli($servername, $username, $password, $dbname);

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$iconClass = 'fa-check-circle'; // This value indicates success; you can adjust it as needed
$cardClass = 'alert-success';   // This value indicates a success message card; adjust as needed
$bgColor = "#D4F4DD";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $memberships_id = $_POST['memberships_id'];
    $staff_id = $_POST['staff_id'];

    // Validate and sanitize inputs
    $memberships_id = intval($memberships_id);
    $staff_id = intval($staff_id);

    $checkmembershipsQuery = "SELECT * FROM membershipss WHERE memberships_id = ?";
    $checkStaffQuery = "SELECT * FROM staffs WHERE staff_id = ?";

    // Use prepared statements
    $membershipsStmt = $link->prepare($checkmembershipsQuery);
    $membershipsStmt->bind_param("i", $memberships_id);
    $membershipsStmt->execute();
    $membershipsResult = $membershipsStmt->get_result();

    $staffStmt = $link->prepare($checkStaffQuery);
    $staffStmt->bind_param("i", $staff_id);
    $staffStmt->execute();
    $staffResult = $staffStmt->get_result();

    if ($membershipsResult->num_rows === 0) {
        echo "Invalid memberships ID. No matching memberships found.";
    } elseif ($staffResult->num_rows === 0) {
        echo "Invalid staff ID. No matching staff found.";
    } else {
        // Check if the memberships already has a staff assigned
        $existingStaffQuery = "SELECT staff_id FROM membershipss WHERE memberships_id = ?";
        $existingStaffStmt = $link->prepare($existingStaffQuery);
        $existingStaffStmt->bind_param("i", $memberships_id);
        $existingStaffStmt->execute();
        $existingStaffResult = $existingStaffStmt->get_result();
        $row = $existingStaffResult->fetch_assoc();
        $existingStaffId = $row['staff_id'];

        if ($existingStaffId !== null) {
            echo "memberships already has a staff assigned.";
        } else {
            $updateQuery = "UPDATE membershipss SET staff_id = ? WHERE memberships_id = ?";
            $updateStmt = $link->prepare($updateQuery);
            $updateStmt->bind_param("ii", $staff_id, $memberships_id);

            if ($updateStmt->execute()) {
                $message = "memberships assigned to staff successfully.";
            } else {
                $message = "Error updating staff.";
            }
        }
    }

    // Close all prepared statements
    $membershipsStmt->close();
    $staffStmt->close();
    $existingStaffStmt->close();
    $updateStmt->close();
}

$link->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <style>
        /* Your custom CSS styles for the success message card here */
        body {
            text-align: center;
            padding: 40px 0;
            background: #EBF0F5;
        }
        h1 {
            color: #88B04B;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
            font-weight: 900;
            font-size: 40px;
            margin-bottom: 10px;
        }
        p {
            color: #404F5E;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
            font-size: 20px;
            margin: 0;
        }
        i.checkmark {
            color: #9ABC66;
            font-size: 100px;
            line-height: 200px;
            margin-left: -15px;
        }
        .card {
            background: white;
            padding: 60px;
            border-radius: 4px;
            box-shadow: 0 2px 3px #C8D0D8;
            display: inline-block;
            margin: 0 auto;
        }
        /* Additional CSS styles based on success/error message */
        .alert-success {
            /* Customize the styles for the success message card */
            background-color: <?php echo $bgColor; ?>;
        }
        .alert-success i {
            color: #5DBE6F; /* Customize the checkmark icon color for success */
        }
        .alert-danger {
            /* Customize the styles for the error message card */
            background-color: #FFA7A7; /* Custom background color for error */
        }
        .alert-danger i {
            color: #F25454; /* Customize the checkmark icon color for error */
        }
        .custom-x {
            color: #F25454; /* Customize the "X" symbol color for error */
            font-size: 100px;
            line-height: 200px;
        }
    </style>
</head>
<body>
    <div class="card <?php echo $cardClass; ?>" style="display: none;">
        <div style="border-radius: 200px; height: 200px; width: 200px; background: #F8FAF5; margin: 0 auto;">
            <?php if ($iconClass === 'fa-check-circle'): ?>
                <i class="checkmark">✓</i>
            <?php else: ?>
                <i class="custom-x" style="font-size: 100px; line-height: 200px;">✘</i>
            <?php endif; ?>
        </div>
        <h1><?php echo ($cardClass === 'alert-success') ? 'Success' : 'Error'; ?></h1>
        <p><?php echo $message; ?></p>
    </div>

    <div style="text-align: center; margin-top: 20px;">Redirecting back in <span id="countdown">3</span></div>

    <script>
        // Function to show the message card as a pop-up and start the countdown
        function showPopup() {
            var messageCard = document.querySelector(".card");
            messageCard.style.display = "block";

            var i = 3;
            var countdownElement = document.getElementById("countdown");
            var countdownInterval = setInterval(function() {
                i--;
                countdownElement.textContent = i;
                if (i <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = "../panel/staff-panel.php";
                }
            }, 1000); // 1000 milliseconds = 1 second
        }

        // Show the message card and start the countdown when the page is loaded
        window.onload = showPopup;

        // Function to hide the message card after a delay
        function hidePopup() {
            var messageCard = document.querySelector(".card");
            messageCard.style.display = "none";
            // Redirect to another page after hiding the pop-up (adjust the delay as needed)
            setTimeout(function () {
                window.location.href = "../panel/staff-panel.php"; // Replace with your desired URL
            }, 3000); // 3000 milliseconds = 3 seconds
        }

        // Hide the message card after 3 seconds (adjust the delay as needed)
        setTimeout(hidePopup, 3000);
    </script>
</body>
</html>