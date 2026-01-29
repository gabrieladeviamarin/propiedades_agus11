<?php
session_start();
require_once('../../database/database.php');
$documento = $_SESSION['documento'];
$nombre = $_SESSION['nombre'];
$conexion = new database;
$con = $conexion->conectar();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes AGUS11</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
 
</head>
<body>
    <div class="container_grid">
        <aside class="aside_barra">
        <?php include ('asideBar/asideBar.php') ?>
        </aside>
        <main class="grid_contenido_plantilla">

        //////////////////////////////////CONTENIDO AQUI //////////////////////////////////////////
              
        </main>   
    </div>

</body>
</html>

