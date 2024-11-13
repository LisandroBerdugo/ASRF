<?php
require_once '../BLL/ProductoBLL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $precio = $_POST['precio'] ?? null;
    $stock = $_POST['stock'] ?? null;

    $imagen_url = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = uniqid() . '_' . $_FILES['imagen']['name'];
        $rutaDestino = __DIR__ . '/../uploads/' . $nombreArchivo;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imagen_url = 'uploads/' . $nombreArchivo;
        }
    }

    if (!$id || !$nombre || !$precio || !$stock || !is_numeric($id)) {
        echo "error";
        exit();
    }

    $productoBLL = new ProductoBLL();
    $resultado = $productoBLL->editarProducto($id, $nombre, $precio, $stock, $imagen_url);

    echo $resultado ? "success" : "error";
    exit();
}

echo "error";
exit();
