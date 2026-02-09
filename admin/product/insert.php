<?php
require_once '../../config/database.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    // Get POST data
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $description = $_POST['description'];

    // Image upload
    $image_path = '';
    if ($_FILES['product_image']['name'] != '') {
        $image_name = time() . '_' . $_FILES['product_image']['name'];
        move_uploaded_file(
            $_FILES['product_image']['tmp_name'],
            'uploads/' . $image_name
        );
        $image_path = 'uploads/' . $image_name;
    }

    // Insert query
    $sql = "INSERT INTO products (category_id, name, description, price, stock_quantity, image_url) 
            VALUES ('$category_id', '$name', '$description', '$price', '$stock_quantity', '$image_path')";

    if (mysqli_query($conn, $sql)) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>