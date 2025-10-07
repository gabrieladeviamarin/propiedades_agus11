<?php
session_start();
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();

$propiedades = $con->prepare("SELECT p.cod_lugar, p.nom_lugar, p.direccion, t.nom_tipo FROM propiedades p
                                INNER JOIN tipo_propiedad t ON p.id_tip_prop = t.id_tip_prop ORDER BY cod_lugar ASC;");
$propiedades->execute();
$propiedades = $propiedades->fetchAll(PDO::FETCH_ASSOC);

$conteo = $con->prepare("SELECT COUNT(*) FROM propiedades");
$conteo->execute();
$total = $conteo->fetchColumn();
$distritos = $_SESSION['distritos'];

$tipo = $con->prepare("SELECT t.id_tip_prop, t.nom_tipo FROM tipo_propiedad t;");
$tipo->execute();
$tipo = $tipo->fetchAll(PDO::FETCH_ASSOC);
?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

                <div class="filtros">
                    <input type="text" id="buscador" placeholder="Buscar Por Nombre">
                    <select id="filtro">
                        <option value="">---  Filtrar por tipo  ---</option>
                        <option value="Propia">Propia</option>
                        <option value="Alquilada">Alquilada</option>
                        <option value="Prestamo">Prestamo</option>
                        <option value="Convenio">Convenio</option>
                        <option value="Compraventa">Compraventa</option>
                    </select>
                    <button type="button" class="btn btn-primary btn_all" data-bs-toggle="modal" data-bs-target="#agregar">Crear Propiedad</button>
                </div>

                <table id="tabla" class="tabla">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
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
                            <td><?= $propiedad["nom_tipo"] ?></td>
                            <td>
                                <a href="editar.php?codigo=<?= $propiedad['cod_lugar'] ?>" class="btn_editar">Editar</a>
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
                    <form action="agg_propiedad.php" method="post">
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Codigo</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" required>
                       
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Direccion</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <div class="mb-3">
                                <label for="distrito" class="form-label">Distrito</label>
                                <select class="form-select" id="distrito" name="distrito" required> 
                                    <option value="">---  Seleccione un distrito  ---</option>
                                    <?php foreach($distritos as $distrito):?>
                                        <option value="<?php echo $distrito['id_distrito']?>"><?php echo $distrito['nom_distrito']?></option>
                                    <?php endforeach;?>
                                </select>
                        </div>
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select class="form-select" id="tipo" name="tipo" required> 
                                <option value="0">---  Seleccione un tipo  ---</option>
                                <?php foreach($tipo as $tipos):?>
                                    <option value="<?php echo $tipos['id_tip_prop']?>"><?php echo $tipos['nom_tipo']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>  
<!---------------------------------------------- SI ESCRITURA ------------------------------------------------>
                        <div class="mb-3 escritura">
                            <label for="nro_matricula" class="form-label">Numero de Matricula</label>
                            <input type="text" class="form-control" id="nro_matricula" name="nro_matricula" required>
                        </div>
                        <div class="mb-3 escritura">
                            <label for="ficha_catastral" class="form-label">Ficha Catastral</label>
                            <input type="text" class="form-control" id="ficha_catastral" name="ficha_catastral" required>
                        </div>
                        <div class="mb-3 escritura">
                            <label for="valor_escritura" class="form-label">Valor</label>
                            <input type="number" class="form-control" id="valor_escritura" name="valor_escritura" required>
                        </div>
                        <div class="mb-3 escritura">
                            <label for="fecha_registro" class="form-label">Fecha de Registro</label>
                            <input type="text" class="form-control" id="fecha_registro" name="fecha_registro" required>
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
                            <input type="file" class="form-control" id="escritura" name="escritura">
                        </div>

<!----------------------- ----------------------- SI CONTRATO ------------------------------------------------>
                        <div class="mb-3 contrato">
                            <label for="nro_contrato" class="form-label">Numero de Contrato</label>
                            <input type="number" class="form-control" id="nro_contrato" name="nro_contrato">
                        </div> 
                        <div class="mb-3 contrato">
                            <label for="valor_contrato" class="form-label">Valor</label>
                            <input type="numer" class="form-control" id="valor_contrato" name="valor_contrato">
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
                            <input type="file" class="form-control" id="contrato" name="contrato">
                        </div>  
                  </div>
                                
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn_cancelar" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn_all">Crear Propiedad</button>
                  </div>

                </div>
              </div>
            </div>
        </main>   
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
///////////////// FILTROS Y DISPLAY DE CAMPOS AGG //////////////////
    document.addEventListener("DOMContentLoaded", () => {

        const buscador = document.getElementById("buscador");
        const filtro = document.getElementById("filtro");
        const filas = document.querySelectorAll("#tabla tbody tr");

        function filtrarTabla() {
            const texto = buscador.value.toLowerCase();
            const tipo = filtro.value;

            filas.forEach(fila => {
                const contenido = fila.textContent.toLowerCase();
                const tipoFila = fila.cells[3].textContent;

                const coincideTexto = contenido.includes(texto);
                const coincideFiltro = tipo === "" || tipoFila === tipo;

                fila.style.display = (coincideTexto && coincideFiltro) ? "" : "none";
            });
        }

        buscador.addEventListener("keyup", filtrarTabla);
        filtro.addEventListener("change", filtrarTabla);

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

            desmarcarRadios(); 
            subir_file();

            const tipo = select.value;

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
                else if (tipo === "2" || tipo === "4") {
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
    });
</script>
</body>
</html>

