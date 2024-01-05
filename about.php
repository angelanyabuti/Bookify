<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
//if a user is not logged in, it redirects to login page
if (!isset($user_id)) {
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>About</title>
    <!--Custom css files-->
    <link rel="stylesheet" href="admin.css" />
    <link rel="stylesheet" href="index.css" />
</head>
<body>
    <?php 
    include 'header.php';
    ?>
    <div class="heading">
        <h3>About Us</h3>
        <p><a href="index.php">Home</a> / About</p>
    </div>
    <section class="about">
        <div class="flex">
            <div class="image">
                <img src="images/pexels-gary-barnes-6231630.jpg" alt="">
            </div>
            <div class="content">
                <h3>Why Choose Us</h3>
                <p>Attic Books was born from a love for books. Books represent empowerment and education, full of wonderful stories fueling the imagination. Getting affordable used books in Nairobi Kenya can be a challenge. Due to this, Attic Books was founded in 2020. As a bookworm herself, the founder set about finding a solution.</p>
                <p>After trying various ideas, Attic Books started selling books online. This model proved successful, allowing more and more books to be accessible to bookworms in Kenya.</p>
                <a href="contact.php" class="btn">Contact Us</a>
            </div>
        </div>
    </section>
    <!--js file link-->
    <script src="js/header.js"></script>
    <?php 
    include 'footer.php';
    ?>
</body>
</html>
