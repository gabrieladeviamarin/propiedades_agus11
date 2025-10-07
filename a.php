<?php
require_once("database/database.php");
$conexion = new database;
$con = $conexion->conectar();

if (isset($_POST['submit'])) {
    $nombre = $_POST['nombre'];
    $documento = $_POST['id_documento'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    // Verificar si ya existe el usuario
    $query = $con->prepare("SELECT * FROM usuario WHERE id_documento = :documento OR email = :correo");
    $query->bindParam(":documento", $documento);
    $query->bindParam(":correo", $correo);
    $query->execute();

    if ($query->rowCount() > 0) {
        $error = "El usuario o correo ya está registrado.";
    } else {
        // Insertar nuevo registro
        $insert = $con->prepare("INSERT INTO usuario (nombre, id_documento, email, password, id_rol, id_estado) 
                                 VALUES (:nombre, :documento, :correo, :password,1,1)");
        $insert->bindParam(":nombre", $nombre);
        $insert->bindParam(":documento", $documento);
        $insert->bindParam(":correo", $correo);
        $insert->bindParam(":password", $password);

        if ($insert->execute()) {
            header("Location: login.php?registro=ok");
            exit;
        } else {
            $error = "Error al registrar el usuario.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <!-- Lado Izquierdo -->
    <div class="left">
        <div class="leftcontent">
            <h2>PROPIEDADES</h2>
            <img src="assets/blanco.png" />
        </div>
    </div>
    <!-- Lado Derecho -->
    <div class="right">
        <div class="login_box">
            <h2>Registro de Usuario</h2>

            <?php if (!empty($error)): ?>
                <p style="color:red;"><?= $error ?></p>
            <?php endif; ?>

            <form method="POST">
                <div class="form_group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ingrese su nombre">
                </div>
                
                <div class="form_group">
                    <label for="documento">Documento</label>
                    <input type="text" id="documento" name="id_documento" required placeholder="Ingrese su documento">
                </div>
                <div class="form_group">
                    <label for="correo">Correo</label>
                    <input type="email" id="correo" name="correo" required placeholder="Ingrese su correo">
                </div>
                <div class="form_group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required placeholder="Cree una contraseña">
                </div>
                <button type="submit" name="submit" class="btn">Registrarse</button>
            </form>
        </div>
    </div>
</body>
</html>
