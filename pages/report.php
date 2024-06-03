<?php
include '../connector.php';

// SQL query to get the top 5 resellers with the highest total amount spent
$get_top_resellers_sql = "
    SELECT name, total_amount_spent
    FROM reseller
    ORDER BY total_amount_spent DESC
    LIMIT 5";

$result = $conn->query($get_top_resellers_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/report.css'/>
    <link rel='stylesheet' href='css/dashboard.css'/>
    <title>RMS: REPORT</title>
</head>
<body>
    <header>
        <div class="header-content">
            <img src="../images/admin.png" alt="persons" class="persons">
            <h3 class="color">Admin Dashboard</h3>
        </div>
        <form action="logout.php" method="post" class="logout">
                <input type="submit" value="LOGOUT">
        </form>
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
            <?php
            $current_month = date("F");
            echo "$current_month";
            ?>
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
                <!-- Add code here to display top products -->
            </div>
        </div>
        <div class="bot">
            <h3>TOTAL RESELLERS:</h3>
            <h3>TOTAL ORDERS:</h3>
            <!-- Add code here to display total resellers and total orders -->
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
