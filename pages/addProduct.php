<?php
include '../connector.php';
session_start();

if(isset($_POST["submit"])){
    $name = htmlspecialchars($_POST["name"]); 
    $price = htmlspecialchars($_POST["price"]);
    $stock = htmlspecialchars($_POST["stock"]);

    // Check if file was uploaded without errors
    if(isset($_FILES["product_photo"])) {
        echo "hello";
        $photo = $_FILES["product_photo"]["name"];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["product_photo"]["name"]);

        // Move uploaded file to designated folder
        if (move_uploaded_file($_FILES["product_photo"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars(basename($_FILES["product_photo"]["name"])). " has been uploaded.";
            // You can store the file name in a variable or use it for further processing
            $uploadedFileName = $photo;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No file uploaded or an error occurred while uploading.";
        // You may handle this case differently based on your requirements
        exit();
    }

    // Insert product into database
    $insert_product_sql = "INSERT INTO product (stock, image, price, name)
                          VALUES ('$stock', '$photo', '$price','$name')";

    if ($conn->query($insert_product_sql) === TRUE) {
        $product_id = $conn->insert_id;

        // Insert record into records table
        $insert_record_sql = "INSERT INTO records (date, admin_id, product_id, record_type)
                                VALUES (NOW(), '{$_SESSION['admin_id']}', '$product_id', 1)";

        if ($conn->query($insert_record_sql) === TRUE) {
            header("Location: productList.php");
            exit();
        } else {
            echo "Error inserting record: " . $conn->error;
        }
    } else {
        echo "Error inserting product: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/addProduct.css"/>
    <title>ADD PRODUCT</title>
</head>
<body>
    <header>
     <img src="../images/product.png">
        <h1>ADD PRODUCT</h1>
    </header> 
    <main>
        <div class="backBtnWrapper">
            <a href="productList.php"><img src="../images/back.png" class="backBtn" /></a>
        </div>
        <form  action="addProduct.php" method="post" enctype="multipart/form-data"> >
            <div class="left">
                <h3>Product Information</h3>
                <input name="name" type="text" placeholder="  Name" />
                <div class="priceWrapper">
                    <input name="price" type="text" placeholder="  Price" />
                    <input name="stock" type="text" placeholder="  Stock" />
                </div>
                
                <h3>Product Image</h3>
                <input
                name="product_photo"
                type="file"
                id="photoInput"
                accept="image/*"
                class="productImg"
                value=""
                />
            </div>
            <div class="right">
            
                <input name="submit" type="submit" class="submit" value="ADD">
            </div>
        </form>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>