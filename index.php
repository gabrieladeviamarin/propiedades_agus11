<?php
require_once("database/database.php");
$conexion = new database;
$con = $conexion->conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <!----------- Lado Izquierdo ------------------------------->
    <div class="left">
        <div class="leftcontent">
            <h2>PROPIEDADES</h2>
            <img src="assets/logo_blanco.png" />
        </div>
    </div>
    <!----------- Lado Derecho ----------------------------------->
    <div class="right">
        <div class="login_box">
            <h2>Inicio de Sesión</h2>
            <form action="includes/session_start.php" method="POST" autocomplete="off">
                <div class="form_group">
                    <label for="documento">Cedula</label>
                    <input type="text" id="documento" name="id_documento" placeholder="Ingrese su usuario" autocomplete="off">
                    <small id="user" style="color: red; font-size: 14px; display: none;">Usuario no encontrado</small>
                </div>
                <div class="form_group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" autocomplete="new-password">
                    <small id="password2" style="color: red; font-size: 14px; display: none;">Contraseña incorrecta</small>
                </div>
                <button type="submit" name="submit" class="btn">Iniciar Sesión</button>
            </form>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    ////////// obtengo la url //////////////////////
    const params = new URLSearchParams(window.location.search);
    if (params.has("contra")) {
        let inputPass = document.getElementById("password");
        let msg = document.getElementById("password2");
        msg.style.display = "block";
        inputPass.style.border = "2px solid red";
    } else if (params.has("user")) {
        let inputUser = document.getElementById("documento");
        let msg = document.getElementById("user");
        msg.style.display = "block";
        inputUser.style.border = "2px solid red";
    }
});
</script>
  
</body>
</html>