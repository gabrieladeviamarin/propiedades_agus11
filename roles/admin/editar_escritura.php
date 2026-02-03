<?php
session_start();
require_once('../../database/database.php');
$documento = $_SESSION['documento'];
$nombre = $_SESSION['nombre'];
$conexion = new database;
$con = $conexion->conectar();

if (isset($_GET['nro_matricula'])) {
    $nro_matricula = $_GET['nro_matricula'];
    $sql = $con->prepare("SELECT escritura.*, seguros.*, propiedades.* FROM escritura
    LEFT JOIN seguros ON seguros.id_lugar = escritura.id_lugar 
    LEFT JOIN propiedades ON propiedades.id_lugar = escritura.id_lugar WHERE nro_matricula = :nro_matricula");

    $sql->execute([':nro_matricula' => $nro_matricula]);
    $escritura = $sql->fetch(PDO::FETCH_ASSOC);
    
    $id_lugar = $escritura['id_lugar']; 
    if (!$escritura) {
        echo "<!DOCTYPE html>
            <html>
            <head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    text: 'No se encuentra el Nro Matricula de la propiedad',
                }).then(() => {
                    window.location.href = 'editar_propiedad.php?id_lugar=$id_lugar';
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
                    text: 'No se encuentra el Nro Matricula de la propiedad',
                }).then(() => {
                    window.location.href = 'editar_propiedad.php?id_lugar=$id_lugar';
                });
            </script>
            </body>
            </html>";
            
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nro_matricula = $_POST['nro_matricula'];
    $ficha_catastral = $_POST['ficha_catastral'];
    $fecha_registro = $_POST['fecha_registro'];
    $valor = $_POST['valor'];
    $codigo_contable = $_POST['codigo_contable'];
    $valor_contable = $_POST['valor_contable'];
    $valor_contable_lote = $_POST['valor_contable_lote'];
    $valor_contable_build = $_POST['valor_contable_build'];
    $valor_avaluo = $_POST['valor_avaluo'];
    $valor_avaluo_lote = $_POST['valor_avaluo_lote'];
    $valor_avaluo_build = $_POST['valor_avaluo_build'];
    $id_lugar = $escritura['id_lugar'];

    // Procesar archivo si se subió
    if (isset($_FILES['escritura']) && $_FILES['escritura']['error'] !== UPLOAD_ERR_NO_FILE) {

        $uploadDir = '../../uploads/escrituras/';
        
        // Crear directorio si no existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = basename($_FILES['escritura']['name']);
        $fileTmpName = $_FILES['escritura']['tmp_name'];
        $fileSize = $_FILES['escritura']['size'];
        $fileError = $_FILES['escritura']['error'];

        if ($fileError === 0) {
            if ($fileSize < 4 * 1024 * 1024) {
                $pre_fijo = "escritura_" . $nombre . "_";
                $newFileName = uniqid($pre_fijo, true) . ".pdf"; 
                $fileDestination = $uploadDir . $newFileName;
            
                if (move_uploaded_file($fileTmpName, $fileDestination)) {

                    $query = $con->prepare("UPDATE escritura SET documento_pdf = :documento WHERE nro_matricula = :nro_matricula");
                    $query->execute([
                        ':documento' => $newFileName,
                        ':nro_matricula' => $nro_matricula
                    ]);

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
                            text: 'Error al subir la escritura'
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
                        text: 'La escritura es muy pesada, máximo 4MB'
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
                    text: 'Error al procesar el archivo'
                }).then(function() {
                    window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
                });
            </script>
            </body>
            </html>";
            exit();
        }
    }

    // Actualizar datos de escritura
    $sql = $con->prepare("UPDATE escritura SET
        ficha_catastral = :ficha_catastral,
        fecha_registro = :fecha_registro,
        valor = :valor,
        codigo_contable = :codigo_contable,
        valor_contable = :valor_contable,
        valor_contable_lote = :valor_contable_lote,
        valor_contable_build = :valor_contable_build,
        valor_avaluo = :valor_avaluo,
        valor_avaluo_lote = :valor_avaluo_lote,
        valor_avaluo_build = :valor_avaluo_build
        WHERE nro_matricula = :nro_matricula");

    $sql->execute([
        ':ficha_catastral' => $ficha_catastral,
        ':fecha_registro' => $fecha_registro,
        ':valor' => $valor,
        ':codigo_contable' => $codigo_contable,
        ':valor_contable' => $valor_contable,
        ':valor_contable_lote' => $valor_contable_lote,
        ':valor_contable_build' => $valor_contable_build,
        ':valor_avaluo' => $valor_avaluo,
        ':valor_avaluo_lote' => $valor_avaluo_lote,
        ':valor_avaluo_build' => $valor_avaluo_build,
        ':nro_matricula' => $nro_matricula
    ]);

    // Procesar seguros
    if (isset($_POST['precio_seguro']) && $_POST['precio_seguro'] != null) {
        $seguro = $_POST['seguro'];
        $precio_seguro = $_POST['precio_seguro'];
        
        $sqlCheck = $con->prepare("SELECT * FROM seguros WHERE id_lugar = :id_lugar");
        $sqlCheck->execute([':id_lugar' => $id_lugar]);
        $seguroExiste = $sqlCheck->fetch(PDO::FETCH_ASSOC);

        if (!$seguroExiste) {
            $sqlInsert = $con->prepare("INSERT INTO seguros (id_lugar, tiene_seguro, precio) VALUES (:id_lugar, :seguro, :precio)");
            $sqlInsert->execute([
                ':id_lugar' => $id_lugar,
                ':seguro' => $seguro,
                ':precio' => $precio_seguro
            ]);
        } else {
            $sqlUpdate = $con->prepare("UPDATE seguros SET tiene_seguro = :seguro, precio = :precio WHERE id_lugar = :id_lugar");
            $sqlUpdate->execute([
                ':seguro' => $seguro,
                ':precio' => $precio_seguro,
                ':id_lugar' => $id_lugar
            ]);
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
                text: 'Registros Actualizados',
            }).then(() => {
                window.location.href = 'editar_propiedad.php?id_lugar=$id_lugar';
            });
        </script>
        </body>
        </html>";
        
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Escritura AGUS11</title>
    <link rel="stylesheet" href="css/editar_escritura.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body>
    <div class="container_grid">
        <aside class="aside_barra">
        <?php include ('asideBar/asideBar.php') ?>
        </aside>
        <main class="grid_contenido_plantilla">
        <div class="contenedor">
        <h2>Editar Escritura</h2>

        <form class="form_escritura" method="POST" enctype="multipart/form-data">

            <div class="grid_form">
                <div class="campo">
                    <label>Nro Matrícula</label>
                    <input type="number" name="nro_matricula" value="<?= htmlspecialchars($escritura['nro_matricula']) ?>" readonly>
                </div>
                <div class="campo">
                    <label>Ficha Catastral</label>
                    <input type="text" name="ficha_catastral" value="<?= htmlspecialchars($escritura['ficha_catastral']) ?>">
                </div>
                <div class="campo">
                    <label>Fecha Registro</label>
                    <input type="date" name="fecha_registro" value="<?= htmlspecialchars($escritura['fecha_registro']) ?>">
                </div>
                <div class="campo">
                    <label>Valor Escritura</label>
                    <input type="number" name="valor" value="<?= htmlspecialchars($escritura['valor']) ?>">
                </div>
                <div class="campo">
                    <label>Código Contable</label>
                    <input type="text" name="codigo_contable" value="<?= htmlspecialchars($escritura['codigo_contable']) ?>">
                </div>
                <div class="campo">
                    <label>Valor Contable</label>
                    <input type="number" name="valor_contable" value="<?= htmlspecialchars($escritura['valor_contable']) ?>">
                </div>
                <div class="campo">
                    <label>Valor Contable Lote</label>
                    <input type="number" name="valor_contable_lote" value="<?= htmlspecialchars($escritura['valor_contable_lote']) ?>">
                </div>
                <div class="campo">
                    <label>Valor Contable Building</label>
                    <input type="number" name="valor_contable_build" value="<?= htmlspecialchars($escritura['valor_contable_build']) ?>">
                </div>
                <div class="campo">
                    <label>Valor Avalúo Total</label>
                    <input type="number" name="valor_avaluo" value="<?= htmlspecialchars($escritura['valor_avaluo']) ?>">
                </div>
                <div class="campo">
                    <label>Valor Avalúo Lote</label>
                    <input type="number" name="valor_avaluo_lote" value="<?= htmlspecialchars($escritura['valor_avaluo_lote']) ?>">
                </div>
                <div class="campo">
                    <label>Valor Avalúo Building</label>
                    <input type="number" name="valor_avaluo_build" value="<?= htmlspecialchars($escritura['valor_avaluo_build']) ?>">
                </div>
                <div class="campo">
                    <label>¿Tiene seguro?</label>
                    <select name="seguro">
                        <option value="1" <?= ($escritura['tiene_seguro'] == 1) ? 'selected' : '' ?>>Sí</option>
                        <option value="0" <?= ($escritura['tiene_seguro'] == 0) ? 'selected' : '' ?>>No</option>
                    </select>
                </div>
                <div class="campo">
                    <label>Precio del Seguro</label>
                    <input type="number" name="precio_seguro" value="<?= htmlspecialchars($escritura['precio']) ?>">
                </div>
                <div class="campo">
                    <label>Escritura Escaneada En Pdf</label>
                    <input type="file" name="escritura" accept=".pdf">
                    <?php if (!empty($escritura['documento_pdf'])): ?>
                        <small style="display:block; margin-top:5px; color:#666;">
                            Archivo actual: <?= htmlspecialchars($escritura['documento_pdf']) ?>
                        </small>
                    <?php endif; ?>
                </div>
                
            </div>

            <div class="acciones_form">
                <a href="editar_propiedad.php?id_lugar=<?= $id_lugar ?>" class="btn_cancelar">Cancelar</a>
                <button type="submit" class="btn_guardar">Guardar Cambios</button>
            </div>

        </form>
    </div>
        </main>   
    </div>

</body>
</html>