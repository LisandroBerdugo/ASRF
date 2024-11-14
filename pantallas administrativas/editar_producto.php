<?php
require_once '../BLL/ProductoBLL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $precio = $_POST['precio'] ?? null;
    $stock = $_POST['stock'] ?? null;
    $id_marca = $_POST['id_marca'] ?? null;
    $id_color = $_POST['id_color'] ?? null;
    $id_microprocesador = $_POST['id_microprocesador'] ?? null;
    $id_ram = $_POST['id_ram'] ?? null;
    $id_tamano_pantalla = $_POST['id_tamano_pantalla'] ?? null;
    $id_idioma_teclado = $_POST['id_idioma_teclado'] ?? null;

    if (!$id || !$nombre || !$precio || !$stock || !$id_marca || !$id_color || !$id_microprocesador || !$id_ram || !$id_tamano_pantalla || !$id_idioma_teclado) {
        echo "error";
        exit();
    }

    $productoBLL = new ProductoBLL();
    $resultado = $productoBLL->editarProducto($id, $nombre, $precio, $stock);

    echo $resultado ? "success" : "error";
    exit();
}

echo "error";
exit();
