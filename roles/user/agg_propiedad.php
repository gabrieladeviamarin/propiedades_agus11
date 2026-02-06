<?php
session_start();
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();


if (isset($_POST['submit'])){

    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $distrito = $_POST['distrito'];
    $tipo = $_POST['tipo'];
    $observacion = $_POST['observacion'];
    $estado = 1;
    
    $verify = $con->prepare("SELECT * FROM propiedades WHERE cod_lugar = :codigo");
    $verify->bindParam(':codigo', $codigo);
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
                                text: 'La propiedad ya existe'
                            }).then(function() {
                                window.location.href='propiedades.php';
                            });
                        </script>
                        </body>
                        </html>";
                        exit();
    } 

    $query = $con->prepare("INSERT INTO propiedades (cod_lugar, nom_lugar, direccion, id_distrito, id_tip_prop, id_estado, observacion) VALUES (:codigo, :nombre, :direccion, :distrito, :tipo, :estado, :observacion)");
    $query->bindParam(':codigo', $codigo);
    $query->bindParam(':nombre', $nombre);
    $query->bindParam(':direccion', $direccion);
    $query->bindParam(':distrito', $distrito);
    $query->bindParam(':tipo', $tipo);
    $query->bindParam(':estado', $estado);
    $query->bindParam(':observacion', $observacion);
    $query->execute();

    $id_propiedad = $con->lastInsertId();

    // PROCESAMIENTO DE SEGURO
    if (isset($_POST['tiene_seguro']) && $_POST['tiene_seguro'] == '1') {
        $codigo_seguro = $_POST['codigo_seguro'];
        $precio_seguro = $_POST['precio_seguro'];
        
        // Verificar si el código de seguro ya existe
        $verify_seguro = $con->prepare("SELECT * FROM seguros WHERE codigo_seguro = :codigo_seguro");
        $verify_seguro->bindParam(':codigo_seguro', $codigo_seguro);
        $verify_seguro->execute();
        $verify_seguro = $verify_seguro->fetchAll(PDO::FETCH_ASSOC);

        if (count($verify_seguro) > 0) {
            echo "<!DOCTYPE html>
                        <html>
                        <head>
                            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        </head>
                        <body>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                text: 'El código de seguro ya existe'
                            }).then(function() {
                                window.location.href='propiedades.php';
                            });
                        </script>
                        </body>
                        </html>";
                        exit();
        }

        // Insertar seguro con tiene_seguro = 1
        $query_seguro = $con->prepare("INSERT INTO seguros (id_lugar, tiene_seguro, codigo_seguro, precio) VALUES (:id_lugar, 1, :codigo_seguro, :precio)");
        $query_seguro->bindParam(':id_lugar', $id_propiedad);
        $query_seguro->bindParam(':codigo_seguro', $codigo_seguro);
        $query_seguro->bindParam(':precio', $precio_seguro);
        $query_seguro->execute();
    } 

    if (!empty($_POST['nro_matricula'])){

        $nro_matricula = $_POST['nro_matricula'];
        $ficha_catastral = $_POST['ficha_catastral'];
        $codigo_contable = $_POST['codigo_contable'];
        $v_escritura = $_POST['valor_escritura'];
        $v_contable_l = $_POST['valor_contable_l'];
        $v_contable_b = $_POST['valor_contable_b'];
        $v_avaluo_l = $_POST['valor_avaluo_l'];
        $v_avaluo_b = $_POST['valor_avaluo_b'];
        $fecha_registro = $_POST['fecha_registro'];

        $verify = $con->prepare("SELECT * FROM escritura WHERE nro_matricula = :nro_matricula");
        $verify->bindParam(':nro_matricula', $nro_matricula);
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
                                window.location.href='propiedades.php';
                            });
                        </script>
                        </body>
                        </html>";
                        exit();
        }

        $query = $con->prepare("INSERT INTO escritura (nro_matricula, ficha_catastral, valor, fecha_registro, id_lugar) VALUES (:nro_matricula, :ficha_catastral, :v_escritura, :fecha_registro, :id_propiedad)");
        $query->bindParam(':nro_matricula', $nro_matricula);
        $query->bindParam(':ficha_catastral', $ficha_catastral);
        $query->bindParam(':v_escritura', $v_escritura);
        $query->bindParam(':fecha_registro', $fecha_registro);
        $query->bindParam(':id_propiedad', $id_propiedad);
        $query->execute();

        if (isset($_FILES['escritura']) &&  $_FILES['escritura']['error'] !== UPLOAD_ERR_NO_FILE){

            $uploadDir = '../../uploads/escrituras/';
            $fileName = basename($_FILES['escritura']['name']);
            $fileTmpName = $_FILES['escritura']['tmp_name'];
            $fileSize = $_FILES['escritura']['size'];
            $fileError = $_FILES['escritura']['error'];

            if ($fileError === 0) {
                if ($fileSize < 4 * 1024 * 1024) {
                    $pre_fijo = "escritura_$fileName";
                    $newFileName = uniqid($pre_fijo, true) . ".pdf"; 
                    $fileDestination = $uploadDir . $newFileName;
                
                    if (move_uploaded_file($fileTmpName, $fileDestination)) {

                        $query = $con->prepare("UPDATE escritura SET documento_pdf = :newFileName WHERE nro_matricula = :nro_matricula");
                        $query->bindParam(':newFileName', $newFileName);
                        $query->bindParam(':nro_matricula', $nro_matricula);
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
                                window.location.href='propiedades.php';
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
                                window.location.href='propiedades.php';
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
                            window.location.href='propiedades.php';
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
                        window.location.href='propiedades.php';
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
                        window.location.href='propiedades.php';
                    });
                </script>
                </body>
                </html>";
                exit();

    } elseif (!empty($_POST['nro_contrato'])){
            
            $nro_contrato = $_POST['nro_contrato'];
            $v_contrato = $_POST['valor_contrato'];

            $verify = $con->prepare("SELECT * FROM contrato WHERE nro_contrato = :nro_contrato");
            $verify->bindParam(':nro_contrato', $nro_contrato);
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
                                    window.location.href='propiedades.php';
                                });
                            </script>
                            </body>
                            </html>";
                            exit();
            }

            $query = $con->prepare("INSERT INTO contrato (nro_contrato, id_lugar, valor_contrato) VALUES (:nro_contrato, :id_propiedad, :v_contrato)");
            $query->bindParam(':nro_contrato', $nro_contrato);
            $query->bindParam(':id_propiedad', $id_propiedad);
            $query->bindParam(':v_contrato', $v_contrato);
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
                            $query = $con->prepare("UPDATE contrato SET archivo_contrato = :newFileName WHERE nro_contrato = :nro_contrato");
                            $query->bindParam(':newFileName', $newFileName);
                            $query->bindParam(':nro_contrato', $nro_contrato);
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
                                    window.location.href='propiedades.php';
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
                                    window.location.href='propiedades.php';
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
                                window.location.href='propiedades.php';
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
                            window.location.href='propiedades.php';
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
                            window.location.href='propiedades.php';
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
            window.location.href='propiedades.php';
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