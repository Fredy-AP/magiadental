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

if (isset($_GET['list'])) {
    $db = new SQLite3('../server/database.db');
    $sql = "SELECT * FROM products";
    $result = $db->query($sql);
    $products = array();
    while ($row = $result->fetchArray()) {
        array_push($products, array(
            'id' => $row['id'],
            'category' => $row['category'],
            'description' => $row['description'],
            'price' => $row['price'],
            'image' => $row['image']
        ));
    }
    $db->close();
    echo json_encode($products);
}
