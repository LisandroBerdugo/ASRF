<?php
session_start();
require_once '../BLL/usuarioBLL.php';

// Verifica que el usuario tenga permisos y que los datos sean válidos
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $usuarioBLL = new UsuarioBLL();
    $ids = explode(',', $_POST['id']); // Divide los IDs recibidos si son varios

    $success = true; // Variable para verificar si todo fue exitoso

    foreach ($ids as $id) {
        // Valida que el ID sea numérico para evitar inyecciones
        if (!is_numeric($id)) {
            $success = false;
            break;
        }

        // Intenta eliminar cada usuario, si falla marca error
        if (!$usuarioBLL->eliminarUsuario($id)) {
            $success = false;
            break;
        }
    }

    // Envía la respuesta al cliente
    if ($success) {
        echo "success";
    } else {
        echo "error";
    }
    exit();
}

// Si algo falla, devuelve error
echo "error";
exit();
