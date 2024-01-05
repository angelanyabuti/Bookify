<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>

<?php
if (isset($_POST["submit"])) {
    require_once "config.php";

    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, ($_POST['password']));
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $passwordRepeat = mysqli_real_escape_string($conn, ($_POST['repeat_password']));

    $errors = array();

    if (empty($fname) OR empty($lname) OR empty($telephone) OR empty($address) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if (strlen($passwordRepeat) < 8) {
        array_push($errors, "Confirm password must be at least 8 characters long");
    }
    if ($password !== $passwordRepeat) {
        array_push($errors, "Password does not match");
    }
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $rowCount = mysqli_num_rows($result);
    if ($rowCount > 0) {
        array_push($errors, "Email already exists!");
    }
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            //echo "$error";
        }
    } else {

        $sql = "INSERT INTO users (fname, lname, telephone, address, email, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
        if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $fname, $lname, $telephone, $address, $email, $hashedPassword);
            mysqli_stmt_execute($stmt);
            echo "You are registered successfully";
            header('location:login.php');
        } else {
            die("Something went wrong");
        }
    }

    // Pass the error messages to JavaScript
    echo "<script>";
    echo "var errors = " . json_encode($errors) . ";";
    echo "</script>";

    // Your PHP code to check and store the error messages goes here

    // Display the error messages in a pop-up
    if (count($errors) > 0) {
        echo "<div class='error-popup'>";
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo "<button class='btn' onclick='closePopup()'>Close</button>";
        echo "  
  </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="index.css">
    <style>
        /* Style for the error pop-up */
        .error-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <?php
    include 'header.php';
    ?>
    <div class="form-container">
        <div class="box">
            <form action="signup.php" method="post">
                <h3>Register now</h3>
                <input type="text" class="form-control" value="<?php echo isset($_POST['fname']) ? $_POST['fname'] : ''; ?>" name="fname" placeholder="First Name:">
                <input type="text" class="form-control" value="<?php echo isset($_POST['lname']) ? $_POST['lname'] : ''; ?>" name="lname" placeholder="Last Name:">
                <input type="number" class="form-control" value="<?php echo isset($_POST['telephone']) ? $_POST['telephone'] : ''; ?>" name="telephone" placeholder="Phone number:">
                <input type="email" class="form-control" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" name="email" placeholder="Email:">
                <input type="text" class="form-control" value="<?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?>" name="address" placeholder="Address:">
                <input type="password" class="form-control" name="password" placeholder="Password:">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
                <p>Already Registered <a href="login.php">Login Here</a></p>
            </form>
        </div>
    </div>
    <script>
        function closePopup() {
            var popup = document.querySelector('.error-popup');
            popup.style.display = 'none';
        }
    </script>
</body>

</html>
