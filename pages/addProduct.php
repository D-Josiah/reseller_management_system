<?php
include '../connector.php';

session_start(); 

function generateUniqueFilename($filename) {

    $path_parts = pathinfo($filename);
    $extension = isset($path_parts['extension']) ? '.' . $path_parts['extension'] : '';
    $basename = isset($path_parts['filename']) ? $path_parts['filename'] : '';
    $dirname = '';


    if (!is_dir($dirname)) {
    
        mkdir($dirname, 0777, true);
    }

    $count = 1;
    $new_filename = $basename . $extension;
    // If filename alr exits, make a new file
    while (file_exists($dirname . '/' . $new_filename)) {
        $new_filename = $basename . '_' . $count . $extension;
        $count++;
    }
    return $new_filename;
}

// If form was submitted
if(isset($_POST["submit"])){
    $name = $_POST["name"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];
    $imagePath = null;

    // If an image is uploaded, handle the upload process
    if(isset($_FILES["product_photo"]) && $_FILES["product_photo"]["error"] == 0){
        // new filename
        $filename = generateUniqueFilename($_FILES["product_photo"]["name"]);

        $destination = '../uploads/' . $filename;


        // If file was moved to destination
        if(move_uploaded_file($_FILES["product_photo"]["tmp_name"], $destination)){
            // Store filename 
            $imagePath = $filename; // Store only the filename
        } else {
            echo "Failed to move uploaded file.";
            exit();
        }
    }

    // Construct the INSERT SQL query
    if ($imagePath) { //With image
        $insert_product_sql = "INSERT INTO product (stock, image, price, name) VALUES ('$stock', '$imagePath', '$price', '$name')";
    } else {
        $insert_product_sql = "INSERT INTO product (stock, price, name) VALUES ('$stock', '$price', '$name')";
    }

    // Execute the INSERT SQL query for product insertion
    if ($conn->query($insert_product_sql) === TRUE) {
        $product_id = $conn->insert_id;

        // Construct the INSERT SQL query for records table
        $insert_record_sql = "INSERT INTO records (date, admin_id, product_id, record_type) VALUES (NOW(), '{$_SESSION['admin_id']}', '$product_id', 1)";

        // Execute the INSERT SQL query for record insertion
        if ($conn->query($insert_record_sql) === TRUE) {
            header("Location:productList.php");
            exit();
        } else {
            echo "Error inserting record: " . $conn->error;
        }
    } else {
        echo "Error inserting product: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/addProduct.css"/>
    <link rel="stylesheet" href="css/footer.css"/>
    <title>ADD PRODUCT</title>
</head>
<body>
    <header>
        <!-- Image that links to reg pahe -->
        <a href="registration.php"><img src="../images/product.png"></a>
        <h1>Add Product</h1>
    </header>
    <main>
        <!-- Bacl button -->
        <div class="backBtnWrapper">
            <a href="productList.php"><img src="../images/back.png" class="backBtn" /></a>
        </div>

        <!-- Add product form -->
        <form action="addProduct.php" method="post" enctype="multipart/form-data">
            <div class="left">
                <h3>Product Information</h3>
                <input name="name" type="text" placeholder="  Name" required />
                <div class="priceWrapper">
                    <input name="price" type="number" step="0.01" placeholder="  Price" required />
                    <input name="stock" type="number" placeholder="  Stock" required />
                </div>
                
                <h3>Product Image</h3>
                <input name="product_photo" type="file" id="photoInput" accept="image/*" class="productImg" />
            </div>
            <div class="right">
                <input name="submit" type="submit" class="submit" value="ADD">
            </div>
        </form>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>