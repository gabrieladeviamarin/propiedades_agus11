<?php
session_start();
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();


if (isset($_POST['submit'])){

    $id_lugar = $_POST['id_lugar'];

    if (!empty($_POST['nro_matricula'])){

        $nro_matricula = $_POST['nro_matricula'];
        $ficha_catastral = $_POST['ficha_catastral'];
        $codigo = $_POST['codigo_contable'];
        $v_escritura = $_POST['valor_escritura'];
        $v_contable = $_POST['valor_contable'];
        $v_avaluo = $_POST['valor_avaluo'];
        $v_contable_l = $_POST['valor_contable_l'];
        $v_contable_b = $_POST['valor_contable_b'];
        $v_avaluo_l = $_POST['valor_avaluo_l'];
        $v_avaluo_b = $_POST['valor_avaluo_b'];
        $fecha_registro = $_POST['fecha_registro'];
        $seguro = $_POST['seguro'];
        $precio_seguro = $_POST['precio_seguro'];

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
                                icon: 'success',
                                text: 'Propiedad Creada con Escritura Escaneada'
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

        $query_seguro = $con->prepare("INSERT INTO seguros (tiene_seguro, precio) VALUES (1, $precio_seguro) WHERE id_propiedad = $id_lugar");
        $query_seguro->execute();

        if ($query_seguro->rowCount() == 0) {
            $query_seguro = $con->prepare("INSERT INTO seguro (id_propiedad, valor) VALUES ($id_lugar, $precio_seguro)");
            $query_seguro->execute();
        }

        if (isset($_FILES['escritura']) &&  $_FILES['escritura']['error'] !== UPLOAD_ERR_NO_FILE){

            $uploadDir = '../../uploads/escrituras/';
            $fileName = basename($_FILES['escritura']['name']);
            $fileTmpName = $_FILES['escritura']['tmp_name'];
            $fileSize = $_FILES['escritura']['size'];
            $fileError = $_FILES['escritura']['error'];

            if ($fileError === 0) {
                if ($fileSize < 4 * 1024 * 1024) {
                    $pre_fijo = "escritura_$nombre_";
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
                                text: 'Propiedad Creada con Escritura Escaneada'
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
                        text: 'Documento de escritura invalido, dirigete a la propiedad y agregala'
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
                        text: 'Propiedad Creada con Escritura'
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
                                    text: 'Propiedad Creada con Contrato Escaneado'
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
                            text: 'Documento de escritura invalido, dirigete a la propiedad y agregalo'
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
                            text: 'Propiedad Creada con contrato'
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
                text: 'Propiedad creada'
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
            window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
        });
    </script>
    </body>
    </html>";
}
?>