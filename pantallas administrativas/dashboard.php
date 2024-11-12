<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: ../index.php");
    exit();
}

// Datos del usuario
$nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "Usuario";
$rolUsuario = $_SESSION['rol'] === 'admin' ? 'Administrador' : 'Vendedor';

// Pantalla a mostrar, por defecto el dashboard principal
$pagina = isset($_GET['page']) ? $_GET['page'] : 'inicio';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <!-- Enlace a Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <!-- Barra lateral -->
        <aside class="sidebar">
            <div class="logo">
                <h2>Admin Panel</h2>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="dashboard.php?page=inicio"><i class="fas fa-home"></i> Inicio</a></li>
                    <li><a href="dashboard.php?page=usuarios"><i class="fas fa-users"></i> Configuración Usuarios</a></li>
                    <li><a href="dashboard.php?page=productos"><i class="fas fa-box-open"></i> Productos</a></li>
                </ul>
            </nav>
            <div class="footer">
                <p>Admin Dashboard</p>
            </div>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <header class="header">
                <div class="header-title">
                    <h1>Dashboard</h1>
                </div>
                <div class="user-info">
                    <p><?php echo htmlspecialchars($nombreUsuario); ?> <span><?php echo htmlspecialchars($rolUsuario); ?></span></p>
                    <!-- Botón de Cerrar Sesión -->
                    <form action="../logout.php" method="post" style="display:inline;">
                        <button type="submit" class="logout-btn">Cerrar Sesión</button>
                    </form>
                </div>
            </header>

            <!-- Sección de contenido dinámico -->
            <section class="content">
                <?php
                // Incluye la página seleccionada
                switch ($pagina) {
                    case 'usuarios':
                        include 'usuarios.php';
                        break;
                    case 'productos':
                        include 'productos.php';
                        break;
                    case 'inicio':
                    default:
                        echo "<h2>BIENVENIDOS AL CENTRO DE ADMINISTRACIÓN</h2>";
                        echo "<p>Usa el menú de la izquierda para gestionar el sistema.</p>";
                        break;
                }
                ?>
            </section>
        </main>
    </div>
</body>
</html>
