<?php
include 'config.php'; // Database connection
session_start(); // Start session
$user_id = $_SESSION['user_id']; // Retrieve stored session data

// Check if user is logged in, if not, redirect back to login page
if (!isset($user_id)) {
    header('location:login.php');
}

// Code to send message, submit form
if (isset($_POST['send'])) {
    
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    // Generate a date string
    $date = date('Y-m-d H:i:s', time());

    $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE message = '$msg'") or die('query failed');

    if (mysqli_num_rows($select_message) > 0) {
        $message[] = 'Message already sent!';
    } else {
        mysqli_query($conn, "INSERT INTO `message`(user_id, message, date) VALUES('$user_id', '$msg','$date')") or die('query failed');
        $message[] = 'Message sent successfully!';
    }
    $messageJSON = json_encode($message);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Contact us</title>
    <!-- Custom CSS file -->
    <link rel="stylesheet" href="admin.css" />
    <link rel="stylesheet" href="index.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  </head>
  <body>
    <!-- Code to display the message in a popup -->
    <div id="popup" class="popup-container">
        <div class="popup-content">
            <span id="popup-message"></span>
            <button id="close-button" class="btn">Close</button>
        </div>
    </div>

    <?php 
    include 'header.php'; //header file
    ?>

    <div class="heading">
        <h3>Contact us</h3>
        <p><a href="index.php">home</a> / contact </p>
    </div>

    <section class="contact">
        <form action="" method="post">
            <h3>Say something!</h3>
            <textarea name="message" class="box" placeholder="Enter your message" id="" cols="30" rows="10"></textarea>
            <input type="submit" value="Send message" name="send" class="btn">
        </form>
    </section>

    <?php 
    include 'footer.php';
    ?>

    <script>
        // Retrieve the message from the PHP variable
        var message = <?php echo $messageJSON; ?>;

        // Get the pop-up container, message element, and close button
        var popup = document.getElementById("popup");
        var popupMessage = document.getElementById("popup-message");
        var closeButton = document.getElementById("close-button");

        // Set the message text
        popupMessage.textContent = message;

        // Display the pop-up
        popup.style.display = "flex";

        // Close the pop-up when the close button is clicked
        closeButton.addEventListener("click", function() {
            popup.style.display = "none";
        });

        // Close the pop-up when the pop-up container is clicked
        popup.addEventListener("click", function(event) {
            if (event.target === popup) {
                popup.style.display = "none";
            }
        });
    </script>

    <!-- JS file link -->
    <script src="js/header.js"></script>
  </body>
</html>
