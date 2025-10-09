<?php
require_once(__DIR__ . '/../../../database/database.php');
require_once(__DIR__ . '/../../../includes/sesion_validar_datos.php');
$conexion = new database;
$con = $conexion->conectar();
$rol =$_SESSION['rol'];
validarSesionRol($rol,1);       
// $codigo = $_SESSION['documento'];
// $nombre = explode(" ", $_SESSION['nombre'])[0];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body>

    <button class="menu_iconos_burguer"  id="abrir"><i class="bi bi-list"></i></button>

    <aside id="barraLateral" class="fuera">
        <div class="Logo">
            <img src="/propiedades_agus11/assets/logo.png" class="logoIgl" alt="Logo">
        </div>

        <hr class="white">

        <a href="./index.php"> <div class="seccion" id="seccion">
         <img class="icono" src="asideBar/icons/home.png" alt="home">
            <p class="seccionTexto">Inicio</p>
        </div></a>

        <a href="./distritos.php"> <div class="seccion">
            <img class="icono" src="asideBar/icons/distritos.png" alt="">
            <p class="seccionTexto">Distritos</p>
        </div></a>

        <a href="./propiedades.php"> <div class="seccion">
            <img class="icono" src="asideBar/icons/propiedades.png" alt="">
            <p class="seccionTexto">Propiedades</p>
        </div></a>

        <a href="./usuarios.php"> <div class="seccion">
            <img class="icono" src="asideBar/icons/usuarioBlanco.png" alt="">
            <p class="seccionTexto">Usuarios</p>
        </div></a>
        <a href="./documentos.php"> <div class="seccion">
            <img class="icono" src="asideBar/icons/documento.png" alt="">
            <p class="seccionTexto">Documentos</p>
        </div></a>

        <a href="./informes.php"> <div class="seccion">
            <img class="icono" src="asideBar/icons/informe.png" alt="">
            <p class="seccionTexto">Informes</p>
        </div></a>

        <hr class="white2">

        <a href="./notificaciones.php"> <div class="seccion">
            <img class="icono" src="asideBar/icons/notificaciones.png" alt="">
            <p class="seccionTexto">Notificaciones</p>
        </div></a>

        <a href="./configuracion.php"> <div class="seccion">
            <img class="icono" src="asideBar/icons/configuracion.png" alt="">
            <p class="seccionTexto">Configuracion</p>
        </div></a>

        <a href="/propiedades_agus11/includes/exit.php"> <div class="seccion">
            <img class="icono" src="asideBar/icons/Salir.png" alt="">
            <p class="seccionTexto">Salir</p>
        </div></a>

     





    </aside>


    <script>




</script>
    <script src="./asideBar/script.js"></script>
</body>
</html>