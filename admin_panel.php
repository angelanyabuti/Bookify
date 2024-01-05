<?php
include 'config.php'; //database connection file
session_start(); //start session
$admin_id = $_SESSION['admin_id']; //retrieve stored session data

// checks if user is logged in, if not, redirects back to login page
if (!isset($admin_id)) {
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin panel</title>
    <!-- CSS file link -->
    <link rel="stylesheet" href="admin.css" />
    
</head>
<body>
    <?php 
    include 'admin_header.php'; //header file for admin page
    ?>

    <!-- Admin dashboard section starts -->
    <section class="dashboard">
        <h1 class="title">Dashboard</h1>
        <div class="box-container">
            <div class="box">
                <?php
                $total_pendings = 0;
                //query used to fetch data from database
                $select_pending = mysqli_query($conn, "SELECT total_price FROM orders WHERE payment_status = 'pending'") or die('query failed');
                //mysqli_num_rows is used here to return the number of rows obtained from the above query
                if (mysqli_num_rows($select_pending) > 0) {
                    //mysqli_fetch_assoc fetches the next row from the result($select_pending) as an associative array and assigns it to the variable $fetch_pendings
                    while ($fetch_pendings = mysqli_fetch_assoc($select_pending)) {
                        $total_price = $fetch_pendings['total_price'];
                        $total_pendings += $total_price;
                    }
                }
                ?>
                <h3><?php echo $total_pendings; ?></h3>
                <p>Total Pendings</p>
            </div>
            <div class="box">
                <?php
                $total_completed = 0;
                //query to fetch data from database
                $select_completed = mysqli_query($conn, "SELECT total_price FROM orders WHERE payment_status = 'completed'") or die('query failed');
                if (mysqli_num_rows($select_completed) > 0) {
                    while ($fetch_completed = mysqli_fetch_assoc($select_completed)) {
                        $total_price = $fetch_completed['total_price'];
                        $total_completed += $total_price;
                    }
                }
                ?>
                <h3><?php echo $total_completed; ?></h3>
                <p>Completed Orders</p>
            </div>
            <div class="box">
                <?php
                //query to fetch data from the database
                $select_orders = mysqli_query($conn, "SELECT * FROM orders") or die('query failed');
                $number_of_orders = mysqli_num_rows($select_orders);
                ?>
                <h3><?php echo $number_of_orders; ?></h3>
                <p>Order Placed</p>
            </div>
            <div class="box">
                <?php
                //query to fetch data from the database
                $select_products = mysqli_query($conn, "SELECT * FROM products") or die('query failed');
                $number_of_products = mysqli_num_rows($select_products);
                ?>
                <h3><?php echo $number_of_products; ?></h3>
                <p>Products Added</p>
            </div>
            <div class="box">
                <?php
                //query to fetch data from the database
                $select_users = mysqli_query($conn, "SELECT * FROM users WHERE user_type='user'") or die('query failed');
                $number_of_users = mysqli_num_rows($select_users);
                ?>
                <h3><?php echo $number_of_users; ?></h3>
                <p>Normal Users</p>
            </div>
            <div class="box">
                <?php
                //query to fetch data from the database
                $select_admins = mysqli_query($conn, "SELECT * FROM users WHERE user_type='admin'") or die('query failed');
                $number_of_admins = mysqli_num_rows($select_admins);
                ?>
                <h3><?php echo $number_of_admins; ?></h3>
                <p>Admin Users</p>
            </div>
            <div class="box">
                <?php
                //query to fetch data from the database
                $select_account = mysqli_query($conn, "SELECT * FROM users") or die('query failed');
                $number_of_account = mysqli_num_rows($select_account);
                ?>
                <h3><?php echo $number_of_account; ?></h3>
                <p>Total Users</p>
            </div>
            <div class="box">
                <?php
                //query to fetch data from the database
                $select_messages = mysqli_query($conn, "SELECT * FROM message") or die('query failed');
                $number_of_messages = mysqli_num_rows($select_messages);
                ?>
                <h3><?php echo $number_of_messages; ?></h3>
                <p>New Messages</p>
            </div>
        </div>
    </section>
    <!-- Admin dashboard section ends -->
</body>
</html>
