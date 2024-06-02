<?php
include '../connector.php';

if(isset($_POST["submit"])){
    $product_id = $_POST['product-id'];

    // Update other product information
    $name = $_POST['name'];
    $price = $_POST['product-price'];
    $stock = $_POST['stock'];

    // If a new image is uploaded, update it
    if(isset($_FILES["product-photo"]) && $_FILES["product-photo"]["error"] == 0){
        $filename = generateUniqueFilename($_FILES["product-photo"]["name"]);
        $destination = $filename;
        
        if(move_uploaded_file($_FILES["product-photo"]["tmp_name"], $destination)){
            $updateSql = "UPDATE product SET name = ?, price = ?, stock = ?, image = ? WHERE product_id = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("sdssi", $name, $price, $stock, $filename, $product_id);
        } else {
            echo "Failed to move uploaded file.";
            exit();
        }
    } else {
        // If no new image is uploaded, retain the existing image
        $updateSql = "UPDATE product SET name = ?, price = ?, stock = ? WHERE product_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("sdsi", $name, $price, $stock, $product_id);
    }

    if ($stmt->execute()) {
        // Redirect back to productList.php
        header("Location: productList.php");
        exit();
    } else {
        echo "Failed to update product information.";
    }
}

function generateUniqueFilename($filename) {
    $path_parts = pathinfo($filename);
    $extension = isset($path_parts['extension']) ? '.' . $path_parts['extension'] : '';
    $basename = isset($path_parts['filename']) ? $path_parts['filename'] : '';
    $dirname = isset($path_parts['dirname']) ? $path_parts['dirname'] : '';
    
    $count = 1;
    $new_filename = $basename . $extension;
    while (file_exists($dirname . '/' . $new_filename)) {
        $new_filename = $basename . '_' . $count . $extension;
        $count++;
    }
    return $new_filename;
}
?>
