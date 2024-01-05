<?php
include 'config.php'; // Database connection
session_start(); // Start session
$admin_id = $_SESSION['admin_id']; // Retrieve stored session data

// Check if admin is logged in, if not, redirect back to login page
if (!isset($admin_id)) {
    header('location:login.php');
}

// Calculate total sales revenue
$totalRevenue = 0; // Keeps track of the total revenue
$salesByMonth = array(); // Stores sales revenue of each month

// Query to fetch data from the database
$select_orders = mysqli_query($conn, "SELECT * FROM orders") or die('Query failed');
while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
    // Extract year and month from the placed_on column
    $month = date('F Y', strtotime($fetch_orders['placed_on']));
    // Calculate the revenue
    $revenue = $fetch_orders['total_products'] * $fetch_orders['total_price'];

    // Add revenue to the corresponding month
    // Check if the month already exists as an array key, if not, generate a new key
    if (!isset($salesByMonth[$month])) {
        $salesByMonth[$month] = $revenue;
    } else {
        // If the month already exists, add the revenue value to the existing value of that month
        $salesByMonth[$month] += $revenue;
    }

    $totalRevenue += $revenue;
}

// Sort the sales by month in ascending order
ksort($salesByMonth);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sales Report</title>

    <!-- CSS file links -->
    <link rel="stylesheet" href="admin.css" />
    <style>
        /* Add custom styles here */
    </style>
</head>
<body>
<?php
include 'admin_header.php';
?>

<div class="sales-report">
    <h2>Sales Report</h2>
    <div class="print-button">
                <button onclick="window.print()">Print</button>
            </div>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Sales Revenue</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($salesByMonth as $month => $revenue) { ?>
                <tr>
                    <td><?php echo $month; ?></td>
                    <td>KES <?php echo $revenue; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td class="total">Total</td>
                <td class="total">KES <?php echo $totalRevenue; ?></td>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>
