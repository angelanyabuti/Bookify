<!DOCTYPE html>
<html lang="en">
<head>
    <title>Users Report</title>
    
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
            <h1 class="title">Users Report</h1></div>

            <div class="filter-container">
                <form action="" method="GET">
                    <label for="user-type">Filter by User Type:</label>
                    <select name="user-type" id="user-type">
                        <option value="">All</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
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
                        <th>User Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query to fetch data from the database based on the filter
                    $filterUserType = isset($_GET['user-type']) ? $_GET['user-type'] : '';
                    $filterClause = $filterUserType ? "WHERE user_type = '$filterUserType'" : '';
                    $select_users = mysqli_query($conn, "SELECT * FROM users $filterClause") or die('query failed');
                    while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                        ?>
                        <tr>
                            <td><?php echo $fetch_users['fname']; ?></td>
                            <td><?php echo $fetch_users['email']; ?></td>
                            <td style="color:<?php echo ($fetch_users['user_type'] == 'admin') ? 'orange' : 'black'; ?>">
                                <?php echo $fetch_users['user_type']; ?>
                            </td>
                            <td>
                                <a href="users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Delete this user?');" class="delete-btn">Delete</a>
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
