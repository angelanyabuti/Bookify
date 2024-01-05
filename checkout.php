<?php
include 'config.php'; //database connection
session_start(); //start session
$user_id = $_SESSION['user_id']; //retrive stored session data

//checks if user is logged in, if not, redirects them back to login page
if (!isset($user_id)) {
header('location:login.php');
}

//code to place order, insert data to orders table
if (isset($_POST['order'])) {
  $method = mysqli_real_escape_string($conn,$_POST['method']);
  $grand_total = mysqli_real_escape_string($conn,$_POST['total']);

  $placed_on = date('Y-m-d H:i:s', time());

  $cart_total = 0;
  $cart_products[] = '';

  $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
  if (mysqli_num_rows($cart_query) > 0) {
    while ($cart_item = mysqli_fetch_assoc($cart_query)) {
      $cart_products = $cart_item['quantity'];
      $sub_total = ( $cart_item['quantity']);
      $cart_total += $sub_total;
    }
  }

  $total_products = ($cart_total);
  $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE payment_method='$method' AND total_products='$total_products' AND total_price='$grand_total'") or die ('query failed');
  if ($cart_total == 0) {
    echo 'your cart is empty';
  } else {
    if (mysqli_num_rows($order_query) > 0) {
      $message[] ='order already placed';
    } else {
      mysqli_query($conn, "INSERT INTO orders(user_id, payment_method, total_products, total_price, placed_on) VALUES('$user_id','$method', '$total_products', '$grand_total','$placed_on')");
      $message[] ='order placed';
      mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'") or die('query failed');
    }
    
  }
  $messageJSON = json_encode($message);


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
   <h3>checkout</h3>
   <p> <a href="index.php">home</a> / checkout </p>
</div>
<section class="display-order">
    <?php 
    $grand_total = 0;
    $select_cart="SELECT cart.quantity,cart.id, cart.user_id, products.name,products.image,products.price
    FROM products  
    INNER JOIN cart   ON products.bk_id=cart.bk_id
    WHERE cart.user_id = $user_id;";
           $result = $conn->query($select_cart);

   // $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($result) > 0) {
        while ($fetch_cart = mysqli_fetch_assoc($result)) {
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
           ?>
<p><?php echo $fetch_cart['name'];?><span>(<?php echo 'KES'. $fetch_cart['price'] .'/-'.'x'. $fetch_cart['quantity'];?>)</span> </p>
           <?php
            }
          } else {
              echo '<p class="empty">your cart is empty</p>';
          }
          ?>


       
<div class="grand-total">grand total
  <span>KES<?php echo $grand_total?>/-</span>

</div>

</section>
<?php
      $select_orders =  mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'") or die('query failed');
      

if (mysqli_num_rows($select_orders) > 0) {
     while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
 ?>
<section class="checkout">
    <div class="box">
<p>Name :<span><?php echo $fetch_orders['fname'];?></span></P>
<p>Number :<span><?php echo $fetch_orders['Telephone']; ?></span></P>
<p>Email :<span><?php echo $fetch_orders['email']; ?></span></P>
<p>Address :<span><?php echo $fetch_orders['Address']; ?></span></P>

<form action="" method="post">
  <select name="method" required>
    <option value="" selected disabled>Payment Method </option>
  <option value="mpesa">mpesa</option>
  <option value="cash on delivery">cash on delivery</option>
    </select>
    <input type="hidden" name="total" value="<?php echo $grand_total ?>">

    <input type="submit" value="order" name="order" class="option-btn">
        </form>
</div>

</section>
<?php
 }
}else {
echo '<p class="empty">no orders placed yet!</p>';
}
?>
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
  </body>
</html>
