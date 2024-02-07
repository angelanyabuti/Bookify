<!DOCTYPE html>
<html lang="en">
<head>
   <title>Attic</title>
   <!-- CSS file links -->
   <link rel="stylesheet" href="admin.css" />
   <link rel="stylesheet" href="index.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
   <!--Slick slider for the product slider-->
   <!-- Slick Slider CSS -->
   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
   <!-- Slick Slider Theme CSS (optional) -->
   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>
<body>
   <?php
   include 'config.php';
   session_start();

   if(isset($_SESSION['user_id'])){
      $user_id = $_SESSION['user_id'];

      // Adding items to cart
      if(isset($_POST['add_to_cart'])){
         $bk_id=$_POST['bk_id'];
         $product_name = $_POST['product_name'];
         $product_price = $_POST['product_price'];
         $product_image = $_POST['product_image'];
         $product_quantity = $_POST['product_quantity'];

         // Check if book has already been added to the cart
         $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE bk_id = '$bk_id' AND user_id = '$user_id'") or die('query failed');

         if(mysqli_num_rows($check_cart_numbers) > 0){
            $message[] = 'Already added to cart!';
         } else {
            // Get the available stock quantity for the product
            $stockQuery = mysqli_query($conn, "SELECT quantity FROM products WHERE bk_id = '$bk_id'");
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
   } else {
      // User is not logged in, handle the error or redirect to login page
      header('location:login.php');
   }
   ?>

   <!-- Code to display the message in a popup -->
   <div id="popup" class="popup-container">
      <div class="popup-content">
         <span id="popup-message"></span>
         <button id="close-button" class="btn">Close</button>
      </div>
   </div>

   <?php include 'header.php'; ?>

   <section class="home">
      <div class="content">
         <h3>Hand Picked Book to your door.</h3>
         <p></p>
         <a href="about.php" class="white-btn">discover more</a>
      </div>
   </section>
   <div class="box-container">
   <section class="products">
      <h1 class="title">latest products</h1>
      <div id="product-slider" class="slider-container">
      <div class="box-container">
         <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products` ORDER BY bk_id DESC LIMIT 6") or die('query failed');
            if(mysqli_num_rows($select_products) > 0){
               while($fetch_products = mysqli_fetch_assoc($select_products)){
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
      </div>
      
      <div class="load-more" style="margin-top: 2rem; text-align:center">
         <a href="shop.php" class="option-btn">Load More</a>
      </div>
   </section>
   </div>
  

   <section class="about">
      <div class="flex">
         <div class="image">
            <img src="images/pexels-gary-barnes-6231630.jpg" alt="">
         </div>
         <div class="content">
            <h3>About Us</h3>
            <p>Attic Books was born from a love for books. Books represent empowerment and education, full of wonderful stories fueling the imagination. Getting affordable used books in Nairobi, Kenya, can be a challenge. Due to this, Attic Books was founded in 2020. A bookworm herself, the founder set about finding a solution.</p>
            <a href="about.php" class="btn">Read More</a>
         </div>
      </div>
   </section>

   <section class="home-contact">
      <div class="content">
         <h3>Have any questions?</h3>
         <h2>Reach out to us!</h2>
         <a href="contact.php" class="white-btn">Contact Us</a>
      </div>
   </section>

   <?php include 'footer.php'; ?>
   <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Slick Slider JS -->
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<!-- JavaScript file link -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="js/header.js"></script>

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
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
   <script src="js/header.js"></script>
   <!--slick slider for the product slider-->
   <!-- jQuery -->
   <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
   <!-- Slick Slider JS -->
   <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</body>
</html>
