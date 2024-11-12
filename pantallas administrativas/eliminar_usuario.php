<?php
session_start();
require_once '../BLL/usuarioBLL.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    $usuarioBLL = new UsuarioBLL();
    $ids = explode(',', $_POST['ids']); // Dividir la cadena de IDs

    $success = true;
    foreach ($ids as $id) {
        if (!$usuarioBLL->eliminarUsuario($id)) {
            $success = false;
            break;
        }
    }

    echo $success ? "success" : "error";
    exit();
}

echo "error";
exit();
?>
