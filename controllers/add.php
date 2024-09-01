<?php

$db = new SQLite3('../server/database.db');
$tableProducts = 'CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category TEXT NOT NULL,
    description TEXT NOT NULL,
    price REAL NOT NULL,
    image TEXT NOT NULL
)';
$db->exec($tableProducts);
$db->close();
if (isset($_POST['category']) || !isset($_POST['description']) || !isset($_POST['price']) || !isset($_POST['image'])) {
    $image = 'default.png';
    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $file = $_FILES['image'];
        $image = $file['name'];
        $tmp_name = $file['tmp_name'];
        $path = "../static/images/productos/" . $image;
        move_uploaded_file($tmp_name, $path);
    }
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $db = new SQLite3('../server/database.db');
    $sql = "SELECT * FROM products WHERE description='$description'";
    $result = $db->query($sql);
    $row = $result->fetchArray();
    if ($row) {
        echo json_encode(array('status' => 400, 'message' => 'El producto ya existe'));
    } else {
        $sql = "INSERT INTO products (category, description, price, image) VALUES ('$category', '$description', $price, '$image')";
        $db->exec($sql);
        $db->close();
        echo json_encode(array('status' => 200, 'message' => 'Producto agregado correctamente'));
    }
} else {
    echo json_encode(array('status' => 400, 'message' => 'Todos los campos son requeridos'));
}


// if (isset($_GET['list'])) {
//     $db = new SQLite3('../server/database.db');
//     $sql = "SELECT * FROM products";
//     $result = $db->query($sql);
//     $products = array();
//     while ($row = $result->fetchArray()) {
//         array_push($products, array(
//             'id' => $row['id'],
//             'category' => $row['category'],
//             'description' => $row['description'],
//             'price' => $row['price'],
//             'image' => $row['image']
//         ));
//     }
//     $db->close();
//     echo json_encode($products);
// }