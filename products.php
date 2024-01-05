<?php
include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

if (isset($_POST['add_product'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $ISBN = mysqli_real_escape_string($conn, $_POST['ISBN']);
   $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
   $genre = mysqli_real_escape_string($conn, $_POST['genre']);
   $edition = mysqli_real_escape_string($conn, $_POST['edition']);
   $year = mysqli_real_escape_string($conn, $_POST['year']);
   $price = $_POST['price'];
   // Generate a date string
   $date = date('Y-m-d H:i:s', time());
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   // Adds image to images folder
   $image_folder = 'images/' . $image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM products WHERE name = '$name'") or die('query failed');

   if (mysqli_num_rows($select_product_name) > 0) {
      $message[] = 'Product name already added';
   } else {
      $add_product_query = mysqli_query($conn, "INSERT INTO products(name, price, ISBN, quantity, genre, edition, year, image, date) VALUES('$name','$price','$ISBN','$quantity','$genre','$edition','$year','$image','$date') ") or die('query failed');

      if ($add_product_query) {
         if ($image_size > 2000000) {
            $message[] = 'Image size is too large';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Product added';
         }
      } else {
         $message[] = 'Product could not be added';
      }
   }

   $messageJSON = json_encode($message);
}

// Code to delete product
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM products WHERE bk_id = '$delete_id'") or die('query failed');
   mysqli_query($conn, "DELETE FROM products WHERE bk_id ='$delete_id' ") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('images/' . $fetch_delete_image['image']);
   header('location:products.php');
}

// Code to update product
if (isset($_POST['update_product'])) {
   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];
   $update_ISBN = $_POST['update_ISBN'];
   $update_quantity = $_POST['update_quantity'];
   $update_genre = $_POST['update_genre'];
   $update_edition = $_POST['update_edition'];
   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'images/' . $update_image;
   $update_old_image = $_POST['update_old_image'];

   mysqli_query($conn, "UPDATE products SET name = '$update_name', price = '$update_price', ISBN = '$update_ISBN', edition = '$update_edition' , quantity = '$update_quantity', genre = '$update_genre' WHERE bk_id = '$update_p_id'") or die('query failed');

   if (!empty($update_image)) {
      if ($update_image_size > 2000000) {
         $message[] = 'Image file is too large';
      } else {
         mysqli_query($conn, "UPDATE products SET image = '$update_image' WHERE bk_id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('images/' . $update_old_image);
      }
   }

   header('location:products.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <title>Products</title>

   <!-- CSS file link -->
   <link rel="stylesheet" href="admin.css" />
   <style>
      .show-products .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, 30rem);
         justify-content: center;
         gap: 1.5rem;
         max-width: 1200px;
         margin: 0 auto;
         align-items: flex-start;
      }
      .show-products .box-container .box {
         text-align: center;
         padding: 2rem;
         border-radius: 0.5rem;
         border: var(--border);
         box-shadow: var(--box-shadow);
         background-color: var(--white);
      }
   </style>
</head>
<body>
   <!-- Code to display messages in a pop-up -->
   <div id="popup" class="popup-container">
      <div class="popup-content">
         <span id="popup-message"></span>
         <button id="close-button" class="btn">Close</button>
      </div>
   </div>

   <?php include 'admin_header.php'; ?>

   <!-- Products form starts here -->
   <section class="add-products">
      <h1 class="title">Shop Products</h1>
      <form action="" method="post" enctype="multipart/form-data">
         <h3>Add Product</h3>
         <input type="text" name="name" class="box" placeholder="Enter product name" required>
         <input type="number" min="0" name="price" class="box" placeholder="Enter product price" required>
         <input type="number" min="0" name="ISBN" class="box" placeholder="Enter product ISBN" required>
         <input type="text" name="author" class="box" placeholder="Enter product author" required>
         <input type="number" min="0" name="quantity" class="box" placeholder="Enter product quantity" required>
         <input type="text" name="genre" class="box" placeholder="Enter product genre" required>
         <input type="text" name="edition" class="box" placeholder="Enter edition" required>
         <select name="year" id="yearDropdown" class="box">
            <option value="">Select Year of Publication</option>
         </select>
         <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
         <input type="submit" value="Add Product" name="add_product" class="btn">
      </form>
   </section>
   <!-- Products form ends here -->

   <!-- Show products -->
   <section class="show-products">
      <div class="box-container">
         <?php
         $select_products = mysqli_query($conn, "SELECT * FROM products") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <div class="box">
                  <!-- Display product image on the page -->
                  <img src="images/<?php echo $fetch_products['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_products['name']; ?></div>
                  <div class="price">KSH<?php echo $fetch_products['price']; ?>/-</div>
                  <a href="products.php?update=<?php echo $fetch_products['bk_id']; ?>" class="option-btn">Update</a>
                  <a href="products.php?delete=<?php echo $fetch_products['bk_id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
               </div>
            <?php
            }
         } else {
            echo '<p class="empty">No product added yet</p>';
         }
         ?>
      </div>
   </section>

   <section class="edit-product-form">
      <?php
      if (isset($_GET['update'])) {
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM products WHERE bk_id = '$update_id'") or die('query failed');
         if (mysqli_num_rows($update_query) > 0) {
            while ($fetch_update = mysqli_fetch_assoc($update_query)) {
      ?>
               <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['bk_id']; ?>">
                  <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">

                  <img src="images/<?php echo $fetch_update['image']; ?>" alt=""><br>
                  <label for="name">Product Name</label>
                  <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Enter product name">
                  <label for="name">Product Price</label>
                  <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="Enter product price">
                  <label for="name">Product ISBN</label>
                  <input type="number" name="update_ISBN" value="<?php echo $fetch_update['ISBN']; ?>" min="0" class="box" required placeholder="Enter product ISBN">
                  <label for="name">Product Quantity</label>
                  <input type="number" name="update_quantity" value="<?php echo $fetch_update['quantity']; ?>" min="0" class="box" required placeholder="Enter product quantity">
                  <label for="name">Product Genre</label>
                  <input type="text" name="update_genre" value="<?php echo $fetch_update['genre']; ?>" min="0" class="box" required placeholder="Enter product genre">
                  <label for="name">Product Edition</label>
                  <input type="number" name="update_edition" value="<?php echo $fetch_update['Edition']; ?>" min="0" class="box" required placeholder="Enter product edition">
                  <label for="name">Product Image</label>
                  <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
                  <input type="submit" value="Update" name="update_product" class="btn">
                  <input type="reset" value="Cancel" id="close-update" class="option-btn">
               </form>
      <?php
            }
         }
      } else {
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
      ?>
   </section>

   <!-- Custom admin JS file link -->
   <script src="Attic.js"></script>

   <!-- Code to add years to the selection -->
   <script>
      var dropdown = document.getElementById("yearDropdown");
      var currentYear = new Date().getFullYear();
      var startYear = 1900;
      var endYear = currentYear;

      for (var year = startYear; year <= endYear; year++) {
         var option = document.createElement("option");
         option.text = year;
         option.value = year;
         dropdown.add(option);
      }
   </script>

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
