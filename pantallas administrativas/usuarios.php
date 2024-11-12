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
                        location.reload();
                    } else {
                        alert("Error al eliminar el usuario");
                    }
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
                    body: 'id=' + ids
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        alert("Usuarios eliminados exitosamente");
                        location.reload();
                    } else {
                        alert("Error al eliminar los usuarios");
                    }
                });
            }
        }
    </script>
    <style>
        /* Estilos para el popup */
        #popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        #popup .popup-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            position: relative;
            text-align: center;
        }

        #popup .popup-content h2 {
            margin-top: 0;
        }

        #popup .popup-content .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
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
                    <tr>
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
            <a href="?page=usuarios&pagina=<?php echo $i; ?>" class="page-btn <?php if ($i === $pagina_actual) echo 'active'; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>

    <!-- Popup de Agregar Usuario -->
    <div id="popup">
        <div class="popup-content">
            <span class="close-btn" onclick="cerrarPopup()">&times;</span>
            <h2>Agregar Usuario</h2>
            <form action="crear_usuario.php" method="POST">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
                <label>Email:</label>
                <input type="email" name="email" required>
                <label>Password:</label>
                <input type="password" name="password" required>
                <label>Rol:</label>
                <select name="rol">
                    <option value="admin">Admin</option>
                    <option value="vendedor">Vendedor</option>
                </select>
                <button type="submit" class="btn add-new">Guardar</button>
            </form>
        </div>
    </div>
</body>
</html>
