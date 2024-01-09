<?php
include 'config.php';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function generateLoginRegisterLinks() {
    $currentPage = basename($_SERVER['PHP_SELF']);
    if ($currentPage !== 'login.php') {
        echo '<a href="login.php">Login | </a>';
    }
    if ($currentPage !== 'signup.php') {
        echo '<a href="signup.php">Register</a>';
    }
}


function generateLoggedInHeader() {
    global $conn, $user_id;
}
    // Fetch cart items count
    $select_cart_number = mysqli_query($conn, "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = '$user_id'") or die('Query failed');
    $row = mysqli_fetch_assoc($select_cart_number);
    $cart_count = $row['cart_count'];

    ?>

    <header class="header">
        <style>
            .dropdown {
                position: relative;
                display: inline-block;
            }

            .dropdown-toggle {
                background-color: transparent;
                border: none;
                cursor: pointer;
                outline: none;
                font-size:2rem;
            }

            .dropdown-content {
                display: none;
                position: absolute;
                top: 100%;
                right: 0;
                background-color: #f9f9f9;
                min-width: 160px;
                box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
                z-index: 1;
            }

            .dropdown-content a {
                color: black;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
                font-size: 1.5rem;
            }

            .dropdown:hover .dropdown-content {
                display: block;
            }
        </style>
        <div class="header-2">
            <div class="flex">
                <a href="index.php" class="logo"><img src="images/logo.png" alt="" srcset=""></a>
                <nav class="navbar">
                    <a href="index.php">home</a>
                    <a href="about.php">about us</a>
                    <a href="shop.php">shop</a>
                    <a href="contact.php">contact</a>
                    <a href="my_orders.php">orders</a>
                </nav>

                <div class="icons">
                    <a href="search.php">search</a>

                    <div id="menu-btn" class="menu"><img src="images/hamburger.png" alt="" srcset=""></div>

                    <?php if (isLoggedIn()): ?>
                        <!-- Code to fetch items from cart -->
                        <a href="cart.php">cart<span>(<?php echo $cart_count; ?>)</span></a>
                        <div class="dropdown">
                            <button class="dropdown-toggle"><?php echo $_SESSION['user_name']; ?></button>
                            <div class="dropdown-content">
                                <a href="user_profile.php">Profile</a>
                                <a href="bookclub.php">Bookclub</a>
                                <a href="logout.php">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Login and Register links -->
                        <?php generateLoginRegisterLinks(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var dropdownToggle = document.querySelector(".dropdown-toggle");
                var dropdownContent = document.querySelector(".dropdown-content");

                dropdownToggle.addEventListener("click", function () {
                    dropdownContent.classList.toggle("show");
                });

                window.addEventListener("click", function (event) {
                    if (!event.target.matches(".dropdown-toggle")) {
                        if (dropdownContent.classList.contains("show")) {
                            dropdownContent.classList.remove("show");
                        }
                    }
                });
            });
        </script>
    </header>
        