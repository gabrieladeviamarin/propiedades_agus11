<?php
require_once('../database/database.php');
$conex = new database;
$con = $conex->conectar();
session_start();

if (isset($_POST['submit'])){

    $documento = $_POST['id_documento'];
    $passwordDesc = htmlentities(addslashes($_POST['password']));
    $sql = $con->prepare("SELECT * FROM usuario where id_documento = '$documento'");
    $sql->execute();
    $fila = $sql->fetch(PDO::FETCH_ASSOC);

    if ($fila) {

        if (password_verify($passwordDesc, $fila['password'])) {

            $_SESSION['documento'] = $fila['id_documento'];
            $_SESSION['rol'] = $fila['id_rol'];
            $_SESSION['nombre'] = $fila['nombre'];
           
            if ($fila['id_rol'] == 1) {
                header("Location: ../roles/admin/index.php");
                exit();
            } else {
                header("Location: ../roles/user/index.php");
                exit();
            }

        } else {
            header("Location: ../index.php?contra");
            exit;
        }

    } else {
        header("Location: ../index.php?user");
            exit;
    }

} else {
    echo "<script> alert('No se pudo iniciar sesion')</script>";
}

?>