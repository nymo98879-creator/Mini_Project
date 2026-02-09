<?php
require_once '../../config/database.php';

$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.category_id 
        ORDER BY p.product_id DESC";

$result = mysqli_query($conn, $sql);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);

mysqli_close($conn);
?>