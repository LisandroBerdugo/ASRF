<?php
require_once '../BLL/usuarioBLL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    $usuarioBLL = new UsuarioBLL();
    $usuarioBLL->crearUsuario($nombre, $email, $password, $rol);

    // Simplemente terminar el script para que el popup se cierre
    exit();
}

