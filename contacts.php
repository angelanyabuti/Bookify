<?php
include 'config.php'; // Database connection
session_start(); // Start session
$admin_id = $_SESSION['admin_id']; // Retrieve stored session data

if (!isset($admin_id)) {
    header('location:login.php');
}

// Code to delete message
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM message WHERE id = '$delete_id'") or die('Query failed');
    // Redirect to contacts.php
    header('location:contacts.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Messages</title>
    <!-- CSS file link -->
    <link rel="stylesheet" href="admin.css" />
</head>
<body>
<?php
include 'admin_header.php'; // Admin header file
?>
<!--beginning of messages section-->
<section class="messages">
    <h1 class="title">Messages</h1>
    <div class="box-container">
        <?php
        // Query to fetch data from the database
        $select_message = "SELECT users.fname,users.email,users.telephone,users.id,message.message,message.id
                           FROM users
                           INNER JOIN message ON users.id=message.user_id;";
        // The result is assigned to the variable $result
        $result = $conn->query($select_message);

        if (mysqli_num_rows($result) > 0) {
            while ($fetch_message = mysqli_fetch_assoc($result)) {
                ?>
                <div class="box">
                    <p>name: <span><?php echo $fetch_message['fname'] ?></span></p>
                    <p>number: <span><?php echo $fetch_message['telephone'] ?></span></p>
                    <p>email: <span><?php echo $fetch_message['email'] ?></span></p>
                    <p>message: <span><?php echo $fetch_message['message'] ?></span></p>
                    <a href="contacts.php?delete=<?php echo $fetch_message['id']; ?>"
                       onclick="return confirm('Delete this message?');" class="delete-btn">Delete</a>
                </div>
                <?php
            };
        } else {
            echo '<p class="empty">You have no messages</p>';
        }
        ?>
    </div>
</section>
<!--end of messages section-->
</body>
</html>
