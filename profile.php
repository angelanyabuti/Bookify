<?php
include 'config.php'; // Database connection
session_start(); // Session start
$admin_id = $_SESSION['admin_id']; // Retrieve session data

// Check if user is logged in, if not, redirect to login page
if (!isset($admin_id)) {
    header('location:login.php');
}

// Code to update profile
if (isset($_POST['update_profile'])) {
    $admin_id = $_POST['admin_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $address = $_POST['address'];
    mysqli_query($conn, "UPDATE users SET fname = '$fname', lname = '$lname', email = '$email', telephone = '$telephone', address = '$address' WHERE id = '$admin_id'") or die('query failed');
    $message[] = 'Profile updated successfully!';
    $messageJSON = json_encode($message);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <!-- CSS file links -->
    <link rel="stylesheet" href="admin.css" />
    <link rel="stylesheet" href="orders.css" />
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
include 'admin_header.php'; // Admin header file
?>

<?php
// Query to fetch data from database
$select_user = mysqli_query($conn, "SELECT * FROM users WHERE id = '$admin_id'") or die('query failed');

if (mysqli_num_rows($select_user) > 0) {
    while ($fetch_user = mysqli_fetch_assoc($select_user)) {
?>
<section class="profile">
    <div class="box">
        <div class="form-group">
            <form action="" method="post">
                <input type="hidden" name="admin_id" value="<?php echo $fetch_user['id']; ?>">
                <label for="fname">First Name:</label>
                <input type="text" name="fname" id="fname" value="<?php echo $fetch_user['fname']; ?>">
                <label for="lname">Last Name:</label>
                <input type="text" name="lname" id="lname" value="<?php echo $fetch_user['lname']; ?>">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $fetch_user['email']; ?>">
                <label for="telephone">Telephone:</label>
                <input type="number" name="telephone" id="telephone" value="<?php echo $fetch_user['Telephone']; ?>">
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" value="<?php echo $fetch_user['Address']; ?>">
                <input type="submit" value="Update Profile" name="update_profile" class="btn">
            </form>
        </div>
    </div>
</section>
<?php
    }
} else {
}
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

</body>
</html>
