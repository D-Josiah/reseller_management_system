<!DOCTYPE html>
<head>
    <title>Reseller Info</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <header>
        <div class="header-content">
            <img src="../images/persons.png" alt="persons" class="persons">
            <h1>Admin Dashboard</h1>
        </div>
    </header>

    <h1>RESELLER INFO</h1>
    <?php
    include '../connector.php';

    // Generate pre-filled form
    $reseller_id = $_POST['reseller_id'];

    $get_reseller_info_sql = "SELECT * 
                            FROM reseller AS r
                            JOIN address AS a ON r.address = a.address_id
                            JOIN region AS reg ON a.region_id = reg.region_id
                            WHERE r.reseller_id = $reseller_id";

    $reseller_info_result = $conn->query($get_reseller_info_sql);

    if ($reseller_info_result->num_rows > 0) {
        $row = $reseller_info_result->fetch_assoc(); 
    
        $reseller_name = $row['name'];
        $reseller_number = $row['reseller_number'];
        $region_id = $row['region_id'];
        $province = $row['province'];
        $postal_code = $row['postal_code'];
        $active_status = $row['active_status'];
        $total_amount_spent = $row['total_amount_spent'];

    } else {
        echo "No reseller found with the given ID";
    }
    
    
    if (isset($_POST['save_changes'])) {//SAVE CHANGES

        // Retrieve form data
        $reseller_name = $_POST['reseller_name'];
        $reseller_number = $_POST['phone_number'];
        $region_id = $_POST['region'];
        $postal_code = $_POST['postal_code'];
        $province = $_POST['province'];
        $active_status = $_POST['active_status'];

        // Update query to save the changes
        $update_reseller_sql = "UPDATE reseller
                                JOIN address ON reseller.address = address.address_id
                                SET 
                                    reseller.name = '$reseller_name',
                                    reseller.reseller_number = '$reseller_number',
                                    address.region_id = '$region_id',
                                    address.postal_code = '$postal_code',
                                    address.province = '$province',
                                    reseller.active_status = '$active_status'
                                WHERE reseller.reseller_id = $reseller_id";


        $result = $conn->query($update_reseller_sql);

        if ($result) {
            echo "Reseller details updated successfully";
        } else {
            echo "Error updating reseller details: " . $conn->error;
        }
    }    
    ?>

    <form action="resellerInfo.php" method="post">
        <input type='hidden' name='reseller_id' value="<?php echo $reseller_id; ?>">
     
        <div class="reseller-icon">
            <img src="../images/reseller_icon.png" alt="reseller icon">
        </div>
        <br>

        <div class="form-columns">
            <div>
                <label for="reseller_name">Reseller Name:</label>
                <input type="text" id="reseller_name" name="reseller_name" value="<?php echo $reseller_name; ?>">
            </div>
            <div>
                <label for="region">Region:</label>
                <select id="region" name="region">
                    <option value="">Select Region</option>
                    <?php
                    // Fetch regions from the address table
                    $region_sql = "SELECT * FROM region";
                    $region_result = $conn->query($region_sql);
                    if ($region_result->num_rows > 0) {
                        while ($region_row = $region_result->fetch_assoc()) {
                            $selected = ($region_row['region_id'] == $region_id) ? 'selected' : '';
                            echo "<option value='".$region_row['region_id']."' $selected>".$region_row['region_name']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="postal_code">Postal Code:</label>
                <input type="text" id="postal_code" name="postal_code" value="<?php echo $postal_code; ?>">
            </div>
        </div>
        <div class="form-columns">
            <div>
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo $reseller_number; ?>">
            </div>

            <div>
                <label for="province">Province:</label>
                <input type="text" id="province" name="province" value="<?php echo $province; ?>">
            </div>
        </div>
        <div class="form-columns">
            <div>
                <label for="active_status">Active Status:</label>
                <select id="active_status" name="active_status">
                    <option value="">All</option>
                    <option value="1" <?php if ($active_status == 1) echo 'selected'; ?>>Active</option>
                    <option value="2" <?php if ($active_status == 2) echo 'selected'; ?>>Inactive</option>
                    <option value="3" <?php if ($active_status == 3) echo 'selected'; ?>>Suspended</option>
                </select>
            </div>
            <div>
                <label for="total_amount_spent">Total Amount Spent:</label>
                <input type="text" id="total_amount_spent" name="total_amount_spent" value="<?php echo $total_amount_spent; ?>" readonly>
            </div>
        </div>
        <input type="submit" value="SAVE CHANGES" name="save_changes">
    </form>

    <?php include 'footer.php'; ?>

</body>
</html>
