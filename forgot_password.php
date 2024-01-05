<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\wamp64\www\online-bookstore-main\php mailer/Exception.php';
require 'C:\wamp64\www\online-bookstore-main\php mailer/PHPMailer.php';
require 'C:\wamp64\www\online-bookstore-main\php mailer/SMTP.php';

include 'config.php';
session_start();

if (isset($_POST['submit'])) {
  $email = mysqli_real_escape_string($conn, $_POST['email']);

  // Check if the email exists in the database
  $select_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'") or die('Query failed');

  if (mysqli_num_rows($select_user) > 0) {
    $user = mysqli_fetch_assoc($select_user);
    $user_id = $user['id'];

    // Generate a unique token for password reset
    $token = md5(uniqid());

    // Store the token in the database for the user
    mysqli_query($conn, "UPDATE users SET reset_token = '$token' WHERE id = '$user_id'") or die('Query failed');

    // Send the password reset link to the user's email
    $reset_link = "http://127.0.0.1/online-bookstore-main/reset_password.php?token=$token";

    // Email configuration
    $mail = new PHPMailer(true);
    try {
      // SMTP configuration
      $mail->isSMTP();
      $mail->Host = 'smtp.office365.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'nyabutiangela20@outlook.com';
      $mail->Password = 'Admin@2886';
      $mail->Port = 587;

      // Sender and recipient
      $mail->setFrom('nyabutiangela20@outlook.com', 'Angela Nyabuti');
      $mail->addAddress($email, $user['fname']);

      // Email content
      $mail->isHTML(true);
      $mail->Subject = 'Password Reset Link';
      $mail->Body = 'Click the following link to reset your password: <a href="' . $reset_link . '">Reset Password</a>';

      // Send the email
      $mail->send();

      // Display a success message to the user
      $message = 'A password reset link has been sent to your email address.';
    } catch (Exception $e) {
      // Display an error message if the email could not be sent
      $message = 'Error sending email: ' . $mail->ErrorInfo;
    }
  } else {
    $message = 'Email address not found.';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Forgot Password</title>
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
        <p>Enter your email address to receive a password reset link.</p>

        <input
          type="email"
          name="email"
          placeholder="Enter your email"
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
        <p>Remember your password? <a href="login.php">Back to Login</a></p>
      </form>
    </div>
  </body>
</html>
