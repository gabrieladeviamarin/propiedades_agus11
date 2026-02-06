<?php
session_start();
require_once('../../database/database.php');

$documento = $_SESSION['documento'];
$nombre = $_SESSION['nombre'];

$conexion = new database;
$con = $conexion->conectar();

/* SIN ESCRITURA */
$sin_escritura = $con->prepare("
    SELECT p.nom_lugar, p.direccion, d.nom_distrito 
    FROM propiedades p
    INNER JOIN distrito d ON p.id_distrito = d.id_distrito
    LEFT JOIN escritura e ON p.id_lugar = e.id_lugar
    WHERE p.id_tip_prop = '1' AND e.id_lugar IS NULL
");
$sin_escritura->execute();
$sin_escritura = $sin_escritura->fetchAll(PDO::FETCH_ASSOC);

/* TOTAL */
$conteo = $con->prepare("SELECT COUNT(*) FROM propiedades");
$conteo->execute();
$total = $conteo->fetchColumn();

/* DONA */
$propiedades = $con->prepare("
    SELECT t.nom_tipo, COUNT(p.id_lugar) AS tipo_propiedades
    FROM tipo_propiedad t
    LEFT JOIN propiedades p ON t.id_tip_prop = p.id_tip_prop
    GROUP BY t.id_tip_prop, t.nom_tipo
    ORDER BY t.nom_tipo
");
$propiedades->execute();
$propiedades = $propiedades->fetchAll(PDO::FETCH_ASSOC);

/* BARRAS */
$distritos = $con->prepare("
    SELECT d.nom_distrito, COUNT(p.id_lugar) AS total_propiedades
    FROM distrito d
    LEFT JOIN propiedades p ON d.id_distrito = p.id_distrito
    GROUP BY d.id_distrito, d.nom_distrito
    ORDER BY d.nom_distrito
");
$distritos->execute();
$distritos = $distritos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Propiedades AGUS11</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body>

<div class="container_grid">
    <aside class="aside_barra">
        <?php include ('asideBar/asideBar.php') ?>
    </aside>

    <main class="grid_contenido_plantilla">

        <div class="home_titulo">
            <div class="info">
                <span class="nombre">Bienvenid@, <?= $nombre ?></span>
                <p class="rol">Rol: Usuario</p>
            </div>
            <img src="assets/user_negro.png" class="perfil-img">
        </div>

        <div class="scroll_container">
            <div class="card">
                <p>Total Propiedades: <?= $total ?></p>
            </div>

            <?php foreach ($sin_escritura as $row) { ?>
                <div class="card">
                    <h2><?= $row['nom_lugar'] ?></h2>
                    <h3>Distrito <?= $row['nom_distrito'] ?></h3>
                    <h3>Dirección <?= $row['direccion'] ?></h3>
                    <span>SIN ESCRITURA</span>
                </div>
            <?php } ?>
        </div>

        <div class="graficas">
            <div class="grafica">
                <div id="barra"></div>
            </div>
            <div class="grafica">
                <div id="dona"></div>
            </div>
        </div>

    </main>
</div>

<script>
const propiedades = <?= json_encode($propiedades); ?>;
const distritos = <?= json_encode($distritos); ?>;

/* ================= DONA ================= */
var optionsDona = {
    series: propiedades.map(p => p.tipo_propiedades),
    chart: {
        type: 'donut',
        width: 600,
        height: 700
    },
    labels: propiedades.map(p => p.nom_tipo),
    colors: [
        "#e68a2e",
        "#d49a0e",
        "#f9e09d",
        "#7cc9e6",
        "#1591a8",
        "#164d57"
    ],
    plotOptions: {
        pie: {
            startAngle: -90,
            endAngle: 270
        }
    },
    dataLabels: {
        enabled: true,
        formatter: function (val) {
            return val.toFixed(1) + "%";
        }
    },
    fill: {
        type: 'gradient'
    },
    legend: {
        position: 'right',
        formatter: function (val, opts) {
            return val + ": " + opts.w.globals.series[opts.seriesIndex];
        }
    },
    title: {
        text: 'Estado de Pertenencia',
        align: 'center',
        style: {
            fontSize: '18px',
            fontWeight: 'bold'
        }
    }
};

var chartDona = new ApexCharts(document.querySelector("#dona"), optionsDona);
chartDona.render();

/* ================= BARRAS ================= */
var optionsBarra = {
    series: [{
        name: "Propiedades",
        data: distritos.map(d => d.total_propiedades)
    }],
    chart: {
        type: 'bar',
        height: 500,
        width: 600
    },
    colors: ["#25aed0"],
    plotOptions: {
        bar: {
            borderRadius: 4,
            horizontal: true,
            barHeight: '70%'
        }
    },
    dataLabels: {
        enabled: true,
        formatter: function (val) {
            return val;
        }
    },
    xaxis: {
        categories: distritos.map(d => d.nom_distrito)
    },
    title: {
        text: 'Propiedades por Distrito',
        align: 'center',
        style: {
            fontSize: '18px',
            fontWeight: 'bold'
        }
    },
    legend: {
        show: false
    }
};

var chartBarra = new ApexCharts(document.querySelector("#barra"), optionsBarra);
chartBarra.render();
</script>

</body>
</html>
