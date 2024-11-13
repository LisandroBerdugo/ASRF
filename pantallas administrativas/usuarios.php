<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../BLL/usuarioBLL.php';

$usuarioBLL = new UsuarioBLL();
$usuarios = $usuarioBLL->obtenerUsuarios();

// Configuración de paginación
$usuarios_por_pagina = 10;
$total_usuarios = count($usuarios);
$total_paginas = ceil($total_usuarios / $usuarios_por_pagina);
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $usuarios_por_pagina;
$usuarios_pagina = array_slice($usuarios, $inicio, $usuarios_por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="usuarios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function mostrarPopup() {
            document.getElementById('popup').style.display = 'flex';
            document.getElementById('overlay').style.display = 'block';
        }

        function cerrarPopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        function mostrarPopupEditar(id, nombre, email, rol) {
            const popupEditar = document.getElementById('popup-editar');
            if (popupEditar) {
                popupEditar.style.display = 'flex';
                document.getElementById('editar-id').value = id;
                document.getElementById('editar-nombre').value = nombre;
                document.getElementById('editar-email').value = email;
                document.getElementById('editar-rol').value = rol;
                document.getElementById('overlay').style.display = 'block';
            }
        }

        function cerrarPopupEditar() {
            document.getElementById('popup-editar').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        function eliminarUsuario(id) {
            if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
                fetch('eliminar_usuario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + id
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        alert("Usuario eliminado exitosamente");
                        const fila = document.querySelector(`tr[data-id="${id}"]`);
                        if (fila) fila.remove();
                    } else {
                        alert("Error al eliminar el usuario");
                    }
                })
                .catch(error => {
                    console.error("Error en la solicitud:", error);
                    alert("Error en la solicitud.");
                });
            }
        }

        function eliminarUsuariosSeleccionados() {
            const checkboxes = document.querySelectorAll('.select-user:checked');
            const ids = Array.from(checkboxes).map(checkbox => checkbox.getAttribute('data-id'));

            if (ids.length === 0) {
                alert('No has seleccionado ningún usuario.');
                return;
            }

            if (confirm('¿Estás seguro de que deseas eliminar los usuarios seleccionados?')) {
                fetch('eliminar_usuario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + ids.join(',')
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        alert('Usuarios eliminados exitosamente');
                        ids.forEach(id => {
                            const fila = document.querySelector(`tr[data-id="${id}"]`);
                            if (fila) fila.remove();
                        });
                    } else {
                        alert('Error al eliminar los usuarios seleccionados.');
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud:', error);
                    alert('Error en la solicitud.');
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const formCrear = document.querySelector('#form-crear-usuario');
            formCrear.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(this);

                fetch('crear_usuario.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        cerrarPopup();
                        alert("Usuario agregado exitosamente");
                        location.reload();
                    } else {
                        alert("Error al agregar el usuario.");
                    }
                })
                .catch(error => {
                    console.error("Error al procesar la solicitud:", error);
                    alert("Error al guardar el usuario.");
                });
            });

            const formEditar = document.querySelector('#form-editar-usuario');
            formEditar.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(this);

                fetch('editar_usuario.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        cerrarPopupEditar();
                        alert("Usuario editado exitosamente");
                        location.reload();
                    } else {
                        alert("Error al editar el usuario.");
                    }
                })
                .catch(error => {
                    console.error("Error al procesar la solicitud:", error);
                    alert("Error al editar el usuario.");
                });
            });
        });
    </script>
</head>
<body>
    <div id="overlay"></div>

    <div class="toolbar">
        <button class="btn add-new" onclick="mostrarPopup()"><i class="fas fa-plus"></i> Agregar Nuevo</button>
        <div class="search-bar">
            <input type="text" placeholder="Buscar nombre..." />
            <button><i class="fas fa-search"></i></button>
        </div>
    </div>

    <section class="table-section">
        <table class="user-table">
            <thead>
                <tr>
                    <th><input type="checkbox" onclick="toggleSelectAll(this)" /></th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios_pagina as $usuario): ?>
                    <tr data-id="<?php echo $usuario->getId(); ?>">
                        <td><input type="checkbox" class="select-user" data-id="<?php echo $usuario->getId(); ?>" /></td>
                        <td class="nombre-col"><?php echo htmlspecialchars($usuario->getNombre()); ?></td>
                        <td class="email-col"><?php echo htmlspecialchars($usuario->getEmail()); ?></td>
                        <td class="rol-col"><?php echo htmlspecialchars($usuario->getRol()); ?></td>
                        <td>
                            <button class="btn-action edit" onclick="mostrarPopupEditar('<?php echo $usuario->getId(); ?>', '<?php echo htmlspecialchars($usuario->getNombre()); ?>', '<?php echo htmlspecialchars($usuario->getEmail()); ?>', '<?php echo htmlspecialchars($usuario->getRol()); ?>')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action delete" onclick="eliminarUsuario(<?php echo $usuario->getId(); ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn delete-selected" onclick="eliminarUsuariosSeleccionados()">
            <i class="fas fa-trash"></i> Eliminar Seleccionados
        </button>
    </section>

<div class="pagination">
    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
        <a href="?pagina=<?php echo $i; ?>" class="page-btn <?php if ($i === $pagina_actual) echo 'active'; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>


    <div id="popup">
        <div class="popup-content">
            <span class="close-btn" onclick="cerrarPopup()">&times;</span>
            <h2>Agregar Usuario</h2>
            <form id="form-crear-usuario">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="admin">Admin</option>
                    <option value="vendedor">Vendedor</option>
                </select>
                <button type="submit">Guardar</button>
            </form>
        </div>
    </div>

    <div id="popup-editar">
        <div class="popup-content">
            <span class="close-btn" onclick="cerrarPopupEditar()">&times;</span>
            <h2>Editar Usuario</h2>
            <form id="form-editar-usuario">
                <input type="hidden" id="editar-id" name="id">
                <label for="editar-nombre">Nombre:</label>
                <input type="text" id="editar-nombre" name="nombre" required>
                <label for="editar-email">Email:</label>
                <input type="email" id="editar-email" name="email" required>
                <label for="editar-password">Nueva Contraseña (opcional):</label>
                <input type="password" id="editar-password" name="password">
                <label for="editar-rol">Rol:</label>
                <select id="editar-rol" name="rol" required>
                    <option value="admin">Admin</option>
                    <option value="vendedor">Vendedor</option>
                </select>
                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>
</body>
</html>
