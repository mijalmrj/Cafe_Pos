<?php include '../inc/dashHeader.php'; ?>
<?php
// Include config file
require_once "../config.php";

$input_user_id = $user_iderr = $user_id = "";
$input_email = $email_err = $email = "";
$input_Contact_number = $Contact_number_err = $Contact_number = "";
$input_password = $password_err = $password = "";

?>
<head>
    <meta charset="UTF-8">
    <title>Create New memberships</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{ width: 1300px; padding-left: 200px; padding-top: 80px; }
    </style>
</head>

<div class="wrapper">
    <h1>CAFE Northside  cafe</h1>
    <h3>Create New users</h3>
    <p>Please fill in users Information Properly</p>

    <form method="POST" action="success_create_staff_memberships.php" class="ht-600 w-50">
        
        <div class="form-group">
            <label for="user_id" class="form-label">users ID:</label>
            <input min=1 type="number" name="user_id" placeholder="99" class="form-control <?php echo !$user_idErr ?: 'is-invalid'; ?>" id="user_id" required value="<?php echo $user_id; ?>"><br>
            <div id="validationServerFeedback" class="invalid-feedback">
                Please provide a valid users id.
            </div>
        </div>
        
        <div class="form-group">
            <label for="email" class="form-label">Email :</label>
            <input type="text" name="email" placeholder="Northsidecafe.com" class="form-control <?php echo !$emailErr ?: 'is-invalid'; ?>" id="email" required value="<?php echo $email; ?>"><br>
            <div id="validationServerFeedback" class="invalid-feedback">
                Please provide a valid email.
            </div>
        </div>


        <div class="form-group">
            <label for="Contact number" class="form-label">Phone Number:</label>
            <input type="text" name="Contact number" placeholder="+60101231234" class="form-control <?php echo !$Contact_numberErr ?: 'is-invalid'; ?>" id="Contact number" required value="<?php echo $Contact_number; ?>"><br>
            <div id="validationServerFeedback" class="invalid-feedback">
                Please provide a valid phone number.
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password :</label>
            <input type="password" name="password" placeholder="johnny1234@" id="password" required class="form-control <?php echo !$password_err ?: 'is-invalid' ; ?>" value="<?php echo $password; ?>"><br>
            <div id="validationServerFeedback" class="invalid-feedback">
                Please provide a valid password.
            </div>
        </div>
        
        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-dark" value="Create memberships">
        </div>
    </form>
</div>

<?php include '../inc/dashFooter.php'; ?>
