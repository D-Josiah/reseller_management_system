<?php
include '../connector.php';


// SQL query to get the top 5 resellers with the highest total amount spent
$get_top_resellers_sql = "
    SELECT name, total_amount_spent
    FROM reseller
    ORDER BY total_amount_spent DESC
    LIMIT 5";

$result = $conn->query($get_top_resellers_sql);

$get_top_products_sql = "
    SELECT p.product_id, p.name, od.total_quantity
    FROM (
        SELECT product_id, SUM(quantity) AS total_quantity
        FROM order_details
        GROUP BY product_id
        ORDER BY total_quantity DESC
        LIMIT 5
    ) AS od
    JOIN product AS p ON od.product_id = p.product_id";

$product_result = $conn->query($get_top_products_sql);

$get_total_amount_spent_sql = "SELECT SUM(total_amount_spent) AS total_sales FROM reseller";

$sales_result = $conn->query($get_total_amount_spent_sql);



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/dashboard.css'/>
    <link rel='stylesheet' href='css/report.css'/>
   
    <title>RMS: REPORT</title>
</head>
<body>
    <header>
        <div class="header-content">
            <img src="../images/admin.png" alt="persons" class="persons">
            <h3 class="color">Admin Dashboard</h3>
            <form action="logout.php" method="post" class="logout">
                <input type="submit" value="LOGOUT">
            </form>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="resellerList.php">Reseller List</a></li>
            <li><a href="productList.php">Product List</a></li>
            <li><a href="orderList.php">Order List</a></li>
            <li><a href="report.php">Report</a></li>
        </ul>
    </nav>

    <main>
        <h1>
        </h1>
      
        <div class="top">
            <div class="reseller">
                <h3>TOP RESELLERS</h3>
                <?php
                if ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Reseller Name</th><th>Total Amount Spent</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_amount_spent']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No resellers found.</p>";
                }
                ?>
            </div>
            <div class="product">
                <h3>TOP PRODUCTS</h3>
                <?php
                if ($product_result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Product Name</th><th>Order Quantity</th></tr>";
                    while ($row = $product_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_quantity']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No products found.</p>";
                }
                ?>
            </div>
        </div>
        <div class="bot">
            <?php
            if ($sales_result->num_rows > 0) { 
                $row = $sales_result->fetch_assoc();//sales
                $total_spent = $row['total_sales']; 
             
                echo "<h2>TOTAL SALES: P  $total_spent</h2>";
            } else {
                echo "No data found.";
            }
            ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
