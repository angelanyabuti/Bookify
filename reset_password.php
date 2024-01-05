<?php
include 'config.php';
session_start();

if (isset($_GET['token'])) {
  $token = $_GET['token'];

  // Check if the token exists in the database and is not expired
  $select_user = mysqli_query($conn, "SELECT * FROM users WHERE reset_token = '$token' AND reset_expiry >= NOW()") or die('Query failed');

  if (mysqli_num_rows($select_user) > 0) {
    $user = mysqli_fetch_assoc($select_user);
    $user_id = $user['id'];

    if (isset($_POST['submit'])) {
      $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
      $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

      if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        // Generate the expiry timestamp (e.g., 1 hour from now)
        $expiry_timestamp = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update the user's password in the database
        mysqli_query($conn, "UPDATE users SET password = '$hashed_password', reset_token = NULL, reset_expiry = $expiry_timestamp WHERE id = '$user_id'") or die('Query failed');

        // Redirect to the login page with a success message
        $_SESSION['reset_success'] = true;
        header('Location: login.php');
        exit();
      } else {
        $message = 'Passwords do not match.';
      }
    }
  } else {
    // Token is invalid or expired
    $message = 'Invalid or expired token.';
  }
} else {
  // Token is not provided
  $message = 'Token is missing.';
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="index.css" />
  </head>

  <body>
    <?php
    if (isset($message)) {
      echo '
        <div class="message">
          <span>' . $message . '</span>
        </div>
      ';
    }
    ?>

    <div class="form-container">
      <form action="" method="post">
        <h3>Reset Password</h3>
        <p>Enter your new password.</p>

        <input
          type="password"
          name="new_password"
          placeholder="New Password"
          required
          class="box"
        />
        <input
          type="password"
          name="confirm_password"
          placeholder="Confirm Password"
          required
          class="box"
        />
        
        <input
          type="submit"
          name="submit"
          value="Reset Password"
          required
          class="btn"
        />
      </form>
    </div>
  </body>
</html>
