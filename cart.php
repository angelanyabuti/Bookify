<?php
include 'config.php'; // Database connection
session_start(); // Start session
$user_id = $_SESSION['user_id']; // Retrieve stored session data

// Check if the user is logged in, if not, redirect back to the login page
if (!isset($user_id)) {
    header('location:login.php');
}

// Code to update the cart quantity
if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];

    // Query to update cart
    mysqli_query($conn, "UPDATE cart SET quantity ='$cart_quantity' WHERE id = '$cart_id'") or die('query failed');

    //$message[] array is used to store several messages
    $message[] = 'Cart quantity updated!';
    $messageJSON = json_encode($message);
}

// Deleting a single item from the cart
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM cart WHERE id = '$delete_id'") or die('query failed');
    $message[] = 'Items deleted successfully!';
    header('location:cart.php');
}

// Deleting all items from the cart
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'") or die('query failed');
    $message[] = 'Items deleted successfully!';
    header('location:cart.php');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cart</title>
    <!-- Custom CSS file -->
    <link rel="stylesheet" href="admin.css" />
    <link rel="stylesheet" href="index.css" />
</head>
<body>
    <!-- Code to display messages in a pop-up -->
    <div id="popup" class="popup-container">
        <div class="popup-content">
            <span id="popup-message"></span>
            <button id="close-button" class="btn">Close</button>
        </div>
    </div>

    <?php 
    include 'header.php'; // Header file
    ?>

    <div class="heading">
        <h3>Shopping cart</h3>
        <p><a href="index.php">home</a> /cart </p>
    </div>

    <!-- Cart section starts -->
    <section class="shopping-cart">
        <h1 class="title">Products added</h1>
        <div class="box-container">
            <?php
            $grand_total = 0;
            // Query to fetch data from the database
            $select_cart = "SELECT cart.quantity, cart.id, products.name, products.image, products.price
                            FROM products 
                            INNER JOIN cart ON products.bk_id = cart.bk_id
                            WHERE cart.user_id = '$user_id';";
            
            // The result is assigned to the variable $result
            $result = $conn->query($select_cart);

            if (mysqli_num_rows($result) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($result)) {
            ?>
            <div class="box">
                <img class="image" src="images/<?php echo $fetch_cart['image']; ?>" alt="">
                <div class="name"><?php echo $fetch_cart['name']; ?></div>
                <div class="price"><?php echo $fetch_cart['price']; ?></div>

                <form action="" method="post">
                    <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                    <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                    <input type="submit" name="update_cart" value="Update" class="option-btn">
                </form>

                <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('Delete this from cart?');">Delete</a>

                <div class="sub-total">sub total: <span>KES<?php echo $sub_total = ($fetch_cart['quantity'] *$fetch_cart['price'] );?>/-</span></div>
            </div>
            <?php
                $grand_total += $sub_total;
                }
            } else {
                echo '<p class="empty">Your cart is empty</p>';
            }
            ?>
        </div>

        <div style="margin-top: 2rem; text-align:center;">
            <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)? '':'disabled';?>" onclick="return confirm('Delete all from cart?');">Delete All</a>
        </div>

        <div class="cart-total">
            <p>grand total : <span>KES<?php echo $grand_total; ?>/-</span></p>
            <div class="flex">
                <a href="shop.php" class="option-btn">continue shopping</a>
                <a href="checkout.php" class="btn <?php echo ($grand_total > 1)? '':'disabled';?>">proceed to checkout</a>
            </div>
        </div>
    </section>
    <!-- Cart section ends -->

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

    <?php 
    include 'footer.php';
    ?>

    <!-- JS file link -->
    <script src="js/header.js"></script>
</body>
</html>
