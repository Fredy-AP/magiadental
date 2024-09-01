<?php
session_name('magiaDental');
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    header('Location: ./login.php');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../node_modules/sweetalert2/dist/sweetalert2.css">
    <link rel="stylesheet" href="../../DataTables/datatables.css">
    <link rel="stylesheet" href="../../DataTables/datatables.min.js">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../static/css/admin.css?v=<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="../../node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <title>Admin</title>
</head>

<body>
    <h1>
        Administrar productos
    </h1>
    <div class="container-fluid bg-dark p-2">
        <button class="btn btn-primary btn-add">
            <i class="bi bi-plus"></i>
            <span>
                Agregar producto
            </span>
        </button>
        <a href="./logout.php" class="btn btn-danger btn-logout">
            <i class="bi bi-box-arrow-right"></i>
            <span>
                Cerrar sesión
            </span>
        </a>
    </div>
    <div class="table-container">
        <table id="productos">
            <thead>
                <tr>
                    <th>
                        Categoria
                    </th>
                    <th>
                        Descripción
                    </th>
                    <th>
                        Precio
                    </th>
                    <th>
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <script src="../../DataTables/datatables.js"></script>
    <script src="../../static/js/admin.js?v=<?php echo time(); ?>"></script>
</body>

</html>