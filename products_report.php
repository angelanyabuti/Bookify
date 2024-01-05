<!DOCTYPE html>
<html lang="en">
<head>
    <title>Products</title>
    <!-- CSS file links -->
    <link rel="stylesheet" href="admin.css" />
    <style>
         @media print {
            .filter-container {
                display: none;
            }
    </style>
</head>
<body>
<?php
include 'config.php'; // Database connection
session_start(); // Start session
$admin_id = $_SESSION['admin_id']; // Retrieve stored session data

// Check if user is logged in, if not, redirect back to login page
if (!isset($admin_id)) {
    header('location:login.php');
}
?>

<?php
include 'admin_header.php'; // Admin header page
?>

<!-- Show products -->
<section class="show-products">
    <div class="title-container">
        <h1 class="title">Products Report</h1>
        <div class="filter-container">
            <form action="" method="GET">
                <label for="product-genre">Filter by Genre:</label>
                <select name="product-genre" id="product-genre">
                    <option value="">All</option>
                    <option value="Fiction">Fiction</option>
                    <option value="Non-Fiction">Non-Fiction</option>
                    <option value="Mystery">Mystery</option>
                    <option value="Thriller">Thriller</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>
        <div class="print-button">
            <button onclick="window.print()">Print</button>
        </div>
    </div>
    <div class="box-container">
        <div class="box">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Genre</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Filter by genre
                    $filterGenre = isset($_GET['product-genre']) ? $_GET['product-genre'] : '';
                    $filterClause = $filterGenre ? "WHERE genre = '$filterGenre'" : '';
                    
                    $select_products = mysqli_query($conn, "SELECT * FROM products $filterClause") or die('query failed');
                    if (mysqli_num_rows($select_products) > 0) {
                        while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    ?>
                            <tr>
                                <td><?php echo $fetch_products['name']; ?></td>
                                <td><?php echo $fetch_products['price']; ?></td>
                                <td><?php echo $fetch_products['quantity']; ?></td>
                                <td><?php echo $fetch_products['genre']; ?></td>
                                <td>
                                    <a href="products.php?delete=<?php echo $fetch_products['bk_id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<p class="empty">No products found</p>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Custom admin JS file link -->
<script src="Attic.js"></script>

</body>
</html>
