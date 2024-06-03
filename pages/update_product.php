<?php
include '../connector.php';
session_start();

if(isset($_POST["submit"])){
    $product_id = $_POST['product-id'];

    // Update other product information
    $name = $_POST['name'];
    $price = $_POST['product-price'];
    $stock = $_POST['stock'];

    // If a new image is uploaded without errors, update it
    if(isset($_FILES["product-photo"]) && $_FILES["product-photo"]["error"] == 0){
        $filename = generateUniqueFilename($_FILES["product-photo"]["name"]);
        $destination = '../uploads/' . $filename;
        
        // Move image to the new location
        if(move_uploaded_file($_FILES["product-photo"]["tmp_name"], $destination)){
            // Sql to update product
            $updateSql = "UPDATE product SET name = '$name', price = $price, stock = $stock, image = '$filename' WHERE product_id = $product_id";
        } else {
            echo "Failed to move uploaded file.";
            exit();
        }
    } else {
        // If no new image is uploaded, retain the existing image
        $updateSql = "UPDATE product SET name = '$name', price = $price, stock = $stock WHERE product_id = $product_id";
    }

    if ($conn->query($updateSql) === TRUE) {
        // Redirect back to productList.php
        header("Location: productList.php");
        exit();
    } else {
        echo "Failed to update product information.";
    }
}

function generateUniqueFilename($filename) {
    // get path
    $path_parts = pathinfo($filename);
    $extension = isset($path_parts['extension']) ? '.' . $path_parts['extension'] : '';
    $basename = isset($path_parts['filename']) ? $path_parts['filename'] : '';
    $dirname = isset($path_parts['dirname']) ? $path_parts['dirname'] : '';
    
    $count = 1;
    $new_filename = $basename . $extension;
    // If filename alr exits, make a new file
    while (file_exists($dirname . '/' . $new_filename)) {
        $new_filename = $basename . '_' . $count . $extension;
        $count++;
    }
    return $new_filename;
}
?>