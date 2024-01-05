<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

// Check if the user is logged in, if not, redirect back to the login page
if (!isset($user_id)) {
   header('location:login.php');
}


// Adding items to cart
if (isset($_POST['add_to_cart'])) {
   $bk_id = $_POST['bk_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   // Check if book has already been added to the cart
   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE bk_id = '$bk_id' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'Already added to cart!';
   } else {
      // Get the available stock quantity for the product
      $stockQuery = mysqli_query($conn, "SELECT quantity FROM products WHERE bk_id = $bk_id");
      $row = mysqli_fetch_assoc($stockQuery);
      $availableStock = $row['quantity'];

      if ($product_quantity <= 0) {
         $message[] = 'Product is out of stock!';
      } elseif ($product_quantity > $availableStock) {
         $message[] = 'Requested quantity exceeds the available stock!';
      } else {
         mysqli_query($conn, "INSERT INTO `cart`(user_id, quantity, bk_id) VALUES('$user_id', '$product_quantity', (SELECT products.bk_id FROM products WHERE products.bk_id = $bk_id LIMIT 1))") or die('query failed');
         $message[] = 'Product added to cart!';
      }
   }

   $messageJSON = json_encode($message);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <title>Shop</title>
   <link rel="stylesheet" href="admin.css" />
   <link rel="stylesheet" href="index.css" />
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
   include 'header.php';
   ?>
   <div class="heading">
      <h3>our products</h3>
      <p> <a href="index.php">home</a> / shop </p>
   </div>
   <section class="products">
      <h1 class="title">latest products</h1>
      <div class="box-container">
         <?php  
         // Code to select items from products table
         $select_products = mysqli_query($conn, "SELECT * FROM `products` ORDER BY bk_id DESC") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <form action="" method="post" class="box">
                  <img class="image" src="images/<?php echo $fetch_products['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_products['name']; ?></div>
                  <div class="price">KSH <?php echo $fetch_products['price']; ?>/-</div>
                  <?php if ($fetch_products['quantity'] > 0) { ?>
                     <input type="number" min="1" name="product_quantity" value="1" class="qty">
                     <!-- Used when adding items to cart -->
                     <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                     <input type="hidden" name="bk_id" value="<?php echo $fetch_products['bk_id']; ?>">
                     <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                     <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                     <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                  <?php } else { ?>
                     <div class="out-of-stock">Out of Stock</div>
                  <?php } ?>
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">No products added yet!</p>';
         }
         ?>
      </div>
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
   <!-- JavaScript file link -->
   <script src="js/header.js"></script>
</body>
</html>
