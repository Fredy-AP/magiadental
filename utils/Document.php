<?php
require_once '../../utils/menu.php';
$pageTitle = ucfirst(PAGE_NAME);
$css = 'css/' . str_replace(' ', '', strtolower(PAGE_NAME)) . '.css';
$js = 'js/' . str_replace(' ', '', strtolower(PAGE_NAME)) . '.js';
$p_static = '../../static/';
$p_modules = '../../node_modules/';
$p_views = '../../views/';
$viewName = ucfirst(str_replace(' ', '_', PAGE_NAME)) . 'View.php';

//crear carpeta static
if (!file_exists($p_static)) {
    mkdir($p_static);
}
if (!file_exists($p_static . 'sass')) {
    mkdir($p_static . 'sass');
}
if (!file_exists($p_static . 'js')) {
    mkdir($p_static . 'js');
}
//creamos el archivo js
$fileJS = $p_static . $js;
if (!file_exists($fileJS)) {
    $file = fopen($fileJS, 'w');
    fwrite($file, '');
    fclose($file);
}
//creamos el archivo scss
if (!file_exists($p_static . 'sass/' . str_replace(' ', '', strtolower(PAGE_NAME)) . '.scss')) {
    $file = fopen($p_static . 'sass/' . str_replace(' ', '', strtolower(PAGE_NAME)) . '.scss', 'w');
    fwrite($file, '');
    fclose($file);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $p_static . $css ?>?v=<?php echo time() ?>">
    <link rel="stylesheet" href="<?php echo $p_modules ?>bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo $p_modules ?>sweetalert2/dist/sweetalert2.min.css">
    <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
    <script src="<?php echo $p_modules ?>jquery/dist/jquery.min.js"></script>
    <script src="<?php echo $p_modules ?>sweetalert2/dist/sweetalert2.all.min.js"></script>
    <title>MD | <?php echo $pageTitle ?></title>
</head>

<body>
    <?php
    if (!file_exists($p_views . $viewName)) {
        //crear el archivo
        $file = fopen($p_views . $viewName, 'w');
        fwrite($file, '');
        fclose($file);
    }
    require_once $p_views . $viewName;

    ?>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col">

                    <p>
                        T.P.D Juan Pablo C. Bravo
                    </p>
                    <p>
                        MZ Q Casa 9 Barrio Tolima Ggrande (Ibagué-Tolima)
                    </p>
                    <p class="copy">
                        &copy; <?php echo date('Y') ?> Magia Dental. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
        <a href="http://wa.me/573208482825" class="btn btn-primary btn-whatsapp" target="_blank">
            <i class="bi bi-whatsapp"></i>
        </a>
    </footer>
    <script src="<?php echo $p_static ?>js/fx.js?v=<?php echo time() ?>"></script>
    <script src="<?php echo $p_static . $js ?>?v=<?php echo time() ?>"></script>
</body>

</html>