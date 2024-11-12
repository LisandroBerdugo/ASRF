<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../BLL/UsuarioBLL.php';
$usuarioBLL = new UsuarioBLL();

if (isset($_GET['id'])) {
    $usuario = $usuarioBLL->obtenerUsuarioPorId($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    $usuarioBLL->actualizarUsuario($id, $nombre, $email, $password, $rol);

    header("Location: usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
</head>
<body>
    <h2>Editar Usuario</h2>
    <form action="editar_usuario.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $usuario->getId(); ?>">

        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario->getNombre()); ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($usuario->getEmail()); ?>" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Rol:</label><br>
        <select name="rol" required>
            <option value="admin" <?php echo $usuario->getRol() == 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="vendedor" <?php echo $usuario->getRol() == 'vendedor' ? 'selected' : ''; ?>>Vendedor</option>
        </select><br><br>

        <button type="submit">Actualizar Usuario</button>
    </form>
</body>
</html>
