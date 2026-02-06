        <?php
        session_start();
        require_once('../../database/database.php');
        $documento = $_SESSION['documento'];
        $nombre = $_SESSION['nombre'];
        $conexion = new database;
        $con = $conexion->conectar();

        if (isset($_GET['id_lugar'])) {
            $id_lugar = $_GET['id_lugar'];
            $sql = $con -> prepare("SELECT p.* , t.*, d.nom_distrito FROM propiedades p
            INNER JOIN distrito d ON p.id_distrito = d.id_distrito
            INNER JOIN tipo_propiedad t ON p.id_tip_prop = t.id_tip_prop WHERE id_lugar = $id_lugar");
            $sql -> execute();
            $resultado = $sql -> fetch(PDO::FETCH_ASSOC);
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
                    text: 'No se proporciono el codigo de la propiedad',
                }).then(() => {
                    window.location.href = 'propiedades.php';
                });
            </script>
            </body>
            </html>";
            
            exit();
        }

        $escrituras = $con -> prepare("SELECT * FROM escritura WHERE id_lugar = $id_lugar");
        $escrituras -> execute();
        $escrituras = $escrituras -> fetchAll(PDO::FETCH_ASSOC);

        $contrato = $con -> prepare("SELECT * FROM contrato WHERE id_lugar = $id_lugar");
        $contrato -> execute();
        $contrato = $contrato -> fetchAll(PDO::FETCH_ASSOC);

        $distritos = $con -> prepare("SELECT * FROM distrito");
        $distritos -> execute();
        $distritos = $distritos -> fetchAll(PDO::FETCH_ASSOC);

        $tipos = $con -> prepare("SELECT * FROM tipo_propiedad");
        $tipos -> execute();
        $tipos = $tipos -> fetchAll(PDO::FETCH_ASSOC);

        $seguro = $con -> prepare("SELECT * FROM seguros WHERE id_lugar = $id_lugar");
        $seguro -> execute();
        $seguro = $seguro -> fetch(PDO::FETCH_ASSOC);
        
        // Si no hay seguro, crear un array por defecto
        if (!$seguro) {
            $seguro = ['tiene_seguro' => 0];
        }

        if (isset($_POST['btn_prop'])) {
            $cod_lugar = $_POST['cod_lugar'];
            $distrito = $_POST['distrito'];
            $direccion = $_POST['direccion'];
            $tipo = $_POST['tipo'];
            $observacion = $_POST['observacion'];

            $sql = $con -> prepare("UPDATE propiedades SET cod_lugar = '$cod_lugar', id_distrito = '$distrito', direccion = '$direccion', id_tip_prop = '$tipo', observacion = '$observacion' WHERE id_lugar = '$id_lugar'");
            $sql -> execute();
            echo "<!DOCTYPE html>
                <html>
                <head>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                <script>
                    Swal.fire({
                        icon: 'success',
                        text: 'Propiedad Actualizada'
                    }).then(function() {
                        window.location.href='editar_propiedad.php?id_lugar=$id_lugar';
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
            <title>Propiedades AGUS11</title>
            <link rel="stylesheet" href="css/editar_propiedad.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        </head>
        <body>
            <div class="container_grid">
                <aside class="aside_barra">
                <?php include ('asideBar/asideBar.php') ?>
                </aside>
                <main class="grid_contenido_plantilla">
                    <div class="header_propiedad">
                        <div class="info_propiedad">
                            <img src="assets/propiedad_negra.png" alt="icono" class="icono_prop">
                            <div class="info">
                                <h3 class="texto_prop"><?= $resultado['nom_lugar']?></h3>
                                <div class="items">
                                    <div>
                                        <form action="" method="POST" id="propiedad">
                                        <p> <strong>Código:</strong> <input type="text" name="cod_lugar" value="<?= $resultado['cod_lugar']?>" ></p>
                                        <p><strong>Distrito:</strong> <select name="distrito" id="distrito" >
                                                            <?php foreach ($distritos as $distrito) {?>
                                                                <option 
        value="<?= $distrito['id_distrito'] ?>" 
        <?= ($distrito['id_distrito'] == $resultado['id_distrito']) ? 'selected' : '' ?>>
        <?= $distrito['nom_distrito'] ?>
      </option>
      <?php } ?>
                                                            </select></p>
                                        <p><strong>Dirección:</strong> <input type="text" name="direccion" id="direccion" value="<?= $resultado['direccion']?>" ></p>
                                    </div>
                                    <div>
                                    <p><strong>Tipo:</strong><select name="tipo" id="tipo" >
                                                           <?php foreach ($tipos as $tipo) {?>
                                                            <option
        value="<?= $tipo['id_tip_prop'] ?>" 
        <?= ($tipo['id_tip_prop'] == $resultado['id_tip_prop']) ? 'selected' : '' ?>>
        <?= $tipo['nom_tipo'] ?>
                                                            <?php }?>
                                                            </select></p> 
                                    <p><strong>Observaciones:</strong> <textarea rows="3" name="observacion" id="observacion" ><?= $resultado['observacion']?> </textarea></p>   
                                    </div>      
                                </div>             
                            </div>
                        </div>
                        <button type="submit" name="btn_prop" class="btns">Guardar cambios</button>
                        </form>
                    </div>
                    <div class="agg_documento">
                        <button class="btns" id="agg_documento" data-bs-toggle="modal" data-bs-target="#agregar">Agregar Documento</button>
                    </div>
                    <?php if ($escrituras != null) {?>
                        
                            <?php foreach ($escrituras as $escritura) {?>
                            <div class="grid_documentos">
                                <div class="card_doc">
                                <a href="editar_escritura.php?nro_matricula=<?= $escritura['nro_matricula'] ?>" class="btn_editar">
                                    <i class="bi bi-pencil" style="font-size: 1.6rem;" title="Editar"></i>
                                </a>
                                    <div class="card_header">
                                        <img src="assets/doc.png" alt="" width="50px" height="50px">
                                        <div>
                                        <span><strong><?= $escritura['nro_matricula']?></strong></span>
                                        <p>Nro Matricula</p>
                                        </div>

                                    </div>
                                    <div class="card_body">
                                        <p><strong>Ficha Catastral: </strong> <?= $escritura['ficha_catastral']?></p>
                                        <p><strong>Fecha Registro:</strong> <?= $escritura['fecha_registro']?></p>
                                        <p><strong>Valor escritura:</strong> <?= $escritura['valor']?></p>
                                        <p><strong>Valor avalúo:</strong> <?= $escritura['valor_avaluo']?></p>
                                        <p><strong><?= ($seguro['tiene_seguro'] == 1) ? 'Asegurada' : 'Sin Asegurar' ?></strong></p>
                                    </div>
                                    <?php if($escritura['documento_pdf']!=null){?>
                                        <div class="card_footer">
                                            <a href="../../uploads/escrituras/<?=$escritura['documento_pdf'] ?>" download class="btn_icon" title="Descargar PDF">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                            <?php }?>
                    <?php } elseif ($contrato != null){
                        foreach ($contrato as $contrato) {?>
                        <div class="grid_documentos">
                            <div class="card_doc">
                                <div class="card_header">
                                    <img src="assets/doc.png" alt="" width="50px" height="50px">
                                    <div>
                                    <span><strong><?=$contrato['nro_contrato']?></strong></span>
                                    <p>Nro Contrato</p>
                                    </div>
                                </div>
                                <div class="card_body">
                                    <p>Valor contrato: <?=$contrato['valor_contrato']?></p>
                                </div>
                                <div class="card_footer">
                                    <?php if($contrato['archivo_contrato']!=null){?>
                                        <div class="card_footer">
                                            <a href="../../uploads/contratos/<?=$contrato['archivo_contrato'] ?>" download class="btn_icon" title="Descargar PDF">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                    <?php }else{?>
                        <div class="grid_documentos">
                            <div class="card_doc">
                                <div class="card_header">
                                    <img src="assets/doc.png" alt="" width="50px" height="50px">
                                    <div>
                                    <span><strong>No hay documentos</strong></span>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    <?php }?>
        <!-- 
        ////////////////////////////////////// MODAL AGG DOCUMENTO ///////////////////////////////////////////////////////// -->
        <div class="modal fade" id="agregar" tabindex="-1" aria-labelledby="Titulo" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h1 class="modal-title fs-5" id="Titulo">Crear Propiedad</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                                <div class="modal-body">
                                <form action="agg_documento.php" id="agg" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="id_lugar">
                                            <input type="hidden" name="id_lugar" id="id_lugar" value="<?= $id_lugar?>">
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <label for="seguro">¿La propiedad tiene seguro?</label>
                                        <div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="seguro" id="si" value="1">
                                                <label class="form-check-label" for="1">Si</label>
                                            </div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="seguro" id="no" value="2">
                                                <label class="form-check-label" for="2">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="precio_seguro">Valor por el que esta asegurado</label>
                                        <input type="number" class="form-control" id="precio_seguro" name="precio_seguro">
                                    </div>
        <!----------------      ------------------------------ SI ESCRITURA ------------------------------------------------>
                                    <div class="mb-3 escritura">
                                        <label for="nro_matricula" class="form-label">Numero de Matricula</label>
                                        <input type="number" class="form-control" id="nro_matricula" name="nro_matricula" >
                                    </div>
                                    <div class="mb-3 escritura">
                                        <label for="ficha_catastral" class="form-label">Ficha Catastral</label>
                                        <input type="text" class="form-control" id="ficha_catastral" name="ficha_catastral" >
                                    </div>
                                    <div class="mb-3 escritura">
                                        <label for="valor_escritura" class="form-label">Valor Escritura</label>
                                        <input type="number" min="0" class="form-control" id="valor_escritura" name="valor_escritura" >
                                    </div>
                                    <div class="mb-3 escritura">
                                        <label for="codigo_contable" class="form-label">Codigo Contable</label>
                                        <input type="text" class="form-control" id="codigo_contable" name="codigo_contable" >
                                    </div>
                                    <div class="mb-3 escritura">
                                        <label for="valor_contable" class="form-label">Valor Contable</label>
                                        <input type="number" class="form-control" id="valor_contable" name="valor_contable" >
                                    </div>
                                    <div class="mb-3 escritura">
                                        <label for="valor_contable_l" class="form-label">Valor Contable Lote</label>
                                        <input type="number" class="form-control" id="valor_contable_l" name="valor_contable_l" >
                                    </div>
                                    <div class="mb-3 escritura">
                                        <label for="valor_contable_b" class="form-label">Valor Contable Building</label>
                                        <input type="number" class="form-control" id="valor_contable_b" name="valor_contable_b" >
                                    </div>
                                    <div class="mb-3 escritura">
                                        <label for="valor_avaluo" class="form-label">Valor Avaluo</label>
                                        <input type="number" class="form-control" id="valor_avaluo" name="valor_avaluo" >
                                    </div>
                                    <div class="mb-3 escritura">
                                        <label for="valor_avaluo_l" class="form-label">Valor Avaluo Lote</label>
                                        <input type="number" class="form-control" id="valor_avaluo_l" name="valor_avaluo_l" >
                                    </div>
                                    <div class="mb-3 escritura">
                                        <label for="valor_avaluo_b" class="form-label">Valor Avaluo Building</label>
                                        <input type="number" class="form-control" id="valor_avaluo_b" name="valor_avaluo_b" >
                                    </div>
                                    <div class="mb-3 escritura">
                                        <label for="fecha_registro" class="form-label">Fecha de Registro</label>
                                        <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" >
                                    </div>
                                    <div class="mb-3 escritura">
                                        <label for="escr" class="form-label">¿Posee la escritura?</label>
                                        <div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="opcion" id="si" value="1">
                                                <label class="form-check-label" for="1">Si</label>
                                            </div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="opcion" id="no" value="2">
                                                <label class="form-check-label" for="2">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 escritura_file">
                                        <label for="escritura" class="form-label">Subir Documento Escaneado</label>
                                        <input type="file" class="form-control" id="escritura" name="escritura" accept=".pdf">
                                    </div>
        <!----------------      ------- ----------------------- SI CONTRATO ------------------------------------------------>
                                    <div class="mb-3 contrato">
                                        <label for="nro_contrato" class="form-label">Numero de Contrato</label>
                                        <input type="number" class="form-control" id="nro_contrato" name="nro_contrato">
                                    </div> 
                                    <div class="mb-3 contrato">
                                        <label for="valor_contrato" class="form-label">Valor</label>
                                        <input type="numer" min="0" class="form-control" id="valor_contrato" name="valor_contrato">
                                    </div> 
                                    <div class="mb-3 contrato">
                                        <label for="cont" class="form-label">¿Posee el contrato?</label>
                                        <div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="opcion2" id="si2" value="1">
                                                <label class="form-check-label" for="1">Si</label>
                                            </div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="opcion2" id="no2" value="2">
                                                <label class="form-check-label" for="2">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 contrato_file">
                                        <label for="contrato" class="form-label">Subir Documento Escaneado</label>
                                        <input type="file" class="form-control" id="contrato" name="contrato" accept=".pdf">
                                    </div>  
                                </div>
                                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn_cancelar" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" name="submit" class="btns">Agregar Documento</button>
                        </div>
                        </form>

                        </div>
                    </div>
                    </div>
                </main>   
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

            <script>
const tipoPropiedad = Number(<?= json_encode($resultado['id_tip_prop']) ?>);  
const btn = document.getElementById("agg_documento");
const modal = document.getElementById("agregar");

modal.addEventListener("show.bs.modal", function (event) {
    const form = document.getElementById("agg");
    const escritura = document.querySelectorAll(".escritura");
    const contrato = document.querySelectorAll(".contrato");
    const escritura_file = document.querySelectorAll(".escritura_file");
    const contrato_file = document.querySelectorAll(".contrato_file");
    const radios_escritura = document.querySelectorAll('input[name="opcion"]');
    const radios_contrato = document.querySelectorAll('input[name="opcion2"]');
    const radios_seguro = document.querySelectorAll('input[name="seguro"]');
    const precio_seguro = document.getElementById("precio_seguro").parentElement;

    if (tipoPropiedad === 1) {
        escritura.forEach(function(escritura) {
            escritura.style.setProperty('display', 'block', 'important');
        });
        contrato.forEach(function(contrato) {
            contrato.style.setProperty('display', 'none', 'important');
        });
    } else if(tipoPropiedad === 2 || tipoPropiedad === 4 || tipoPropiedad === 5){
        contrato.forEach(function(contrato) {
            contrato.style.setProperty('display', 'block', 'important');
        });
        escritura.forEach(function(escritura) {
            escritura.style.setProperty('display', 'none', 'important');
        });
    } else {
        event.preventDefault();
        Swal.fire({
            icon: "error",
            text: "No se puede agregar un documento debido a que no es escritura o contrato",
        }).then(() => {
            window.location.href = "editar_propiedad.php?id_lugar=<?= $id_lugar ?>";
        });
        return;
    }

    // Resetear radios y ocultar campos de archivo
    radios_escritura.forEach(r => r.checked = false);
    radios_contrato.forEach(r => r.checked = false);
    radios_seguro.forEach(r => r.checked = false);
    escritura_file.forEach(e => e.style.display = "none");
    contrato_file.forEach(c => c.style.display = "none");
    precio_seguro.style.display = "none";
});

document.addEventListener("DOMContentLoaded", function () {
    // Radio buttons de seguro - usando name y value
    const si_seguro = document.querySelector('input[name="seguro"][value="1"]');
    const no_seguro = document.querySelector('input[name="seguro"][value="2"]');
    
    // Radio buttons de escritura - usando name y value
    const si_escritura = document.querySelector('input[name="opcion"][value="1"]');
    const no_escritura = document.querySelector('input[name="opcion"][value="2"]');
    
    // Radio buttons de contrato - usando name y value
    const si_contrato = document.querySelector('input[name="opcion2"][value="1"]');
    const no_contrato = document.querySelector('input[name="opcion2"][value="2"]');
    
    const escrituraFile = document.querySelectorAll(".escritura_file");
    const contratoFile = document.querySelectorAll(".contrato_file");
    const precio_seguro = document.getElementById("precio_seguro").parentElement;
    
    // Ocultar el campo de precio del seguro al cargar
    precio_seguro.style.display = "none";
    
    // Función para mostrar/ocultar campo de precio del seguro
    function togglePrecioSeguro() {
        if (si_seguro && si_seguro.checked) {
            precio_seguro.style.display = "block";
        } else {
            precio_seguro.style.display = "none";
            document.getElementById("precio_seguro").value = ""; // Limpiar el valor
        }
    }
    
    // Función para mostrar/ocultar campos de archivo de escritura y contrato
    function subir_file() {
        // Ocultar todos los campos de archivo
        escrituraFile.forEach(e => e.style.display = "none");
        contratoFile.forEach(c => c.style.display = "none");

        // Mostrar campo de escritura si se selecciona "Si"
        if (si_escritura && si_escritura.checked) {
            escrituraFile.forEach(e => e.style.display = "block");
        }

        // Mostrar campo de contrato si se selecciona "Si"
        if (si_contrato && si_contrato.checked) {
            contratoFile.forEach(c => c.style.display = "block");
        }
    }

    // Agregar listeners a los radio buttons de seguro
    if (si_seguro) si_seguro.addEventListener("change", togglePrecioSeguro);
    if (no_seguro) no_seguro.addEventListener("change", togglePrecioSeguro);
    
    // Agregar listeners a los radio buttons de escritura
    if (si_escritura) si_escritura.addEventListener("change", subir_file);
    if (no_escritura) no_escritura.addEventListener("change", subir_file);
    
    // Agregar listeners a los radio buttons de contrato
    if (si_contrato) si_contrato.addEventListener("change", subir_file);
    if (no_contrato) no_contrato.addEventListener("change", subir_file);

    const formPropiedad = document.getElementById("propiedad");
    const formDocumento = document.getElementById("agg");
    
    function validarFormulario(event) {
        const formId = event.target.id;
    
        if (formId === "propiedad") {
            const codigo = document.querySelector('input[name="cod_lugar"]');
            const direccion = document.getElementById("direccion");
            const distrito = document.getElementById("distrito");
            const tipo = document.getElementById("tipo");
        
            if (!codigo.value.trim() || !direccion.value.trim() || !distrito.value.trim() || !tipo.value.trim()) {
                event.preventDefault();
                Swal.fire({ 
                    icon: "error", 
                    text: "Todos los campos deben ser diligenciados en la propiedad" 
                });
                return false;
            }
        }
    
        if (formId === "agg") {
            const nro_matricula = document.getElementById("nro_matricula");
            const ficha_catastral = document.getElementById("ficha_catastral");
            const valor_escritura = document.getElementById("valor_escritura");
            const fecha_registro = document.getElementById("fecha_registro");
            const escritura_file = document.getElementById("escritura");
            
            const nro_contrato = document.getElementById("nro_contrato");
            const valor_contrato = document.getElementById("valor_contrato");
            const contrato_file = document.getElementById("contrato");
            
            const precio_seguro_input = document.getElementById("precio_seguro");
        
            // --- Validar seguro ---
            if (!si_seguro.checked && !no_seguro.checked) {
                event.preventDefault();
                Swal.fire({ icon: "error", text: "Debe seleccionar si la propiedad tiene seguro o no" });
                return false;
            }
            
            if (si_seguro.checked && !precio_seguro_input.value.trim()) {
                event.preventDefault();
                Swal.fire({ icon: "error", text: "Debe ingresar el valor del seguro" });
                return false;
            }
            
            // --- Validar escritura ---
            if (nro_matricula.value.trim() !== "") {
                if (!ficha_catastral.value.trim() || !valor_escritura.value.trim() || !fecha_registro.value.trim()) {
                    event.preventDefault();
                    Swal.fire({ icon: "error", text: "Debe completar todos los campos de escritura" });
                    return false;
                }
                if (!si_escritura.checked && !no_escritura.checked) {
                    event.preventDefault();
                    Swal.fire({ icon: "error", text: "Debe seleccionar si tiene o no la escritura" });
                    return false;
                }
                if (si_escritura.checked && !escritura_file.files.length) {
                    event.preventDefault();
                    Swal.fire({ icon: "error", text: "Debe subir un documento escaneado de la escritura" });
                    return false;
                }
            }
        
            // --- Validar contrato ---
            if (nro_contrato.value.trim() !== "") {
                if (!valor_contrato.value.trim()) {
                    event.preventDefault();
                    Swal.fire({ icon: "error", text: "Debe completar los campos del contrato" });
                    return false;
                }
                if (!si_contrato.checked && !no_contrato.checked) {
                    event.preventDefault();
                    Swal.fire({ icon: "error", text: "Debe seleccionar si tiene o no el contrato" });
                    return false;
                }
                if (si_contrato.checked && !contrato_file.files.length) {
                    event.preventDefault();
                    Swal.fire({ icon: "error", text: "Debe subir un documento escaneado del contrato" });
                    return false;
                }
            }
        
            // Si ambos están vacíos
            if (nro_matricula.value.trim() === "" && nro_contrato.value.trim() === "") {
                event.preventDefault();
                Swal.fire({ 
                    icon: "error", 
                    text: "Debe completar todos los campos del formulario" 
                });
                return false;
            }
            
            // Si todo está bien, permitir el envío
            return true;
        }
    }

    // Escuchar ambos formularios
    if (formPropiedad) {
        formPropiedad.addEventListener("submit", validarFormulario);
    }
    if (formDocumento) {
        formDocumento.addEventListener("submit", validarFormulario);
    }
});
</script>
        </body>
        </html>
