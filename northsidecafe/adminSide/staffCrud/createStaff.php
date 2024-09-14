<?php
session_start(); // Ensure session is started
?>
<?php include '../inc/dashHeader.php'; ?>
<?php
// Include config file
require_once "../config.php";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafedb";

// Create a connection
$link = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$input_staff_id = $staff_id_err = $staff_id = "";
$input_memberships_id = $memberships_iderr = $memberships_id = "";
$input_email = $email_err = $email = "";
$input_register_date = $register_date_err = $register_date = "";
$input_Contact number = $Contact number_err = $Contact number = "";
$input_password = $password_err = $password = "";

// Processing form data when form is submitted
if (isset($_POST['submit'])) {
    if (empty($_POST['staff_id'])) {
        $staff_idErr = 'ID is required';
    } else {
        $staff_id = filter_input(
            INPUT_POST,
            'staff_id',
            FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );
    }
}

// Function to get the next available memberships ID
function getNextAvailablemembershipsID($link) {
    $sql = "SELECT MAX(memberships_id) as max_memberships_id FROM membershipss";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $next_memberships_id = $row['max_memberships_id'] + 1;
    return $next_memberships_id;
}

// Function to get the next available Staff ID
function getNextAvailableStaffID($link) {
    $sql = "SELECT MAX(staff_id) as max_staff_id FROM Staffs";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $next_staff_id = $row['max_staff_id'] + 1;
    return $next_staff_id;
}

// Get the next available Staff ID
$next_staff_id = getNextAvailableStaffID($link);

// Get the next available memberships ID
$next_memberships_id = getNextAvailablemembershipsID($link);
?>
<head>
    <meta charset="UTF-8">
    <title>Create New Staff</title>
    <style>
       .wrapper{ width: 1300px; padding-left: 200px ; padding-top: 80px; }
       /* Style the select input */
        #memberships_id {
            width: 100%;
           
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            color: #333;
        }

        /* Style the default option */
        #memberships_id option {
            color: #333;
        }

        /* Style the selected option */
        #memberships_id option:checked {
            background-color: #007bff;
            color: #fff;
        }

        /* Style the select when it's required and empty */
        #memberships_id:required:invalid {
            color: #999;
            border-color: #f00; /* Red border for validation */
        }

        /* Style the select when it's required and filled */
        #memberships_id:required:valid {
            border-color: #28a745; /* Green border for validation */
            color: #333;
        }
    </style>
</head>

<div class="wrapper">
    <h3>Create New Staff</h3>
    <p>Please fill in the Staff Information</p>

    <form method="POST" action="succ_create_staff.php" class="ht-600 w-50">

        <div class="form-group">
            <label for="staff_id" class="form-label">Staff ID:</label>
            <input min="1" type="number" name="staff_id" placeholder="1" class="form-control <?php echo $staff_id_err ? 'is-invalid' : ''; ?>" id="staff_id" required value="<?php echo $next_memberships_id; ?>" readonly><br>
            <div class="invalid-feedback">
                Please provide a valid staff_id.
            </div>
        </div>

        <div class="form-group">
            <label for="staff_name">Staff Name:</label>
            <input type="text" name="staff_name" placeholder="Nipesh Bhatta" id="staff_name" required class="form-control <?php echo (!empty($staff_name_err)) ? 'is-invalid' : ''; ?>"><br>
            <span class="invalid-feedback"></span>
        </div>

        <div class="form-group">
            <label for="role">Role:</label>
            <input type="text" name="role" id="role" placeholder="Cashier" required class="form-control <?php echo (!empty($role_err)) ? 'is-invalid' : ''; ?>"><br>
            <span class="invalid-feedback"></span>
        </div>
        
        <div class="form-group">
            <label for="memberships_id" class="form-label">memberships ID:</label>
            <input min="1" type="number" name="memberships_id" placeholder="99" class="form-control <?php echo !$memberships_idErr ?: 'is-invalid'; ?>" id="memberships_id" required value="<?php echo $next_memberships_id; ?>" readonly><br>
            <div id="validationServerFeedback" class="invalid-feedback">
                Please provide a valid memberships_id.
            </div>
        </div>
        
        <div class="form-group">
            <label for="email" class="form-label">Email :</label>
            <input type="text" name="email" placeholder="Northside  cafe@gmail.com" class="form-control <?php echo !$emailErr ?: 'is-invalid'; ?>" id="email" required value="<?php echo $email; ?>"><br>
            <div id="validationServerFeedback" class="invalid-feedback">
                Please provide a valid email.
            </div>
        </div>

        <div class="form-group">
            <label for="register_date">Register Date :</label>
            <input type="date" name="register_date" id="register_date" required class="form-control <?php echo !$register_date_err ?: 'is-invalid';?>" value="<?php echo $register_date; ?>"><br>
            <div id="validationServerFeedback" class="invalid-feedback">
                Please provide a valid register date.
            </div>
        </div>

        <div class="form-group">
            <label for="Contact number" class="form-label">Phone Number:</label>
            <input type="text" name="Contact number" placeholder="+61466125458" class="form-control <?php echo !$Contact numberErr ?: 'is-invalid'; ?>" id="Contact number" required value="<?php echo $Contact number; ?>"><br>
            <div id="validationServerFeedback" class="invalid-feedback">
                Please provide a valid phone number.
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password :</label>
            <input type="password" name="password" placeholder="chuckofcoffee" id="password" required class="form-control <?php echo !$password_err ?: 'is-invalid' ; ?>" value="<?php echo $password; ?>"><br>
            <div id="validationServerFeedback" class="invalid-feedback">
                Please provide a valid password.
            </div>
        </div>
        
        <div class="form-group mb-5">
            <input type="submit" class="btn btn-dark" value="Create Staff">
        </div>

    </form>
</div>

<?php include '../inc/dashFooter.php'; ?>
