<?php
include 'config.php'; // Database connection
session_start(); // Start session

// Code to submit the form
if (isset($_POST['submit'])) {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $pass = mysqli_real_escape_string($conn, ($_POST['password']));

  $select_users = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'") or die('query failed');

  if (mysqli_num_rows($select_users) > 0) {
    $row = mysqli_fetch_assoc($select_users);
    $storedHashedPassword = $row['password'];

    if (password_verify($pass, $storedHashedPassword)) {
      // Password is correct
      // Store the data to a session
      if ($row['user_type'] == 'admin') {
        $_SESSION['admin_name'] = $row['fname'] . ' ' . $row['lname'];
        $_SESSION['admin_email'] = $row['email'];
        $_SESSION['admin_id'] = $row['id'];
        // Redirect to admin panel
        header('location: admin_panel.php');
      } else if ($row['user_type'] == 'user') {
        $_SESSION['user_name'] = $row['fname'] . ' ' . $row['lname'];
        $_SESSION['user_email'] = $row['email'];
        $_SESSION['user_id'] = $row['id'];
        // Redirect to home page
        header('location: index.php');
      }
    } else {
      $message[] = 'Incorrect email or password';
    }
  } else {
    $message[] = 'Incorrect email or password';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sign up</title>
  <link rel="stylesheet" href="index.css" />
</head>
<body>
  <?php
  include 'header.php';
  ?>
  <?php
  if (isset($message)) {
    foreach ($message as $message) {
      echo '
      <div class="message">
        <span>' . $message . '</span>
      </div>
      ';
    }
  }
  ?>

  <div class="form-container">
    <form action="" method="post">
      <h3>Login now</h3>
      <input type="email" name="email" placeholder="enter your email" required class="box" />
      <input type="password" name="password" placeholder="enter your password" required class="box" />
      <input type="submit" name="submit" value="login" required class="btn" />
      <p>don't have an account?<a href="signup.php">Register now</a></p>
      <p>Forgot your password? <a href="forgot_password.php">Reset Password</a></p>
    </form>
  </div>
</body>
</html>
