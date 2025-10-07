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

?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/propiedades.css">
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
            
        </main>   
    </div>

    <script>
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

    });

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
    
</script>
</body>
</html>

