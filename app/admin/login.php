<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../node_modules/sweetalert2/dist/sweetalert2.css">
    <link rel="stylesheet" href="../../DataTables/datatables.css">
    <link rel="stylesheet" href="../../DataTables/datatables.min.js">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../static/css/login.css?v=<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="../../node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <title>Admin</title>
</head>

<body>
    <h1>
        Login
    </h1>
    <div class="container-fluid bg-dark p-2">
        <form class="form" id="login" action="./auth.php" method="POST">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" class="form-control">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password">
            </div>
            <?php if (isset($_GET['error'])) : ?>
            <div class="alert alert-danger">
                <p>
                    Usuario o contraseña incorrectos
                </p>
            </div>
            <?php endif; ?>
            <button class="btn btn-primary btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>
                    Iniciar sesión
                </span>
            </button>
        </form>
    </div>
</body>

</html>