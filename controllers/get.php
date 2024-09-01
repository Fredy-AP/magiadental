<?php
if (isset($_GET['get'])) {
    $id = $_GET['id'];
    $db = new SQLite3('../server/database.db');
    $sql = "SELECT * FROM products WHERE id=$id";
    $result = $db->query($sql);
    $row = $result->fetchArray();
    $product = array(
        'id' => $row['id'],
        'category' => $row['category'],
        'description' => $row['description'],
        'price' => $row['price'],
        'image' => $row['image']
    );
    $db->close();
    echo json_encode($product);
}