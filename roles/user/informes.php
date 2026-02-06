<?php
session_start();
require_once('../../database/database.php');
$documento = $_SESSION['documento'];
$nombre = $_SESSION['nombre'];
$conexion = new database;
$con = $conexion->conectar();
    
// Obtener distritos
$stmt_distritos = $con->prepare("SELECT DISTINCT nom_distrito FROM distrito ORDER BY nom_distrito ASC");
$stmt_distritos->execute();
$distritos = $stmt_distritos->fetchAll(PDO::FETCH_COLUMN);

// Obtener tipos de propiedad
$stmt_tipos = $con->prepare("SELECT DISTINCT nom_tipo FROM tipo_propiedad ORDER BY nom_tipo ASC");
$stmt_tipos->execute();
$tipos = $stmt_tipos->fetchAll(PDO::FETCH_COLUMN);

// Obtener datos de propiedades
$query = "SELECT p.cod_lugar, p.nom_lugar, p.direccion, d.nom_distrito,
                  t.nom_tipo, e.nro_matricula, e.ficha_catastral, e.documento_pdf,
                  c.nro_contrato, c.valor_contrato, s.precio, estado.nom_estado AS estado
          FROM propiedades p
          LEFT JOIN distrito d ON p.id_distrito = d.id_distrito
          LEFT JOIN tipo_propiedad t ON p.id_tip_prop = t.id_tip_prop
          LEFT JOIN escritura e ON p.id_lugar = e.id_lugar
          LEFT JOIN contrato c ON p.id_lugar = c.id_lugar
          LEFT JOIN seguros s ON p.id_lugar = s.id_lugar
          LEFT JOIN estado ON p.id_estado = estado.id_estado
          ORDER BY d.nom_distrito ASC";

$stmt_datos = $con->prepare($query);
$stmt_datos->execute();
$datos = $stmt_datos->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes AGUS11</title>
    <link rel="stylesheet" href="css/informes.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
 
</head>
<body>
    <div class="container_grid">
        <aside class="aside_barra">
        <?php include ('asideBar/asideBar.php') ?>
        </aside>
        <main class="grid_contenido_plantilla">
            <div class="contenedor">
                <h2>Informes de Propiedades</h2>
                
                <div class="filtros">
                    <select id="filtro-escritura">
                        <option value="">Por Escritura Física</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                    <select id="filtro-distrito">
                        <option value="">Todos los Distritos</option>
                        <?php foreach($distritos as $distrito): ?>
                            <option value="<?php echo $distrito; ?>"><?php echo $distrito; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="filtro-tipo">
                        <option value="">Todos los Tipos de Propiedad</option>
                        <?php foreach($tipos as $tipo): ?>
                            <option value="<?php echo $tipo; ?>"><?php echo $tipo; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btns" id="btn-exportar">Descargar <strong>EXCEL</strong></button>
                </div>

                <table class="tabla table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Propiedad</th>
                            <th>Tipo Propiedad</th>
                            <th>Nro Matrícula</th>
                            <th>Ficha Catastral</th>
                            <th>Nro Contrato</th>
                            <th>Seguro</th> 
                            <th>Escritura Física</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        <!-- Aqui va el js -->
                    </tbody>
                </table>
            </div>
        </main>   
    </div>

    <script>
        // Datos de las propiedades desde PHP
        const datosCompletos = <?php echo json_encode($datos); ?>;
        
        // Función para mostrar la tabla
        function mostrarTabla() {
            const filtroEscritura = document.getElementById('filtro-escritura').value;
            const filtroDistrito = document.getElementById('filtro-distrito').value;
            const filtroTipo = document.getElementById('filtro-tipo').value;

            // Filtrar datos
            let datosFiltrados = datosCompletos.filter(row => {
                let cumple = true;

                // Filtro por distrito
                if(filtroDistrito && row.nom_distrito !== filtroDistrito) {
                    cumple = false;
                }

                // Filtro por tipo
                if(filtroTipo && row.nom_tipo !== filtroTipo) {
                    cumple = false;
                }

                // Filtro por escritura
                if(filtroEscritura) {
                    const tieneEscritura = row.documento_pdf && row.documento_pdf.trim() !== '';
                    if(filtroEscritura === 'si' && !tieneEscritura) {
                        cumple = false;
                    }
                    if(filtroEscritura === 'no' && tieneEscritura) {
                        cumple = false;
                    }
                }

                return cumple;
            });

            // Llenar la tabla
            const tablaBody = document.getElementById('tabla-body');
            tablaBody.innerHTML = '';

            datosFiltrados.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.cod_lugar || ''}</td>
                    <td>${row.nom_lugar || ''}</td>
                    <td>${row.nom_tipo || ''}</td>
                    <td>${row.nro_matricula || ''}</td>
                    <td>${row.ficha_catastral || ''}</td>
                    <td>${row.nro_contrato || ''}</td>
                    <td>${row.precio || ''}</td>
                    <td>${(row.documento_pdf && row.documento_pdf.trim() !== '') ? 'Sí' : 'No'}</td>
                `;
                tablaBody.appendChild(tr);
            });
        }

        // Mostrar tabla al cargar
        document.addEventListener('DOMContentLoaded', function() {
            mostrarTabla();
        });

        // Filtrar cuando cambian los selects
        document.getElementById('filtro-escritura').addEventListener('change', function() {
            mostrarTabla();
        });

        document.getElementById('filtro-distrito').addEventListener('change', function() {
            mostrarTabla();
        });

        document.getElementById('filtro-tipo').addEventListener('change', function() {
            mostrarTabla();
        });

        // Exportar a Excel
        document.getElementById('btn-exportar').addEventListener('click', function() {
            const filtroEscritura = document.getElementById('filtro-escritura').value;
            const filtroDistrito = document.getElementById('filtro-distrito').value;
            const filtroTipo = document.getElementById('filtro-tipo').value;

            // Crear formulario oculto para enviar datos
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'exportar_excel.php';

            const inputExportar = document.createElement('input');
            inputExportar.type = 'hidden';
            inputExportar.name = 'exportar';
            inputExportar.value = 'excel';

            const inputEscritura = document.createElement('input');
            inputEscritura.type = 'hidden';
            inputEscritura.name = 'filtro_escritura';
            inputEscritura.value = filtroEscritura;

            const inputDistrito = document.createElement('input');
            inputDistrito.type = 'hidden';
            inputDistrito.name = 'filtro_distrito';
            inputDistrito.value = filtroDistrito;

            const inputTipo = document.createElement('input');
            inputTipo.type = 'hidden';
            inputTipo.name = 'filtro_tipo';
            inputTipo.value = filtroTipo;

            form.appendChild(inputExportar);
            form.appendChild(inputEscritura);
            form.appendChild(inputDistrito);
            form.appendChild(inputTipo);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        });
    </script>
</body>
</html>

