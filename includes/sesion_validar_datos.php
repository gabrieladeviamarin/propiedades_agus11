
<?php
function validarSesionRol($tipo, $rol) {
    if($tipo != $rol){
        echo "<script>
                alert('Rol no permitido');
                window.location.href = '/propiedades_agus11/index.php';
            </script>";
        exit();
    }
}
if(!isset($_SESSION['documento'])){

    unset($_SESSION['documento']);
    unset($_SESSION['tipo']);

    $_SESSION = array();
    session_destroy();
    session_write_close();
    echo "<script>
            alert('No has iniciado sesión');
            window.location.href = '/propiedades_agus11/index.php';
        </script>";
    exit();
}
?>