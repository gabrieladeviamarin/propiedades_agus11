<?php
session_start();
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();

$propiedades = $con->prepare("SELECT p.cod_lugar, p.nom_lugar, p.direccion, t.nom_tipo, d.nom_distrito   FROM propiedades p
                                INNER JOIN tipo_propiedad t ON p.id_tip_prop = t.id_tip_prop 
                                INNER JOIN distrito d ON p.id_distrito = d.id_distrito WHERE p.id_estado = 1 ORDER BY cod_lugar ASC ;");
$propiedades->execute();
$propiedades = $propiedades->fetchAll(PDO::FETCH_ASSOC);

$conteo = $con->prepare("SELECT COUNT(*) FROM propiedades");
$conteo->execute();
$total = $conteo->fetchColumn();

$distritos = $con->prepare("SELECT d.id_distrito, d.nom_distrito FROM distrito d;");
$distritos->execute();
$distritos = $distritos->fetchAll(PDO::FETCH_ASSOC);

$tipo = $con->prepare("SELECT t.id_tip_prop, t.nom_tipo FROM tipo_propiedad t;");
$tipo->execute();
$tipo = $tipo->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['delete'])){
    $cod_lugar = $_POST['cod_lugar'];
    $delete = $con->prepare("UPDATE propiedades SET id_estado = 2 WHERE cod_lugar = '$cod_lugar'");
    $delete->execute();

    echo json_encode(['success' => true]);
    exit();
}

?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propiedades AGUS11</title>
    <link rel="stylesheet" href="css/propiedades.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container_grid">
        <aside class="aside_barra">
        <?php include ('asideBar/asideBar.php') ?>
        </aside>
        <main class="grid_contenido_plantilla">
        <div class="contenedor">
                <h2>Listado de Propiedades</h2>
                
                <p style="font-weight:bold;">En total <?= $total?> Propiedades</p>
                <div class="filtros">
                    <input type="text" id="buscador" placeholder="Buscar Por Nombre">
                    <select id="filtro_tipo">
                        <option value="">---  Filtrar por tipo  ---</option>
                        <?php foreach($tipo as $tipos):?>
                            <option value="<?= $tipos['nom_tipo']?>"><?= $tipos['nom_tipo']?></option>
                        <?php endforeach;?>
                    </select>
                    <select id="filtro_distrito">
                        <option value="">---  Filtrar por distrito  ---</option>
                        <?php foreach($distritos as $distrito):?>
                            <option value="<?= $distrito['nom_distrito']?>"><?= $distrito['nom_distrito']?></option>
                        <?php endforeach;?>
                    </select>
                    <button type="button" class="btn btn-primary btns" data-bs-toggle="modal" data-bs-target="#agregar">Crear Propiedad</button>
                </div>

                <table id="tabla" class="tabla">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Distrito</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($propiedades as $propiedad): ?>
                        <tr>
                            <td><?= $propiedad["cod_lugar"] ?></td>
                            <td><?= $propiedad["nom_lugar"] ?></td>
                            <td><?= $propiedad["direccion"] ?></td>
                            <td><?= $propiedad["nom_distrito"]?></td>
                            <td><?= $propiedad["nom_tipo"] ?></td>
                            <td>
                                <a href="editar_propiedad.php?codigo=<?= $propiedad['cod_lugar'] ?>" class="btn_editar">
                                    <i class="bi bi-eye" style="font-size: 1.5rem;" title="Ver"></i>
                                </a>
                                |
                                <a href="#" name="cod_lugar" id="cod_lugar" class="btn_editar" onclick="confirmar_delete('<?= $propiedad['cod_lugar'] ?>','<?= $propiedad['nom_lugar'] ?>')">
                                      <i class="bi bi-slash-circle" style="font-size: 1.4rem;" title="Inhabilitar"></i> 
                                </a>
                    
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <button id="btn_top" class="btn_top">↑</button>
            </div>
<!--------------------------------------- MODAL DE AGG ---------------------------------------------->
            <div class="modal fade" id="agregar" tabindex="-1" aria-labelledby="Titulo" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="Titulo">Crear Propiedad</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                        <div class="modal-body">
                          <form action="agg_propiedad.php" id="agg" method="post" enctype="multipart/form-data">
                              <div class="mb-3">
                                  <label for="codigo" class="form-label">Codigo</label>
                                  <input type="text" class="form-control" id="codigo" name="codigo">
                              </div>
                              <div class="mb-3">
                                  <label for="nombre" class="form-label">Nombre</label>
                                  <input type="text" class="form-control" id="nombre" name="nombre" >
                              </div>
                              <div class="mb-3">
                                  <label for="direccion" class="form-label">Direccion</label>
                                  <input type="text" class="form-control" id="direccion" name="direccion" >
                              </div>
                              <div class="mb-3">
                                      <label for="distrito" class="form-label">Distrito</label>
                                      <select class="form-select" id="distrito" name="distrito" > 
                                          <option value="">---  Seleccione un distrito  ---</option>
                                          <?php foreach($distritos as $distrito):?>
                                              <option value="<?php echo $distrito['id_distrito']?>"><?php echo $distrito['nom_distrito']?></option>
                                          <?php endforeach;?>
                                      </select>
                              </div>
                              <div class="mb-3">
                                  <label for="tipo" class="form-label">Tipo</label>
                                  <select class="form-select" id="tipo" name="tipo" > 
                                      <option value="">---  Seleccione un tipo  ---</option>
                                      <?php foreach($tipo as $tipos):?>
                                          <option value="<?php echo $tipos['id_tip_prop']?>"><?php echo $tipos['nom_tipo']?></option>
                                      <?php endforeach;?>
                                  </select>
                              </div>  
                              <div class="mb-3">
                                  <label for="observacion" class="form-label">Observaciones de la propiedad</label>
                                  <textarea class="form-control" id="observacion" name="observacion" rows="3" placeholder="*** No es obligatorio este campo ***"></textarea>
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
                                  <label for="valor_avaluo_l" class="form-label">Valor Avaluo</label>
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
                    <button type="submit" name="submit" class="btn btns">Crear Propiedad</button>
                  </div>
                  </form>

                </div>
              </div>
            </div>
        </main>   
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
//////////////////// LOGICA SCROLL ////////////////////////////////
    const main = document.querySelector(".grid_contenido_plantilla");
    const btn_top = document.getElementById("btn_top");
    
    main.addEventListener("scroll", () => {
        if (main.scrollTop > 300) {
            btn_top.style.display = "block";
        } else {
            btn_top.style.display = "none";
        }
    });
    btn_top.addEventListener("click", () => {
        main.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
////////////////////////////////// ALERTA ELIMINAR //////////////////////////////////////////
    function confirmar_delete(codigo, nombre) {
        Swal.fire({
            html: `¿Desea inhabilitar la propiedad <strong>${nombre}</strong>?<br><small class="text-muted">Esta acción no se puede deshacer</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Inhabilitar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {

                fetch('propiedades.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `delete=1&cod_lugar=${codigo}`
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire({
                            icon: 'success',
                            text: 'La propiedad ha sido inhabilitada'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: data.message || 'No se pudo inhabilitar la propiedad'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        text: 'Ocurrio un error al procesar la solicitud'
                    });
                    console.error('Error:', error);
                });
            }
        });
    }
///////////////// FILTROS Y DISPLAY DE CAMPOS AGG //////////////////
    document.addEventListener("DOMContentLoaded", () => {

        const buscador = document.getElementById("buscador");
        const filtro_tipo = document.getElementById("filtro_tipo");
        const filtro_distrito = document.getElementById("filtro_distrito");
        const filas = document.querySelectorAll("#tabla tbody tr");

        function filtrarTabla() {
            const texto = buscador.value.toLowerCase();
            const tipo = filtro_tipo.value;
            const distrito = filtro_distrito.value;

            filas.forEach(fila => {
                const contenido = fila.textContent.toLowerCase();
                const tipo_fila = fila.cells[4].textContent;
                const distrito_fila = fila.cells[3].textContent;

                const coincide_busc = contenido.includes(texto);
                const coincide_tipo = tipo === "" || tipo_fila === tipo;
                const coincide_distrito = distrito === "" || distrito_fila === distrito;

                fila.style.display = (coincide_busc && coincide_tipo && coincide_distrito) ? "" : "none";
            });
        }

        buscador.addEventListener("keyup", filtrarTabla);
        filtro_tipo.addEventListener("change", filtrarTabla);
        filtro_distrito.addEventListener("change", filtrarTabla);
    

        

////////////////////////// CAMPOS AGG //////////////////////////////////////

        const select = document.getElementById("tipo");
        const escritura = document.querySelectorAll(".escritura");
        const escritura_file = document.querySelector(".escritura_file");
        const contrato = document.querySelectorAll(".contrato");
        const contrato_file = document.querySelector(".contrato_file");
        const radios_escritura = document.querySelectorAll('input[name="opcion"]');
        const radios_contrato = document.querySelectorAll('input[name="opcion2"]');

        function desmarcarRadios() {
        radios_escritura.forEach(function(radio) {
            radio.checked = false;  
        });
        radios_contrato.forEach(function(radio) {
            radio.checked = false; 
        }); 
        }

        function mostrar_campos() {
            const tipo = select.value;
            desmarcarRadios(); 
            subir_file();

            escritura.forEach(function(escritura) {
                escritura.style.setProperty('display', 'none', 'important');
            });
            contrato.forEach(function(contrato) {
                contrato.style.setProperty('display', 'none', 'important');
            });
            console.log(tipo);
                if (tipo === "1") {
                    escritura.forEach(function(escritura) {
                        escritura.style.setProperty('display', 'block', 'important');
                    });
                   console.log("entra");
                }
                else if (tipo === "4" || tipo === "5") {
                    contrato.forEach(function(contrato) {
                        contrato.style.setProperty('display', 'block', 'important');
                    });

                }
        }

        function subir_file(){
            escritura_file.style.setProperty('display', 'none', 'important');
            contrato_file.style.setProperty('display', 'none', 'important');

            const si = document.getElementById("si");
            const si2 = document.getElementById("si2");

            if (si.checked){
                escritura_file.style.setProperty('display', 'block', 'important');
            } else if (si2.checked){
                contrato_file.style.setProperty('display', 'block', 'important');
                console.log("entra");
            }
        }
        
        mostrar_campos();
        select.addEventListener("change", mostrar_campos);
        subir_file();
        const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(function(radio) {
                radio.addEventListener("change", subir_file);
            });

////////////////////// FORMULARIO ///////////////////////////////////

        const form = document.getElementById("agg");
        function validarFormulario(event) {
            const codigo = document.getElementById("codigo");
            const nombre = document.getElementById("nombre");
            const direccion = document.getElementById("direccion");
            const distrito = document.getElementById("distrito");
            const tipo = select.value;
            const nro_matricula = document.getElementById("nro_matricula");
            const ficha_catastral = document.getElementById("ficha_catastral");
            const valor_escritura = document.getElementById("valor_escritura");
            const fecha_registro = document.getElementById("fecha_registro");
            const codigo_contable = document.getElementById("codigo_contable");
            const escritura_file = document.getElementById("escritura");
            const nro_contrato = document.getElementById("nro_contrato");   
            const valor_contrato = document.getElementById("valor_contrato");
            const contrato_file = document.getElementById("contrato");
            const si = document.getElementById("si");
            const si2 = document.getElementById("si2");
            const no = document.getElementById("no");
            const no2 = document.getElementById("no2");
            let valido = true; 
            const v_contrato = parseFloat(valor_contrato.value);
            const v_escritura = parseFloat(valor_escritura.value);
            const v_contable_l = parseFloat(valor_contable_l.value);
            const v_contable_b = parseFloat(valor_contable_b.value);
            const v_avaluo_l = parseFloat(valor_avaluo_l.value);
            const v_avaluo_b = parseFloat(valor_avaluo_b.value);
            const matricula = parseInt(nro_matricula.value);
            const n_contrato = parseInt(nro_contrato.value);

            if (!codigo.value.trim() || !nombre.value.trim() || !direccion.value.trim() || !distrito.value.trim() || !select.value.trim()){
                Swal.fire({
                    icon: "error",
                    text: "Todos los campos deben ser diligenciados"
                })
                valido = false;
            }
        
            if (tipo === "1") {

                if (!nro_matricula.value.trim() || !ficha_catastral.value.trim() || !valor_escritura.value.trim() || !valor_contable_l.value || !valor_contable_b.value || !valor_avaluo_l.value || !valor_avaluo_b.value || !fecha_registro.value.trim()) {
                    Swal.fire({
                        icon: "error",
                        text: "Debe completar todos los campos de escritura"
                    });
                    valido = false;
                } else if (v_escritura < 0 || matricula < 0 || v_contable_l < 0 || v_contable_b < 0 || v_avaluo_l < 0 || v_avaluo_b < 0) {
                    Swal.fire({
                        icon: "error",
                        text: "Los numeros deben ser mayor o igual a 0"
                    });
                    valido = false;
                } else if (new Date(fecha_registro.value) > new Date()) {
                    Swal.fire({
                        icon: "error",
                        text: "La fecha de registro no puede ser posterior a la fecha actual"
                    });
                    valido = false;
                } else {
                    if (!si.checked && !no.checked) {
                        Swal.fire({
                          icon: "error",
                          text: "Debe seleccionar si tiene o no la escritura"
                        });
                        valido = false;
                    }
                    if (si.checked) {
                        if (!escritura_file.files.length) {
                            Swal.fire({
                              icon: "error",
                              text: "Debe subir un documento escaneado de la escritura"
                            });
                            valido = false;
                        } else {
                            const archivo = escritura_file.files[0];
                            const tipo_archivo = archivo.type;
                            if (tipo_archivo !== "application/pdf") {
                                Swal.fire({
                                    icon: "error",
                                    text: "El archivo de escritura debe ser un PDF"
                                });
                                valido = false;
                            }
                        }
                    }
                }
                
            }

            if (tipo  === "4" || tipo === "5") {
                if (!nro_contrato.value.trim() || !valor_contrato.value.trim()) {
                    Swal.fire({
                          icon: "error",
                          text: "Debe completar los campos del contrato"
                    });
                    valido = false;
                }  else if (n_contrato < 0) {
                    Swal.fire({
                        icon: "error",
                        text: "Los numeros deben ser mayor o igual a 0"
                    });
                    valido = false;
                } else {
                    if (!si2.checked &&!no2.checked) {
                        Swal.fire({
                          icon: "error",
                          text: "Debe seleccionar si tiene o no el contrato"
                        });
                        valido = false;
                    }
                    if (si2.checked) {
                        if (!contratoFile.files.length) {
                            Swal.fire({
                              icon: "error",
                              text: "Debe subir un documento escaneado del contrato"
                            });
                        valido = false;
                        }
                    }
                }
            }
        
            if (!valido) {
                event.preventDefault(); 
            }
        }
    
        form.addEventListener("submit", validarFormulario);
    });
</script>
</body>
</html>

