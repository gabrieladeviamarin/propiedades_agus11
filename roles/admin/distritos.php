    <?php
    session_start();
    require_once('../../database/database.php');
    $conexion = new database;
    $con = $conexion->conectar();

    $conteo = $con->prepare("SELECT COUNT(*) FROM distrito");
    $conteo->execute();
    $total = $conteo->fetchColumn();

    $distritos = $con->prepare("SELECT * FROM distrito WHERE id_estado = 1 ORDER BY cod_distrito ASC");
    $distritos->execute();
    $distritos = $distritos->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_POST['delete'])){
        $cod_distrito = $_POST['cod_distrito'];
        $delete = $con->prepare("UPDATE distrito SET id_estado = 2 WHERE cod_distrito = '$cod_distrito'");
        $delete->execute();

        echo json_encode(['success' => true]);
        exit();
    }

    $editar = null;

    if(isset($_GET['codigo'])){
        $cod_distrito = $_GET['codigo'];
        $query = $con->prepare("SELECT * FROM distrito WHERE cod_distrito = '$cod_distrito'");
        $query->execute();  
        $editar = $query->fetch(PDO::FETCH_ASSOC);
    }

    if(isset($_POST['editar'])){
        $cod_distrito = $_POST['cod_distrito'];
        $nom_distrito = $_POST['nombre'];
        $pastor = $_POST['pastor'];
        $telefono_pr = $_POST['celular'];   

        $query = $con->prepare("UPDATE distrito SET cod_distrito = '$cod_distrito', nom_distrito = '$nom_distrito', pastor = '$pastor', telefono_pr = $telefono_pr WHERE cod_distrito = '$cod_distrito'");
        $query->execute();
        $editar = null;
        echo "<!DOCTYPE html>
                        <html>
                        <head>
                            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        </head>
                        <body>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                text: 'Distrito Actualizado'
                            }).then(() => {
                                window.location.href = 'distritos.php';
                            });
                        </script>
                        </body>
                        </html>";
                        exit();
    }

    if(isset($_POST['submit'])){
        $cod_distrito = $_POST['codigo'];
        $nom_distrito = $_POST['nombre'];
        $pastor = $_POST['pastor'];
        $telefono_pr = $_POST['celular'];

        $query = $con->prepare("SELECT * FROM distrito WHERE cod_distrito = '$cod_distrito'");
        $query->execute();
        $query = $query->fetch(PDO::FETCH_ASSOC);

        if($query){
            echo "<!DOCTYPE html>
                        <html>
                        <head>
                            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        </head>
                        <body>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                text: 'Ya existe un distrito con ese codigo',
                            }).then(() => {
                                window.location.href = 'distritos.php';
                            });
                        </script>
                        </body>
                        </html>";
                        exit();
        }
        $query = $con->prepare("INSERT INTO distrito (cod_distrito, nom_distrito, pastor, telefono_pr, id_estado) VALUES ('$cod_distrito', '$nom_distrito', '$pastor', $telefono_pr, 1)");
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
                                text: 'Distrito Creado'
                            }).then(() => {
                                window.location.href = 'distritos.php';
                            });
                        </script>
                        </body>
                        </html>";
                        exit();
        exit();
    }

    ?><!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="css/distritos.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container_grid">
            <aside class="aside_barra">
            <?php include ('asideBar/asideBar.php') ?>
            </aside>
            <main class="grid_contenido_plantilla">
            <div class="contenedor">
                    <h2>Listado de Distritos</h2>
                    
                    <p style="font-weight:bold;">En total <?= $total?> Distritos</p>
                    <div class="filtros">
                        <input type="text" id="buscador" placeholder="Buscar por nombre">
                        <button type="button" class="btn btn-primary btn_all" data-bs-toggle="modal" data-bs-target="#agregar">Crear Distrito</button>
                    </div>

                    <table id="tabla" class="tabla">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Distrito</th>
                                <th>Pastor</th>
                                <th>Celular</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($distritos as $distrito): ?>
                            <tr>
                                <td><?= $distrito['cod_distrito'] ?></td>
                                <td><?= $distrito["nom_distrito"] ?></td>
                                <td><?= $distrito["pastor"] ?></td>
                                <td><?= $distrito["telefono_pr"]?></td>
                                <td>
                                    <a href="?codigo=<?= $distrito['cod_distrito'] ?>" class="btn_editar" style="font-size: 1.4rem;">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    |
                                    <a href="#" class="btn_editar" onclick="confirmar_delete('<?= $distrito['cod_distrito'] ?>','<?= $distrito['nom_distrito'] ?>')">
                                        <i class="bi bi-slash-circle" style="font-size: 1.4rem;" title="Inhabilitar"></i> 
                                    </a>
                        
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button id="btn_top" class="btn_top">↑</button>
                </div>
    <!--------------------------------------- MODAL DE EDITAR ---------------------------------------------->
                <div class="modal fade" id="modal_editar" tabindex="-1" aria-labelledby="TituloEditar" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="TituloEditar">Editar Distrito</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" id="formEditar" method="post">
                                <input type="hidden" name="cod_distrito" value="<?= $editar['cod_distrito'] ?? '' ?>">
                                <div class="mb-3">
                                    <label for="codigo_editar" class="form-label">Codigo</label>
                                    <input type="text" class="form-control" id="codigo_editar" name="codigo" value="<?= $editar['cod_distrito'] ?? '' ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="nombre_editar" class="form-label">Nombre Distrito</label>
                                    <input type="text" class="form-control" id="nombre_editar" name="nombre" value="<?= $editar['nom_distrito'] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="pastor_editar" class="form-label">Nombre Completo del Pastor</label>
                                    <input type="text" class="form-control" id="pastor_editar" name="pastor" value="<?= $editar['pastor'] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="celular_editar" class="form-label">Celular del Pastor</label>
                                    <input type="number" class="form-control" id="celular_editar" name="celular" value="<?= $editar['telefono_pr'] ?? '' ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn_cancelar" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" name="editar" class="btn btn_all">Actualizar Distrito</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    <!--------------------------------------- MODAL DE AGG ---------------------------------------------->
                <div class="modal fade" id="agregar" tabindex="-1" aria-labelledby="Titulo" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="Titulo">Crear Distrito</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" id="agg" method="post">
                                <div class="mb-3">
                                    <label for="codigo" class="form-label">Codigo</label>
                                    <input type="text" class="form-control" id="codigo" name="codigo">
                                </div>
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre Distrito</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" >
                                </div>
                                <div class="mb-3">
                                    <label for="pastor" class="form-label">Nombre Completo del Pastor</label>
                                    <input type="text" class="form-control" id="pastor" name="pastor" >
                                </div>
                                <div class="mb-3">
                                    <label for="celular" class="form-label">Celular del Pastor</label>
                                    <input type="number" class="form-control" id="celular" name="celular" >
                                </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn_cancelar" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" name="submit" class="btn btn_all">Crear Distrito</button>
                            </div>
                            </form>
                        </div>
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
                html: `¿Desea inhabilitar el distrito <strong>${nombre}</strong>?<br><small class="text-muted">Esta acción no se puede deshacer</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Inhabilitar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    fetch('distritos.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `delete=1&cod_distrito=${codigo}`
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
            const filas = document.querySelectorAll("#tabla tbody tr");

            function filtrarTabla() {
                const texto = buscador.value.toLowerCase();

                filas.forEach(fila => {
                    const contenido = fila.textContent.toLowerCase();

                    const coincide_busc = contenido.includes(texto);

                    fila.style.display = (coincide_busc) ? "" : "none";
                });
            }

            buscador.addEventListener("keyup", filtrarTabla);


    ////////////////////// FORMULARIO EDITAR Y AGG ///////////////////////////////////
        function cerrar_modal() {
            window.location.href = 'distritos.php';
        }
        <?php if($editar){ ?>
            const modal_editar = new bootstrap.Modal(document.getElementById('modal_editar'));
            modal_editar.show();
            document.getElementById('modal_editar').addEventListener('hidden.bs.modal', function () {
                cerrar_modal();
            });
        <?php }; ?>
        
        const formEditar = document.getElementById("formEditar");
        
        if(formEditar) {
            formEditar.addEventListener("submit", function(event) {
                const nombre = document.getElementById("nombre_editar");
                const pastor = document.getElementById("pastor_editar");
                const celular = document.getElementById("celular_editar");
                let valido = true;
            
                if (!nombre.value.trim() || !pastor.value.trim() || !celular.value.trim()) {
                    Swal.fire({
                        icon: "error",
                        text: "Todos los campos deben ser diligenciados"
                    });
                    valido = false;
                } else if (!/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/.test(nombre.value)) {
                    Swal.fire({
                        icon: "error",
                        text: "El nombre del distrito solo puede contener letras"
                    });
                    valido = false;
                } else if (pastor.value.length < 6 || !/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/.test(pastor.value)) {
                    Swal.fire({
                        icon: "error",
                        text: "El nombre del pastor solo puede contener letras y minimo 6 caracteres"
                    });
                    valido = false;
                } else if (!/^\d{10}$/.test(celular.value)) {
                    Swal.fire({
                        icon: "error",
                        text: "El número debe tener exactamente 10 digitos"
                    });
                    valido = false;
                }
            
                if (!valido) {
                    event.preventDefault();
                }
            });
        }

        const form = document.getElementById("agg");

        function validarFormulario(event) {
            const codigo = document.getElementById("codigo");
            const nombre = document.getElementById("nombre");
            const pastor = document.getElementById("pastor");
            const celular = document.getElementById("celular");
            let valido = true;
        
            if (!codigo.value.trim() || !nombre.value.trim() || !pastor.value.trim() || !celular.value.trim()) {
                Swal.fire({
                    icon: "error",
                    text: "Todos los campos deben ser diligenciados"
                });
                valido = false;
            
            } else if (!/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/.test(nombre.value)) {
                Swal.fire({
                    icon: "error",
                    text: "El nombre del distrito solo puede contener letras"
                });
                valido = false;
            
            } else if (pastor.value.length < 6 || !/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/.test(pastor.value)) {
                Swal.fire({
                    icon: "error",
                    text: "El nombre del pastor solo puede contener letras y minimo 6 caracteres"
                });
                valido = false;
            
            } else if (!/^\d{10}$/.test(celular.value)) {
                Swal.fire({
                    icon: "error",
                    text: "El número debe tener exactamente 10 digitos"
                });
                valido = false;
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

