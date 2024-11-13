<?php
require_once '../BLL/usuarioBLL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $rol = $_POST['rol'] ?? null;

    if (!$nombre || !$email || !$password || !$rol) {
        echo "error"; // Mensaje de error si faltan datos
        exit();
    }

    $usuarioBLL = new UsuarioBLL();
    $resultado = $usuarioBLL->crearUsuario($nombre, $email, $password, $rol);

    if ($resultado) {
        echo "success"; // Mensaje exitoso
    } else {
        echo "error"; // Error al guardar en la base de datos
    }
    exit();
}

echo "error"; // Error si el método no es POST
exit();
