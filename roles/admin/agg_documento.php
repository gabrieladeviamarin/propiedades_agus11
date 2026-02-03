<?php
session_start();
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();

if (isset($_POST['submit'])){

    $id_lugar = $_POST['id_lugar'];
    $seguro = $_POST['seguro'];
    $precio_seguro = isset($_POST['precio_seguro']) && $_POST['precio_seguro'] != '' ? $_POST['precio_seguro'] : 0;

    // Primero manejar el seguro
    $check_seguro = $con->prepare("SELECT * FROM seguros WHERE id_lugar = $id_lugar");
    $check_seguro->execute();
    
    if ($check_seguro->rowCount() > 0) {
        // Actualizar seguro existente
        $query_seguro = $con->prepare("UPDATE seguros SET tiene_seguro = $seguro, precio = $precio_seguro WHERE id_lugar = $id_lugar");
        $query_seguro->execute();
    } else {
        // Insertar nuevo seguro
        $query_seguro = $con->prepare("INSERT INTO seguros (id_lugar, tiene_seguro, precio) VALUES ($id_lugar, $seguro, $precio_seguro)");
        $query_seguro->execute();
    }

    if (!empty($_POST['nro_matricula'])){

        $nro_matricula = $_POST['nro_matricula'];
        $ficha_catastral = $_POST['ficha_catastral'];
        $codigo = $_POST['codigo_contable'];
        $v_escritura = $_POST['valor_escritura'];
        
        // Usar isset() para evitar errores si los campos están vacíos
        $v_contable = isset($_POST['valor_contable']) && $_POST['valor_contable'] != '' ? $_POST['valor_contable'] : 0;
        $v_avaluo = isset($_POST['valor_avaluo']) && $_POST['valor_avaluo'] != '' ? $_POST['valor_avaluo'] : 0;
        $v_contable_l = isset($_POST['valor_contable_l']) && $_POST['valor_contable_l'] != '' ? $_POST['valor_contable_l'] : 0;
        $v_contable_b = isset($_POST['valor_contable_b']) && $_POST['valor_contable_b'] != '' ? $_POST['valor_contable_b'] : 0;
        $v_avaluo_l = isset($_POST['valor_avaluo_l']) && $_POST['valor_avaluo_l'] != '' ? $_POST['valor_avaluo_l'] : 0;
        $v_avaluo_b = isset($_POST['valor_avaluo_b']) && $_POST['valor_avaluo_b'] != '' ? $_POST['valor_avaluo_b'] : 0;
        
        $fecha_registro = $_POST['fecha_registro'];

        $verify = $con->prepare("SELECT * FROM escritura WHERE nro_matricula = '$nro_matricula'");
        $verify->execute();
        $verify = $verify->fetchAll(PDO::FETCH_ASSOC);

        if (count($verify) > 0) {
            echo "<!DOCTYPE html>
                        <html>
                        <head>
                            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        </head>
                        <body>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                text: 'Numero de matricula ya existe'
                            }).then(function() {
                                window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                            });
                        </script>
                        </body>
                        </html>";
                        exit();
        }

        $query = $con->prepare("INSERT INTO escritura (nro_matricula, ficha_catastral, codigo_contable, valor, valor_contable, valor_contable_lote, valor_contable_build, valor_avaluo, valor_avaluo_lote, valor_avaluo_build, fecha_registro, id_lugar)
        VALUES ($nro_matricula, '$ficha_catastral', '$codigo', $v_escritura, $v_contable, $v_contable_l, $v_contable_b, $v_avaluo, $v_avaluo_l, $v_avaluo_b, '$fecha_registro', $id_lugar)");
        $query->execute();

        if (isset($_FILES['escritura']) &&  $_FILES['escritura']['error'] !== UPLOAD_ERR_NO_FILE){

            $uploadDir = '../../uploads/escrituras/';
            $fileName = basename($_FILES['escritura']['name']);
            $fileTmpName = $_FILES['escritura']['tmp_name'];
            $fileSize = $_FILES['escritura']['size'];
            $fileError = $_FILES['escritura']['error'];

            if ($fileError === 0) {
                if ($fileSize < 4 * 1024 * 1024) {
                    $pre_fijo = "escritura_";
                    $newFileName = uniqid($pre_fijo, true) . ".pdf";
                    $fileDestination = $uploadDir . $newFileName;

                    if (move_uploaded_file($fileTmpName, $fileDestination)) {

                        $query = $con->prepare("UPDATE escritura SET documento_pdf = '$newFileName' WHERE nro_matricula = $nro_matricula");
                        $query->execute();

                        echo "<!DOCTYPE html>
                        <html>
                        <head>
                            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        </head>
                        <body>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                text: 'Documento agregado con escritura escaneada'
                            }).then(function() {
                                window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                            });
                        </script>
                        </body>
                        </html>";
                        exit();

                    } else {

                        echo "<!DOCTYPE html>
                        <html>
                        <head>
                            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        </head>
                        <body>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                text: 'Error al subir la escritura, dirigite a la propiedad y agregala'
                            }).then(function() {
                                window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                            });
                        </script>
                        </body>
                        </html>";
                        exit();
                    }
                } else {

                    echo "<!DOCTYPE html>
                    <html>
                    <head>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    </head>
                    <body>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            text: 'La escritura es muy pesada, máximo 4MB, dirigete a la propiedad y agregala'
                        }).then(function() {
                            window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                        });
                    </script>
                    </body>
                    </html>";
                    exit();
                }
            } else {

                echo "<!DOCTYPE html>
                <html>
                <head>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        text: 'Documento de escritura invalido, dirigite a la propiedad y agregala'
                    }).then(function() {
                        window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                    });
                </script>
                </body>
                </html>";
                exit();
            }
        }
        echo "<!DOCTYPE html>
                <html>
                <head>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                <script>
                    Swal.fire({
                        icon: 'success',
                        text: 'Documento agregado con escritura'
                    }).then(function() {
                        window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                    });
                </script>
                </body>
                </html>";
                exit();

    } elseif (!empty($_POST['nro_contrato'])){

            $nro_contrato = $_POST['nro_contrato'];
            $v_contrato = $_POST['valor_contrato'];

            $verify = $con->prepare("SELECT * FROM contrato WHERE nro_contrato = '$nro_contrato'");
            $verify->execute();
            $verify = $verify->fetchAll(PDO::FETCH_ASSOC);

            if (count($verify) > 0) {
                echo "<!DOCTYPE html>
                            <html>
                            <head>
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                            </head>
                            <body>
                            <script>
                                Swal.fire({
                                    icon:'error',
                                    text: 'Numero de contrato ya existe'
                                }).then(function() {
                                    window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                                });
                            </script>
                            </body>
                            </html>";
                            exit();
            }

            $query = $con->prepare("INSERT INTO contrato (nro_contrato, id_lugar, valor_contrato) VALUES ($nro_contrato, $id_lugar, $v_contrato)");
            $query->execute();

            if (isset($_FILES['contrato']) &&  $_FILES['contrato']['error'] !== UPLOAD_ERR_NO_FILE){

                $uploadDir = '../../uploads/contratos/';
                $fileName = basename($_FILES['contrato']['name']);
                $fileTmpName = $_FILES['contrato']['tmp_name'];
                $fileSize = $_FILES['contrato']['size'];
                $fileError = $_FILES['contrato']['error'];

                if ($fileError === 0) {
                    if ($fileSize < 4 * 1024 * 1024) {
                        $newFileName = uniqid('contrato_', true). ".pdf" ;
                        $fileDestination = $uploadDir. $newFileName;

                        if (move_uploaded_file($fileTmpName, $fileDestination)) {
                            $query = $con->prepare("UPDATE contrato SET archivo_contrato = '$newFileName' WHERE nro_contrato = $nro_contrato");
                            $query->execute();

                            echo "<!DOCTYPE html>
                            <html>
                            <head>
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                            </head>
                            <body>
                            <script>
                                Swal.fire({
                                    icon:'success',
                                    text: 'Documento agregado con contrato escaneado'
                                }).then(function() {
                                    window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                                });
                            </script>
                            </body>
                            </html>";
                            exit();
                        } else {
                            echo "<!DOCTYPE html>
                            <html>
                            <head>
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                            </head>
                            <body>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    text: 'Error al subir el contrato, dirigete a la propiedad y agregalo'
                                }).then(function() {
                                    window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                                });
                            </script>
                            </body>
                            </html>";
                            exit();
                        }
                    } else {
                        echo "<!DOCTYPE html>
                        <html>
                        <head>
                           <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        </head>
                        <body>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                text: 'El contrato es muy pesado, máximo 4MB, dirigete a la propiedad y agregalo'
                            }).then(function() {
                                window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                            });
                        </script>
                        </body>
                        </html>";
                        exit();
                    }
                } else {
                    echo "<!DOCTYPE html>
                    <html>
                    <head>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    </head>
                    <body>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            text: 'Documento de contrato invalido, dirigete a la propiedad y agregalo'
                        }).then(function() {
                            window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                        });
                    </script>
                    </body>
                    </html>";
                    exit();
                }
            }
            echo "<!DOCTYPE html>
                <html>
                    <head>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    </head>
                    <body>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            text: 'Documento agregado con contrato'
                        }).then(function() {
                            window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                        });
                    </script>
                    </body>
                    </html>";
                    exit();
    } else {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
            Swal.fire({
                icon:'success',
                text: 'Seguro actualizado correctamente'
            }).then(function() {
            window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
        });
        </script>
        </body>
        </html>";
        exit();
    }


} else {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: 'error',
            text: 'No se envio el formulario correctamente'
        }).then(function() {
            window.location.href='propiedades.php';
        });
    </script>
    </body>
    </html>";
}
?>