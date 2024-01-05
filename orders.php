<?php
include 'config.php'; // Database connection
session_start(); // Start session
$admin_id = $_SESSION['admin_id']; // Retrieve stored session data

// Check if user is logged in, if not, redirect back to login page
if (!isset($admin_id)) {
    header('location:login.php');
}

// Code to update order
if (isset($_POST['update_order'])) {
    $order_update_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    mysqli_query($conn,"UPDATE orders SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
    $message[] = 'Payment status has been updated';
    $messageJSON = json_encode($message);
}

// Code to delete order
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM orders WHERE id = '$delete_id'") or die('query failed');
    // Redirect to orders.php
    header('location:orders.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Orders</title>
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

    <!-- Orders section starts here -->
    <section class="orders">
        <h1 class="title">Placed Orders</h1>
        <div class="box-container">
            <?php
            // Query to fetch data from the database
            $select_orders = "SELECT users.fname,users.email,users.address,users.telephone,orders.payment_method,orders.total_price,orders.id,orders.payment_status,orders.user_id,orders.total_products,orders.placed_on
            FROM users 
            INNER JOIN orders ON users.id=orders.user_id;";
            $result = $conn->query($select_orders);

            if (mysqli_num_rows($result) > 0) {
                while ($fetch_orders = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="box">
                        <p>user id :<span><?php echo $fetch_orders['user_id'];?></span></p>
                        <p>place on :<span><?php echo $fetch_orders['placed_on']; ?></span></p>
                        <p>name :<span><?php echo $fetch_orders['fname']; ?></span></p>
                        <p>number :<span><?php echo $fetch_orders['telephone']; ?></span></p>
                        <p>email :<span><?php echo $fetch_orders['email'] ?></span></p>
                        <p>address :<span><?php echo $fetch_orders['address']; ?></span></p>
                        <p>total products :<span><?php echo $fetch_orders['total_products']; ?></span></p>
                        <p>total price :<span>KSH <?php echo $fetch_orders['total_price']; ?></span></p>
                        <p>payment method :<span><?php echo $fetch_orders['payment_method']; ?></span></p>

                        <!-- Form to update order -->
                        <form action="" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                            <select name="update_payment">
                                <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                                <option value="pending">pending</option>
                                <option value="in transit">in transit</option>
                                <option value="completed">completed</option>
                            </select>
                            <input type="submit" value="Update" name="update_order" class="option-btn">
                            <a href="orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Delete this order?');" class="delete-btn">Delete</a>
                        </form>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">No orders placed yet!</p>';
            }
            ?>
        </div>
    </section>
    <!-- Orders section ends here -->

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
