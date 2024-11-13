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
        }

        function cerrarPopup() {
            document.getElementById('popup').style.display = 'none';
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
            const checkboxes = document.querySelectorAll('.user-table tbody input[type="checkbox"]:checked');
            if (checkboxes.length === 0) {
                alert("Selecciona al menos un usuario para eliminar.");
                return;
            }

            if (confirm("¿Estás seguro de que deseas eliminar los usuarios seleccionados?")) {
                const ids = Array.from(checkboxes).map(cb => cb.getAttribute('data-id')).join(',');

                fetch('eliminar_usuario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(ids)
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        alert("Usuarios eliminados exitosamente");
                        checkboxes.forEach(cb => {
                            const fila = cb.closest('tr');
                            if (fila) fila.remove();
                        });
                    } else {
                        alert("Error al eliminar los usuarios");
                    }
                })
                .catch(error => {
                    console.error("Error en la solicitud:", error);
                    alert("Ocurrió un error al intentar eliminar los usuarios.");
                });
            }
        }

        // Cierra el popup después de guardar un usuario
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('#form-crear-usuario');
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Evita recargar la página
                const formData = new FormData(this);

                // Enviar el formulario al servidor
                fetch('crear_usuario.php', {
                    method: 'POST',
                    body: formData
                })
                .then(() => {
                    cerrarPopup(); // Cierra el popup
                    alert("Usuario agregado exitosamente");

                    // Opcional: Limpia el formulario después de enviar
                    form.reset();
                })
                .catch(error => {
                    console.error("Error al procesar la solicitud:", error);
                    alert("Error al guardar el usuario.");
                });
            });
        });
    </script>
</head>
<body>
    <div class="toolbar">
        <button class="btn add-new" onclick="mostrarPopup()"><i class="fas fa-plus"></i> Agregar Nuevo</button>
        <div class="search-bar">
            <input type="text" placeholder="Buscar nombre..." />
            <button><i class="fas fa-search"></i></button>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
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
                        <td><input type="checkbox" data-id="<?php echo $usuario->getId(); ?>" /></td>
                        <td><?php echo htmlspecialchars($usuario->getNombre()); ?></td>
                        <td><?php echo htmlspecialchars($usuario->getEmail()); ?></td>
                        <td><?php echo htmlspecialchars($usuario->getRol()); ?></td>
                        <td>
                            <button class="btn-action view"><i class="fas fa-eye"></i></button>
                            <button class="btn-action edit"><i class="fas fa-edit"></i></button>
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

    <!-- Paginación -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>" class="page-btn <?php if ($i === $pagina_actual) echo 'active'; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>

    <!-- Popup de Agregar Usuario -->
    <div id="popup">
        <div class="popup-content">
            <span class="close-btn" onclick="cerrarPopup()">&times;</span>
            <h2>Agregar Usuario</h2>
            <form id="form-crear-usuario" method="POST" action="crear_usuario.php">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ingresa el nombre" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Ingresa el email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Ingresa el password" required>

                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="admin">Admin</option>
                    <option value="vendedor">Vendedor</option>
                </select>

                <button type="submit" class="btn add-new">Guardar</button>
            </form>
        </div>
    </div>
</body>
</html>
