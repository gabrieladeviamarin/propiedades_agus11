<?php
session_start();
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();

$conteo = $con->prepare("SELECT COUNT(*) FROM distrito");
$conteo->execute();
$total = $conteo->fetchColumn();

$usuarios = $con->prepare("SELECT *
FROM usuario u
INNER JOIN estado e ON e.id_estado = u.id_estado
INNER JOIN rol r ON r.id_rol = u.id_rol
WHERE u.id_estado = 1 ORDER BY u.nombre ASC;");
$usuarios->execute();
$usuarios = $usuarios->fetchAll(PDO::FETCH_ASSOC);


if(isset($_POST['delete'])){
    $id_documento = $_POST['id_documento'];
    $delete = $con->prepare("UPDATE usuario SET id_estado = 2 WHERE id_documento = $id_documento");
    $delete->execute();
    echo json_encode(['success' => true]);
    exit();
}

$editar = null;

if(isset($_GET['cedula'])){
    $id_documento = $_GET['cedula'];
    $query = $con->prepare("SELECT * FROM usuario WHERE id_documento = $id_documento");
    $query->execute();  
    $editar = $query->fetch(PDO::FETCH_ASSOC);
}

if(isset($_POST['editar'])){
    $id_documento = $_POST['cedula_editar'];
    $nombre = $_POST['nombre_editar'];
    $email = $_POST['email_editar'];
    $query = $con->prepare("UPDATE usuario SET id_documento = $id_documento, nombre = '$nombre', email = '$email' WHERE id_documento= $id_documento");
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
                            text: 'Usuario Actualizado'
                        }).then(() => {
                            window.location.href = 'usuarios.php';
                        });
                    </script>
                    </body>
                    </html>";
                    exit();
}

if(isset($_POST['submit'])){
    $id_documento = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['id_rol'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $query = $con->prepare("SELECT * FROM usuario WHERE id_documento = $id_documento");
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
                            text: 'Ya existe un usuario con ese documento',
                        }).then(() => {
                            window.location.href = 'usuarios.php';
                        });
                    </script>
                    </body>
                    </html>";
                    exit();
    }
    $query = $con->prepare("INSERT INTO usuario (id_documento, nombre, email, password, id_rol, id_estado) VALUES ($id_documento, '$nombre', '$email', '$password_hash', $rol, 1)");
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
                            text: 'Usuario Creado'
                        }).then(() => {
                            window.location.href = 'usuarios.php';
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
    <title>Usuarios AGUS11</title>
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
                <h2>Listado de Usuarios</h2>
                
                <p style="font-weight:bold;">En total <?= $total?> Usuarios</p>
                <div class="filtros">
                    <input type="text" id="buscador" placeholder="Buscar por nombre">
                    <button type="button" class="btn btn-primary btn_all" data-bs-toggle="modal" data-bs-target="#agregar">Crear Usuario</button>
                </div>
                <table id="tabla" class="tabla">
                    <thead>
                        <tr>
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['id_documento'] ?></td>
                            <td><?=$usuario['nombre'] ?></td>
                            <td><?=$usuario['email']  ?></td>
                            <td><?=$usuario['nom_rol'] ?></td>
                            <td><?=$usuario['nom_estado']?></td>
                            <td>
                                <a href="?cedula=<?= $usuario['id_documento'] ?>" class="btn_editar" style="font-size: 1.4rem;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                |
                                <a href="#" class="btn_editar" onclick="confirmar_delete('<?= $usuario['id_documento'] ?>','<?= $usuario['nombre'] ?>')">
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
                        <h1 class="modal-title fs-5" id="TituloEditar">Editar Usuario</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="formEditar" method="post">
                            <input type="hidden" name="id_documento" value="<?= $editar['id_documento'] ?? '' ?>">
                            <div class="mb-3">
                                <label for="cedula_editar" class="form-label">Cedula</label>
                                <input type="text" class="form-control" id="cedula_editar" name="cedula_editar" value="<?= $editar['id_documento'] ?? '' ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="nombre_editar" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre_editar" name="nombre_editar" value="<?= $editar['nombre'] ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email_editar" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email_editar" name="email_editar" value="<?= $editar['email'] ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label for="rol_editar" class="form-label">Rol</label>
                                <input type="number" class="form-control" id="rol_editar" name="rol_editar" value="<?= $editar['nom_rol'] ?? '' ?>">
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
                        <h1 class="modal-title fs-5" id="Titulo">Crear Usuario</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="agg" method="post">
                            <div class="mb-3">
                                <label for="cedula" class="form-label">Cedula</label>
                                <input type="number" class="form-control" id="cedula" name="cedula">
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" >
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" >
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" >
                            </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn_cancelar" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" name="submit" class="btn btn_all">Crear Usuario</button>
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

