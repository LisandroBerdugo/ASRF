<?php
require_once '../BLL/usuarioBLL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : null;

    $usuarioBLL = new UsuarioBLL();

    // Llamamos al método para actualizar el usuario
    $resultado = $usuarioBLL->actualizarUsuario($id, $nombre, $email, $password, $rol);

    if ($resultado) {
        echo "success";
    } else {
        echo "error";
    }
    exit();
}

echo "error";
exit();
