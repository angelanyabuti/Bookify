<?php
include 'config.php'; // Database connection
session_start(); // Start session
$user_id = $_SESSION['user_id']; // Retrieve stored session data

// Check if user is logged in, if not, redirect back to login page
if (!isset($user_id)) {
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Orders</title>
    <link rel="stylesheet" href="admin.css" />
    <link rel="stylesheet" href="index.css" />
</head>
<body>
    <?php 
    include 'header.php'; // Header file
    ?>
    <div class="heading">
        <h3>Placed Orders</h3>
        <p><a href="index.php">Home</a> / Orders</p>
    </div>

    <!--beginning of orders section-->
    <section class="placed-orders">
        <h1 class="title">Placed Orders</h1>
        <div class="box-container">
            <?php
            $user_id = $_SESSION['user_id'];
            // Query to fetch data from the database
            $select_orders = "SELECT users.fname, users.email, users.address, users.telephone, orders.payment_method, orders.total_price, orders.id, orders.payment_status, orders.user_id, orders.total_products, orders.placed_on
            FROM users 
            INNER JOIN orders ON users.id = orders.user_id
            WHERE orders.user_id = $user_id";

            $result = $conn->query($select_orders);

            if (mysqli_num_rows($result) > 0) {
                while ($fetch_orders = mysqli_fetch_assoc($result)) {
            ?>
            <div class="box">
                <p>Place on: <span><?php echo $fetch_orders['placed_on']; ?></span></p>
                <p>Name: <span><?php echo $fetch_orders['fname']; ?></span></p>
                <p>Number: <span><?php echo $fetch_orders['telephone']; ?></span></p>
                <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>
                <p>Address: <span><?php echo $fetch_orders['address']; ?></span></p>
                <p>Total Products: <span><?php echo $fetch_orders['total_products']; ?></span></p>
                <p>Total Price: <span>KSH:<?php echo $fetch_orders['total_price']; ?> </span></p>
                <p>Payment Method: <span><?php echo $fetch_orders['payment_method']; ?></span></p>
                <p>Status: <span><?php echo $fetch_orders['payment_status']; ?></span></p>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">No orders placed</p>';
            }
            ?>
        </div>
    </section>
    <!--end of orders section-->
    <?php 
    include 'footer.php';
    ?>

    <!-- JS file link -->
    <script src="js/header.js"></script>
</body>
</html>
