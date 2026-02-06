<?php
session_start();
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();

if (!isset($_SESSION['documento'])) {
    header('Location: ../../login.php');
    exit();
}

$documento = $_SESSION['documento'];

// Obtener datos del usuario actual
$query = $con->prepare("SELECT u.*, r.nom_rol 
                        FROM usuario u 
                        INNER JOIN rol r ON r.id_rol = u.id_rol 
                        WHERE u.id_documento = :documento");
$query->bindParam(':documento', $documento);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_ASSOC);

// Actualizar email
if (isset($_POST['update_email'])) {
    $nuevo_email = trim($_POST['nuevo_email']);
    $password_confirm = $_POST['password_confirm_email'];
    
    // Verificar contraseña actual
    $query = $con->prepare("SELECT password FROM usuario WHERE id_documento = :documento");
    $query->bindParam(':documento', $documento);
    $query->execute();
    $user_data = $query->fetch(PDO::FETCH_ASSOC);
    
    if (password_verify($password_confirm, $user_data['password'])) {
        // Verificar si el email ya existe
        $check_email = $con->prepare("SELECT id_documento FROM usuario WHERE email = :email AND id_documento != :documento");
        $check_email->bindParam(':email', $nuevo_email);
        $check_email->bindParam(':documento', $documento);
        $check_email->execute();
        
        if ($check_email->rowCount() > 0) {
            echo "<!DOCTYPE html>
                    <html>
                    <head>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    </head>
                    <body>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Este correo ya está en uso por otro usuario'
                        }).then(() => {
                            window.location.href = 'configuracion.php';
                        });
                    </script>
                    </body>
                    </html>";
            exit();
        }
        
        // Actualizar email
        $update = $con->prepare("UPDATE usuario SET email = :email WHERE id_documento = :documento");
        $update->bindParam(':email', $nuevo_email);
        $update->bindParam(':documento', $documento);
        $update->execute();
        
        echo "<!DOCTYPE html>
                <html>
                <head>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Correo actualizado!',
                        text: 'Tu correo electrónico ha sido actualizado correctamente'
                    }).then(() => {
                        window.location.href = 'configuracion.php';
                    });
                </script>
                </body>
                </html>";
        exit();
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
                        title: 'Error',
                        text: 'La contraseña ingresada es incorrecta'
                    }).then(() => {
                        window.location.href = 'configuracion.php';
                    });
                </script>
                </body>
                </html>";
        exit();
    }
}

// Actualizar contraseña
if (isset($_POST['update_password'])) {
    $password_actual = $_POST['password_actual'];
    $nueva_password = $_POST['nueva_password'];
    $confirmar_password = $_POST['confirmar_password'];
    
    // Verificar contraseña actual
    $query = $con->prepare("SELECT password FROM usuario WHERE id_documento = :documento");
    $query->bindParam(':documento', $documento);
    $query->execute();
    $user_data = $query->fetch(PDO::FETCH_ASSOC);
    
    if (password_verify($password_actual, $user_data['password'])) {
        if ($nueva_password === $confirmar_password) {
            // Actualizar contraseña
            $password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
            $update = $con->prepare("UPDATE usuario SET password = :password WHERE id_documento = :documento");
            $update->bindParam(':password', $password_hash);
            $update->bindParam(':documento', $documento);
            $update->execute();
            
            echo "<!DOCTYPE html>
                    <html>
                    <head>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    </head>
                    <body>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: '¡Contraseña actualizada!',
                            text: 'Tu contraseña ha sido actualizada correctamente'
                        }).then(() => {
                            window.location.href = 'configuracion.php';
                        });
                    </script>
                    </body>
                    </html>";
            exit();
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
                            title: 'Error',
                            text: 'Las contraseñas nuevas no coinciden'
                        }).then(() => {
                            window.location.href = 'configuracion.php';
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
                        title: 'Error',
                        text: 'La contraseña actual es incorrecta'
                    }).then(() => {
                        window.location.href = 'configuracion.php';
                    });
                </script>
                </body>
                </html>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - AGUS11</title>
    <link rel="stylesheet" href="css/configuracion.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container_grid">
        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>
        <main class="grid_contenido_plantilla">
            <div class="contenedor">
                <div class="header-config">
                    <h2>Configuración de Cuenta</h2>
                    <p class="subtitle">Administra tu información personal y seguridad</p>
                </div>

                <!-- Información del Usuario -->
                <div class="card-config info-card">
                    <div class="card-header-config">
                        <i class="bi bi-person-circle"></i>
                        <h3>Información Personal</h3>
                    </div>
                    <div class="card-body-config">
                        <div class="info-item">
                            <label>Nombre:</label>
                            <span><?= htmlspecialchars($usuario['nombre']) ?></span>
                        </div>
                        <div class="info-item">
                            <label>Documento:</label>
                            <span><?= htmlspecialchars($usuario['id_documento']) ?></span>
                        </div>
                        <div class="info-item">
                            <label>Correo electrónico:</label>
                            <span><?= htmlspecialchars($usuario['email']) ?></span>
                        </div>
                        <div class="info-item">
                            <label>Rol:</label>
                            <span class="badge-rol"><?= htmlspecialchars($usuario['nom_rol']) ?></span>
                        </div>
                    </div>
                </div>

                <div class="cards-container">
                    <!-- Actualizar Email -->
                    <div class="card-config">
                        <div class="card-header-config">
                            <i class="bi bi-envelope-fill"></i>
                            <h3>Actualizar Correo Electrónico</h3>
                        </div>
                        <div class="card-body-config">
                            <form id="form_email" method="POST">
                                <div class="form-group-config">
                                    <label for="email_actual">Correo actual</label>
                                    <input type="email" class="form-control-config" id="email_actual" value="<?= htmlspecialchars($usuario['email']) ?>" readonly>
                                </div>
                                <div class="form-group-config">
                                    <label for="nuevo_email">Nuevo correo electrónico</label>
                                    <input type="email" class="form-control-config" id="nuevo_email" name="nuevo_email" placeholder="correo@ejemplo.com" required>
                                </div>
                                <div class="form-group-config">
                                    <label for="password_confirm_email">Confirma tu contraseña</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" class="form-control-config" id="password_confirm_email" name="password_confirm_email" placeholder="Ingresa tu contraseña actual" required>
                                        <i class="bi bi-eye-slash toggle-password" data-target="password_confirm_email"></i>
                                    </div>
                                </div>
                                <button type="submit" name="update_email" class="btn-config btn-primary-config">
                                    <i class="bi bi-check-circle"></i> Actualizar Correo
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Cambiar Contraseña -->
                    <div class="card-config">
                        <div class="card-header-config">
                            <i class="bi bi-shield-lock-fill"></i>
                            <h3>Cambiar Contraseña</h3>
                        </div>
                        <div class="card-body-config">
                            <form id="form_password" method="POST">
                                <div class="form-group-config">
                                    <label for="password_actual">Contraseña actual</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" class="form-control-config" id="password_actual" name="password_actual" placeholder="Ingresa tu contraseña actual" required>
                                        <i class="bi bi-eye-slash toggle-password" data-target="password_actual"></i>
                                    </div>
                                </div>
                                <div class="form-group-config">
                                    <label for="nueva_password">Nueva contraseña</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" class="form-control-config" id="nueva_password" name="nueva_password" placeholder="Mínimo 6 caracteres" required>
                                        <i class="bi bi-eye-slash toggle-password" data-target="nueva_password"></i>
                                    </div>
                                    <small class="password-strength" id="password_strength"></small>
                                </div>
                                <div class="form-group-config">
                                    <label for="confirmar_password">Confirmar nueva contraseña</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" class="form-control-config" id="confirmar_password" name="confirmar_password" placeholder="Repite la nueva contraseña" required>
                                        <i class="bi bi-eye-slash toggle-password" data-target="confirmar_password"></i>
                                    </div>
                                </div>
                                <button type="submit" name="update_password" class="btn-config btn-primary-config">
                                    <i class="bi bi-check-circle"></i> Cambiar Contraseña
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Consejos de Seguridad -->
                <div class="card-config security-tips">
                    <div class="card-header-config">
                        <i class="bi bi-lightbulb-fill"></i>
                        <h3>Consejos de Seguridad</h3>
                    </div>
                    <div class="card-body-config">
                        <ul class="tips-list">
                            <li><i class="bi bi-check-circle-fill"></i> Usa una contraseña única que no utilices en otros sitios</li>
                            <li><i class="bi bi-check-circle-fill"></i> La contraseña debe tener al menos 6 caracteres</li>
                            <li><i class="bi bi-check-circle-fill"></i> Combina letras mayúsculas, minúsculas, números y símbolos</li>
                            <li><i class="bi bi-check-circle-fill"></i> No compartas tu contraseña con nadie</li>
                            <li><i class="bi bi-check-circle-fill"></i> Cambia tu contraseña periódicamente</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // Toggle mostrar/ocultar contraseña
            const toggleButtons = document.querySelectorAll('.toggle-password');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        this.classList.remove('bi-eye-slash');
                        this.classList.add('bi-eye');
                    } else {
                        input.type = 'password';
                        this.classList.remove('bi-eye');
                        this.classList.add('bi-eye-slash');
                    }
                });
            });

            // Validación formulario email
            const formEmail = document.getElementById('form_email');
            formEmail.addEventListener('submit', function(e) {
                const nuevoEmail = document.getElementById('nuevo_email').value;
                const emailActual = document.getElementById('email_actual').value;
                const password = document.getElementById('password_confirm_email').value;

                if (!nuevoEmail.trim() || !password.trim()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Todos los campos son obligatorios'
                    });
                    return;
                }

                if (nuevoEmail === emailActual) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: 'El nuevo correo es igual al actual'
                    });
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(nuevoEmail)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'El formato del correo electrónico no es válido'
                    });
                    return;
                }
            });

            // Validación formulario contraseña
            const formPassword = document.getElementById('form_password');
            const nuevaPassword = document.getElementById('nueva_password');
            const confirmarPassword = document.getElementById('confirmar_password');
            const passwordStrength = document.getElementById('password_strength');

            // Indicador de fortaleza de contraseña
            nuevaPassword.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let strengthText = '';
                let strengthClass = '';

                if (password.length >= 6) strength++;
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                if (/\d/.test(password)) strength++;
                if (/[^a-zA-Z\d]/.test(password)) strength++;

                if (password.length === 0) {
                    passwordStrength.textContent = '';
                } else if (strength <= 2) {
                    strengthText = 'Débil';
                    strengthClass = 'weak';
                } else if (strength <= 3) {
                    strengthText = 'Media';
                    strengthClass = 'medium';
                } else {
                    strengthText = 'Fuerte';
                    strengthClass = 'strong';
                }

                passwordStrength.textContent = strengthText ? `Fortaleza: ${strengthText}` : '';
                passwordStrength.className = 'password-strength ' + strengthClass;
            });

            formPassword.addEventListener('submit', function(e) {
                const passwordActual = document.getElementById('password_actual').value;
                const nueva = nuevaPassword.value;
                const confirmar = confirmarPassword.value;

                if (!passwordActual.trim() || !nueva.trim() || !confirmar.trim()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Todos los campos son obligatorios'
                    });
                    return;
                }

                if (nueva.length < 6) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La nueva contraseña debe tener al menos 6 caracteres'
                    });
                    return;
                }

                if (nueva !== confirmar) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Las contraseñas nuevas no coinciden'
                    });
                    return;
                }

                if (passwordActual === nueva) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: 'La nueva contraseña debe ser diferente a la actual'
                    });
                    return;
                }
            });
        });
    </script>
</body>
</html>