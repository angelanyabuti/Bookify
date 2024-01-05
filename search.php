<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
header('location:login.php');
}

//adding items to cart
if(isset($_POST['add_to_cart'])){
  $bk_id=$_POST['bk_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];
  //checks if book has already been added to the cart
    //$check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE id = '$bk_id' AND user_id = '$user_id'") or die('query failed');
    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE bk_id = '$bk_id' AND user_id = '$user_id'") or die('query failed');
  
   
    if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else {
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
  
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Search</title>
          <link rel="stylesheet" href="admin.css" />
          <link rel="stylesheet" href="index.css" />
          
  </head>
  <body>
    <!--code to display the message in a popup-->
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
   <h3>search page</h3>
   <p> <a href="index.php">home</a> / search </p>
</div>
<section class="search-form">
<form action="" method="post">
    <input type="text" name="search" placeholder="search products" class="box" required>
    <input type="submit" value="search" name="submit" class="btn">
</form>
</section>
<section class="products" style="padding-top:0;">
<!--code to search for a book-->
<div class="box-container">
<?php
if (isset($_POST['submit'])) {
  $search_item = $_POST['search'];
  $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name  LIKE  '%$search_item%' OR genre LIKE '%$search_item%'") or die('query failed');
  if(mysqli_num_rows($select_products) > 0){
     while($fetch_products = mysqli_fetch_assoc($select_products)){
      
?>
<form action="" method="post" class="box">
<img class="image" src="images/<?php echo $fetch_products['image']; ?>" alt="">
<div class="name"><?php echo $fetch_products['name']; ?></div>
<div class="price">KSH <?php echo $fetch_products['price']; ?>/-</div>
<?php if ($fetch_products['quantity'] > 0) { ?>
<input type="number" min="1" name="product_quantity" value="1" class="qty">
<!--used when adding items to cart-->
<input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
<input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
<input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
<input type="hidden" name="bk_id" value="<?php echo $fetch_products['bk_id']; ?>">
<input type="submit" value="add to cart" name="add_to_cart" class="btn">
<?php } else { ?>
                            <div class="out-of-stock">Out of Stock</div>
                        <?php } ?>

</form>
<?php
 }
}else{
   echo '<p class="empty">no result found!</p>';
}
}else {
echo '<p class="empty">search something</p>';
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


<!--js file link-->
<script src="js/header.js"></script>
<script src="Attic.js"></script>

  </body>
</html>
