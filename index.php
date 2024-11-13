<?php
ob_start();
session_start();
require_once 'BLL/usuarioBLL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $usuarioBLL = new UsuarioBLL();
    $usuario = $usuarioBLL->obtenerUsuarioPorEmail($email);

    if ($usuario && $password === $usuario->getPassword()) {
        $_SESSION['usuario_id'] = $usuario->getId();
        $_SESSION['rol'] = $usuario->getRol();
        $_SESSION['nombre'] = $usuario->getNombre();
        header("Location: http://localhost/ASRF/pantallas administrativas/dashboard.php", true, 303);
        exit();
    } else {
        $error = "Credenciales incorrectas.";
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h1>BLUEBACK</h1>
            <p>Bienvenido a la plataforma de administración.</p>
        </div>
        <div class="right-section">
            <h2>Welcome</h2>
            <p>Login in your account to continue</p>
            <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
            <form action="index.php" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">LOG IN</button>
            </form>
        </div>
    </div>
</body>
</html>
