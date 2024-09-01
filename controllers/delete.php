<?php
if (isset($_GET['delete'])) {
    $id = $_GET['id'];
    $db = new SQLite3('../server/database.db');
    $qll1 = "SELECT * FROM products WHERE id=$id";
    $result = $db->query($qll1);
    $row = $result->fetchArray();
    $image = $row['image'];
    if ($image != 'default.png') {
        $path = "../static/images/productos/" . $image;
        unlink($path);
    }

    $sql = "DELETE FROM products WHERE id=$id";
    $db->exec($sql);
    $db->close();
    echo json_encode(array('status' => 200, 'message' => 'Producto eliminado correctamente'));
}
