<?php
if (isset($_GET['update'])) {
    $id = $_GET['id'];
    $category = $_GET['category'];
    $description = $_GET['description'];
    $price = $_GET['price'];
    $db = new SQLite3('../server/database.db');
    $sql = "UPDATE products SET category='$category', description='$description', price=$price WHERE id=$id";
    $db->exec($sql);
    $db->close();
    echo json_encode(array('status' => 200, 'message' => 'Producto actualizado correctamente'));
}