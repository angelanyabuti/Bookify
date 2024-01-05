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
  font-size:1.5rem;

}

.dropdown:hover .dropdown-content {
  display: block;
}
.button-nav {
    display: inline-block;
    background-color: white;
    border: none;
    color: black;
    text-align: center;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.button-nav:hover {
    background-color: #45a049;
}



    </style>
    <div class="header-2">
        <div class="flex">
            <a href="index.php" class="logo">Attic <span>Books</span></a>
            <nav class="navbar">
            <button class="button-nav" onclick="location.href='admin_panel.php'">home</button>
                <button class="button-nav" onclick="location.href='products.php'">products</button>
                <button class="button-nav" onclick="location.href='orders.php'">orders</button>
                <button class="button-nav" onclick="location.href='users.php'">users</button>
                <button class="button-nav" onclick="location.href='contacts.php'">messages</button>
        <div class="dropdown">
                <button class="button-nav">reports</button>
                <div class="dropdown-content">
                    <a href="users_report.php">Users</a>
                    <a href="products_report.php">Products</a>
                  <!--  <a href="bestSeller.php">Best Seller</a>-->
                    <a href="sales.php">Sales</a>
                    <a href="orders_report.php">Orders Report</a>
                  <!--  <a href="financial_report.php">Financial</a>-->

                </div>
            </div>
            </nav>

            <div class="icons">


                <!-- Code to fetch items from cart -->
                
            </div>

            <div class="dropdown">
                <button class="dropdown-toggle"><?php echo $_SESSION['admin_name'];?></button>
                <div class="dropdown-content">
                    <a href="profile.php">Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
  var dropdownToggle = document.querySelector(".dropdown-toggle");
  var dropdownContent = document.querySelector(".dropdown-content");

  dropdownToggle.addEventListener("click", function() {
    dropdownContent.classList.toggle("show");
  });

  window.addEventListener("click", function(event) {
    if (!event.target.matches(".dropdown-toggle")) {
      if (dropdownContent.classList.contains("show")) {
        dropdownContent.classList.remove("show");
      }
    }
  });
});

    </script>
</header>
