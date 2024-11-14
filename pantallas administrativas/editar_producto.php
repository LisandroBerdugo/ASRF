<?php
require_once '../BLL/ProductoBLL.php';

header('Content-Type: application/json');

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

    if (
        !$id || !$nombre || $precio === null || $stock === null ||
        !$id_marca || !$id_color || !$id_microprocesador ||
        !$id_ram || !$id_tamano_pantalla || !$id_idioma_teclado
    ) {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        exit();
    }

    $productoBLL = new ProductoBLL();

    $producto = new Producto(
        $id,
        null, // Código único no se actualiza
        $nombre,
        null, // Imagen no se actualiza aquí
        $id_marca,
        $id_color,
        $id_microprocesador,
        $id_ram,
        $id_tamano_pantalla,
        $id_idioma_teclado,
        $precio,
        $stock
    );

    $resultado = $productoBLL->editarProductoCompleto($producto);

    if ($resultado) {
        echo json_encode(['status' => 'success', 'message' => 'El producto se actualizó correctamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el producto']);
    }
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Solicitud inválida']);
exit();
