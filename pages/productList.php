<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/dashboard.css">
 
    <title>Product List</title>
</head>

<body>
    <header>
        <div class="header-content">
            <img src="../images/admin.png" alt="persons" class="persons">
            <h1>Admin Dashboard</h1>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="resellerList.php">Reseller List</a></li>
            <li><a href="productList.php">Product List</a></li>
            <li><a href="orderList.php">Order List</a></li>
        </ul>
    </nav>


    <?php
    // Check if showHidden parameter is set
    $showHidden = isset($_GET['showHidden']) ? $_GET['showHidden'] : '';
    $toggleButtonText = $showHidden ? 'Hide Hidden Products' : 'Show Hidden Products';
    $toggleButtonLink = $showHidden ? 'productList.php' : 'productList.php?showHidden=true';
    ?>
        
    <div class="search-filter-container">
        <select id="statusFilter">
            <option value="">All</option>
            <option value="available">Available</option>
            <option value="limited stock">Limited Stock</option>
            <option value="out of stock">Out of Stock</option>
        </select>
        <!-- Remove the Apply button -->

        <!--<button onclick="applyFilters()" class="apply-button">Apply</button>-->
        <a href="addProduct.php"><button class="add-button">Add</button></a>
        <!-- Add a button to toggle showing hidden products -->
        <a href="<?php echo $toggleButtonLink; ?>"><button class="toggle-hidden-button"><?php echo $toggleButtonText; ?></button></a>

    </div>

    <main>
        <h2>Product List</h2>
        <div class="product-list">
        <?php
        include '../connector.php';

        // Capture the filter value if set
        $filter = isset($_GET['status']) ? $_GET['status'] : '';

        // Check if showHidden parameter is set
        $showHidden = isset($_GET['showHidden']) ? $_GET['showHidden'] : '';

        // Construct the SQL query based on the showHidden parameter
        $sql = "SELECT p.product_id, p.stock, p.price, p.name, p.image, p.deleted, a.type AS availability
                FROM product p
                JOIN availability a ON p.availability = a.availability_id";


        if ($showHidden) {
            // If showHidden is set, include hidden products
            $sql .= " WHERE p.deleted = 1";
        } else {
            // Otherwise, exclude hidden products
            $sql .= " WHERE p.deleted = 0";
        }

        if ($filter) {
            $sql .= " AND a.type = '$filter'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productId = htmlspecialchars($row['product_id']);
                $productName = htmlspecialchars($row['name']);
                $productStatus = htmlspecialchars($row['availability']);
                $imageUrl = htmlspecialchars($row['image']);
                $imageBlob = $row['image'];

                // Check if the product is hidden
                $isHidden = $row['deleted'] == 1;

                // Output the product card
                echo "<div class='product-card" . ($isHidden ? " hidden-product" : "") . "'>
                        <img src='../uploads/{$imageUrl}' alt='Product Image' class='product-image'>
                        <div class='product-info'>
                            <h3>Product ID: {$productId}</h3>
                            <p>Name: {$productName}</p>
                            <p class='details price'>Price: P " . htmlspecialchars($row['price']) . "</p>
                            <p class='details quantity'>Quantity: " . htmlspecialchars($row['stock']) . "</p>
                            <p class='details availability'>Availability: {$productStatus}</p>";

                if ($isHidden) {
                    // If the product is hidden, display the "Unhide" button
                    echo "<a href='unhideproduct.php?product_id={$productId}' class='hide-product-link'>Unhide Product</a>";           
                } else {
                    // If the product is not hidden, display the "View Details" link
                    echo "<a href='productInfo.php?product_id={$productId}' class='view-details-link'>View Details</a>";
                    echo "<a href='hideproduct.php?product_id={$productId}' class='hide-product-link'>Hide Product</a>";
                }


            
                

                echo "</div>
                    </div>";
            }
        } else {
            echo "<p>No products found</p>";
        }

        $conn->close();
        ?>

        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        document.getElementById('statusFilter').addEventListener('change', function() {
            applyFilters();
        });

        function applyFilters() {
            var statusFilter, table, tr, td, i;

            // Get the status filter value, converted to uppercase
            statusFilter = document.getElementById("statusFilter").value.toUpperCase();

            // Get the product list table and its rows
            table = document.getElementsByClassName("product-list")[0];
            tr = table.getElementsByClassName("product-card");

            // Loop through all table rows (products)
            for (i = 0; i < tr.length; i++) {
                // Get the product info cell in the current row
                td = tr[i].getElementsByClassName("product-info")[0];
                if (td) {
                    // Get the availability element within the product info cell
                    var availability = td.getElementsByClassName("details availability")[0];

                    // Check if the product matches the status filter
                    if (availability.textContent.toUpperCase().indexOf(statusFilter) > -1 || statusFilter === "") {
                        // If matches, display the row
                        tr[i].style.display = "";
                    } else {
                        // If not matches, hide the row
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>

</html>