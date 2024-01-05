
<?php
        if (isset($_POST["submit"])) {
         require_once "config.php";

           $fname = mysqli_real_escape_string($conn, $_POST['fname']);
           $lname = mysqli_real_escape_string($conn, $_POST['lname']);
           $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
           $email = mysqli_real_escape_string($conn, $_POST['email']);
           $password = mysqli_real_escape_string($conn, ($_POST['password']));
           $userType = mysqli_real_escape_string($conn, $_POST['user_type']);
           $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
         

           $errors = array();
           
           if (empty($fname) OR empty($lname) OR empty($telephone)  OR empty($email) OR empty($password) ) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 characters long");
           }
           $sql = "SELECT * FROM users WHERE email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                //echo "$error";
            }
           }else{
            
            $sql = "INSERT INTO users (fname, lname, telephone,email, password, user_type) VALUES ( ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"ssssss",$fname, $lname, $telephone, $email, $hashedPassword, $userType);
                mysqli_stmt_execute($stmt);
                $message[] = 'Admin user added successfully!';
                $messageJSON = json_encode($message);
               }else{
                die("Something went wrong");
            }
           }
           // Pass the error messages to JavaScript
echo "<script>";
echo "var errors = " . json_encode($errors) . ";";
echo "</script>";
          
// Your PHP code to check and store the error messages goes here

// Display the error messages in a pop-up
if (count($errors) > 0) {
  echo "<div class='error-popup'>";
  foreach ($errors as $error) {
    echo "<p>$error</p>";
  }
  echo "<button class='btn' onclick='closePopup()'>Close</button>";
  echo "</div>";
}
        }
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Users</title>

    <!-- CSS file links -->
    <link rel="stylesheet" href="orders.css" />
    <link rel="stylesheet" href="users.css" />
    <link rel="stylesheet" href="admin.css" />

    <style>
      .title-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.title {
    margin: 0;
    text-align: center;
    flex-grow: 1;
}

.btn {
    margin-left: 10px;
}


      .error-popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 20px;
      background-color: #f8d7da;
      border: 1px solid #f5c6cb;
      border-radius: 5px;
      text-align: center;
      font-size: 18px;
    }
        /* Modal styles */
        .modal {
          min-height: 100vh;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  overflow-y: scroll;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1200;
  width: 100%;
  max-height: 80vh; /* Set maximum height for the pop-up container */
  overflow-y: auto; /* Enable vertical scrolling */
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
          min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  overflow-y: scroll;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1200;
  width: 100%;
  max-height: 80vh; /* Set maximum height for the pop-up container */
  overflow-y: auto; /* Enable vertical scrolling */
        }
        .modal-content form{
          width: 50rem;
  padding: 2rem;
  text-align: center;
  border-radius: 0.5rem;
  background-color: var(--white);
  max-height: 80vh; /* Set maximum height for the pop-up container */
  overflow-y: auto; /* Enable vertical scrolling */
        }
        .modal-content form label{
          font-size:2rem;
        }
.modal-content form .box{
  
  margin: 1rem 0;
  padding: 1.2rem 1.4rem;
  border: var(--border);
  border-radius: 0.5rem;
  background-color: var(--light-bg);
  font-size: 1.8rem;
  color: var(--black);
  width: 100%;

}
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
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
include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}
//code to delete user
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id = '$delete_id'") or die('query failed');
    //redirects to users.php
    header('location:users.php');
}
?>

<?php
include 'admin_header.php';
?>

<section class="users">
<div class="title-container">
        <h1 class="title">User Accounts</h1>
        <input type="button" value="Add Admin" class="btn" id="addAdminBtn">
    </div>

    <!-- Modal -->
    <div id="addAdminModal" class="modal">
        <div class="modal-content">
            <!-- Form elements for adding admin -->
            <h2>Add Admin</h2>
            <form action="" method="POST">
            <span class="close">&times;</span><br><br>
                <label for="adminName">First Name:</label>
                <input type="text" id="fname" name="fname" value="<?php echo isset($_POST['fname']) ? $_POST['fname'] : ''; ?>" class="box" required>
                <label for="adminName">Last Name:</label>
                <input type="text" id="lname" name="lname" value="<?php echo isset($_POST['lname']) ? $_POST['lname'] : ''; ?>" class="box" required>
                <label for="adminEmail"> Email:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" class="box" required>
                <label for="adminEmail"> Telephone:</label>
                <input type="number" id="telephone" name="telephone" value="<?php echo isset($_POST['telephone']) ? $_POST['telephone'] : ''; ?>" class="box" required>
                <label for="adminEmail">Password:</label>
                <input type="password" id="password" name="password" class="box" required>
                <input type="hidden" name="user_type" value="admin">
                <input type="submit" value="Add" class="btn" name="submit">
            </form>
            <!-- End of form elements -->

        </div>
    </div>

    <div class="box-container">
        <?php
        $select_users = mysqli_query($conn, "SELECT * FROM users") or die('query failed');
        while ($fetch_users = mysqli_fetch_assoc($select_users)) {
            ?>
            <div class="box">
                <p>username: <span><?php echo $fetch_users['fname']; ?></span></p>
                <p>email: <span><?php echo $fetch_users['email']; ?></span></p>
                <!--changes user type admin color to orange-->
                <p>user type: <span
                            style="color:<?php if ($fetch_users['user_type'] == 'admin') {
                                echo 'orange';
                            } ?>"><?php echo $fetch_users['user_type']; ?></span></p>
                <a href="users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('delete this user?');"
                   class="delete-btn">delete</a>
            </div>
            <?php
        };
        ?>
    </div>
</section>
<script>
    function closePopup() {
      var popup = document.querySelector('.error-popup');
      popup.style.display = 'none';
    }
  </script>
<!-- JavaScript code -->
<script>
    // Get the modal container
    var modal = document.getElementById("addAdminModal");

    // Get the button that opens the modal
    var btn = document.getElementById("addAdminBtn");

    // Get the close button
    var closeBtn = document.querySelector(".modal .close");

    // When the button is clicked, open the modal
    btn.addEventListener("click", function () {
        modal.style.display = "block";
    });

    // When the close button or outside the modal is clicked, close the modal
    closeBtn.addEventListener("click", closeModal);
    window.addEventListener("click", outsideClick);

    function closeModal() {
        modal.style.display = "none";
    }

    function outsideClick(event) {
        if (event.target === modal) {
            closeModal();
        }
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
