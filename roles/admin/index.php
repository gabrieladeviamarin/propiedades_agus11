<?php
session_start();
require_once('../../database/database.php');
$documento = $_SESSION['documento'];
$nombre = $_SESSION['nombre'];
$conexion = new database;
$con = $conexion->conectar();

$sin_escritura = $con->prepare("SELECT p.nom_lugar, p.direccion, d.nom_distrito FROM propiedades p 
                                INNER JOIN distrito d ON p.id_distrito = d.id_distrito 
                                LEFT JOIN escritura e ON p.id_lugar = e.id_lugar 
                                WHERE p.id_tip_prop = '1' AND e.id_lugar IS NULL;");
$sin_escritura->execute();
$sin_escritura = $sin_escritura->fetchAll(PDO::FETCH_ASSOC);

$conteo = $con->prepare("SELECT COUNT(*) FROM propiedades");
$conteo->execute();
$total = $conteo->fetchColumn();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<!-- ///////////////////////////////////   HEADER    //////////////////////////////////////////////// -->
            <div class="home_titulo">

                <div class="info">
                  <span class="nombre">Bienvenid@, <?php echo $nombre ?></span>
                  <p class="rol">Rol: Administrador</p>
                </div>
                <img src="assets/user_negro.png" alt="Foto perfil" class="perfil-img">
            </div>
            
<!-- 
/////////////////////////////////// SCROLL DE SIN ESCRITURAS ///////////////////////////////////////// -->
            <div class="scroll_container">
                <div class="card">
                    <p>Total Propiedades : <?php echo $total;?></p>
                </div>
                <?php foreach ($sin_escritura as $total) { ?>
                   <div class="card">
                        <h2><?php echo $total['nom_lugar'];?></h2>
                        <h3>Distrito <?php echo $total['nom_distrito'];?></h3>
                        <h3>Direccion  <?php echo $total['direccion'];?></h3>
                        <span>SIN ESCRITURA</span>
                   </div>
                <?php }?>
            </div>
<!-- ///////////////////////////////////////   GRAFICAS   //////////////////////////////////////////// -->
            
            <div class="graficas">
                <div class="grafica"> 
                    <div id="barra" class="grafica_barras"></div>
                </div>
                <div class="grafica"> 
                    <div id="dona" class="grafica_dona"></div>    
                </div>
            </div> 
        </main>   
    </div>

    <?php
///////////////////////////// CONSULTAS PARA GRAFICAS ////////////////////////////////////////////////

        $propiedades = $con->prepare("SELECT t.nom_tipo, COUNT(p.id_lugar) AS tipo_propiedades FROM tipo_propiedad t 
        LEFT JOIN propiedades p ON t.id_tip_prop = p.id_tip_prop GROUP BY t.id_tip_prop, t.nom_tipo ORDER BY t.nom_tipo");
        $propiedades->execute();
        $propiedades = $propiedades->fetchAll(PDO::FETCH_ASSOC);

        $distritos = $con->prepare("SELECT d.id_distrito, d.nom_distrito, COUNT(p.id_lugar) AS total_propiedades FROM distrito d 
        LEFT JOIN propiedades p ON d.id_distrito = p.id_distrito GROUP BY d.id_distrito, d.nom_distrito ORDER BY d.nom_distrito");
        $distritos->execute();
        $distritos = $distritos->fetchAll(PDO::FETCH_ASSOC);
    ?>

<script>

    const propiedades = <?php echo json_encode($propiedades); ?>;
    const distritos = <?php echo json_encode($distritos); ?>;

////////////////////// GRAFICO DONA /////////////////////////////////
    var optionsDona = {
        series: propiedades.map(item => item.tipo_propiedades), 
        chart: {
            type: 'donut',
            width: 600,  
            height: 700,
            toolbar: {
                show: true,
                tools: {
                    download: true, // Activa la opción de descarga
                },
            },
        },
        labels: propiedades.map(item => item.nom_tipo),
        colors: [ "#e68a2e","#d49a0e","#f9e09d","#7cc9e6","#1591a8","#164d57"],
        plotOptions: {
            pie: {
                startAngle: -90,
                endAngle: 270
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val, opts) {
            var percentage = (val / opts.w.globals.seriesTotals[opts.seriesIndex]) * 100;
            return `${percentage.toFixed(1)}%`; 
            }
        },
        fill: {
            type: 'gradient'
        },
        legend: {
            position: 'right',
            formatter: function (val, opts) {
                return val + " " + opts.w.globals.series[opts.seriesIndex];
            }
        },
        title: {
            text: 'Estado de Pertenencia',
            align: 'center',
            style: {
                fontSize: '18px',
                fontWeight: 'bold'
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 250
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    var chartDona = new ApexCharts(document.querySelector("#dona"), optionsDona);
    chartDona.render();

///////////////////////////////// GRAFICA BARRAS //////////////////////////////////////////
        var options = {
        series: [{
            name: "Propiedades",
            data: distritos.map(item => item.total_propiedades)
        }],
        chart: {
            type: 'bar',
            height: 500,
            width: 600, 
            dropShadow: { enabled: true }
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
        title: {
            text: 'Propiedades por Distrito',
            align: 'center',
            style: {
                fontSize: '18px',
                fontWeight: 'bold'
            }
        },
        xaxis: {
            categories: distritos.map(item => item.nom_distrito),
            labels: {
                style: { fontSize: '14px'
                 }
            }
        },
        yaxis: {
            opposite: false,
            labels: {
                align: 'left', 
                offsetX: -15, 
                style: { fontSize: '14px' },
                trim: false
            }
        },
        grid: {
            padding: {
                left: 0,   
                right: 0
            }
        },
        legend: {
            show: false
        }
    };
    var chart = new ApexCharts(document.querySelector("#barra"), options);
    chart.render();


</script>

</body>
</html>

