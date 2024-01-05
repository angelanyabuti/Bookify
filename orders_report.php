<!DOCTYPE html>
<html lang="en">
<head>
    <title>Orders Report</title>
    
    <!-- CSS file links -->
    <link rel="stylesheet" href="orders.css" />
    <link rel="stylesheet" href="users.css" />
    <link rel="stylesheet" href="admin.css" />
    
    <style>
        .title-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .filter-container {
            display: flex;
            align-items: center;
        }

        .print-button {
            margin-left: auto;
        }

        @media print {
            .filter-container,
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <?php
    include 'config.php'; // Database connection
    session_start(); // Session start
    $admin_id = $_SESSION['admin_id']; // Retrieve stored session data
    
    // Check if user is logged in, if not, redirect back to login page
    if (!isset($admin_id)) {
        header('location:login.php');
    }
    
    // Code to delete user
    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        mysqli_query($conn, "DELETE FROM users WHERE id = '$delete_id'") or die('query failed');
        // Redirect to users.php
        header('location:users.php');
    }
    ?>
    
    <?php
    include 'admin_header.php';
    ?>
    
    <section class="users">
        <div class="title-container">
            <h1 class="title">Orders Report</h1>
        </div>

        <div class="filter-container">
            <form action="" method="GET">
                <label for="order-status">Filter by Status:</label>
                <select name="order-status" id="order-status">
                    <option value="">All</option>
                    <option value="received">Received</option>
                    <option value="pending">Pending</option>
                    <option value="in_transit">In Transit</option>
                    <option value="completed">Completed</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>
        <div class="print-button">
            <button onclick="window.print()">Print</button>
        </div>
    </div>

    <div class="box-container">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Placed On</th>
                    <th>Total Products</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to fetch data from the database based on the filter
                $filterStatus = isset($_GET['order-status']) ? $_GET['order-status'] : '';
                $filterClause = $filterStatus ? "WHERE orders.payment_status = '$filterStatus'" : '';
$select_orders = "SELECT users.fname,users.email,users.address,users.telephone,orders.payment_method,orders.total_price,orders.id,orders.payment_status,orders.user_id,orders.total_products,orders.placed_on
                FROM users 
                INNER JOIN orders ON users.id=orders.user_id $filterClause";

                $result = $conn->query($select_orders);
                while ($fetch_orders = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $fetch_orders['fname']; ?></td>
                        <td><?php echo $fetch_orders['email']; ?></td>
                        <td><?php echo $fetch_orders['placed_on']; ?></td>
                        <td><?php echo $fetch_orders['total_products']; ?></td>
                        <td><?php echo $fetch_orders['total_price']; ?></td>
                        <td><?php echo $fetch_orders['payment_method']; ?></td>
                        <td><?php echo $fetch_orders['payment_status']; ?></td>
                        <td>
                            <a href="users.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Delete this user?');" class="delete-btn">Delete</a>
                        </td>
                    </tr>
                <?php
                };
                ?>
            </tbody>
        </table>
    </div>
    </section>
    
    <script>
        function closePopup() {
            var popup = document.querySelector('.error-popup');
            popup.style.display = 'none';
        }
    </script>
    
</body>
</html>
