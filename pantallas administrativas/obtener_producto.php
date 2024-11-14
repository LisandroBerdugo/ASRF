<?php
require_once '../BLL/ProductoBLL.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    if (!is_numeric($id)) {
        echo json_encode(["error" => "ID inválido"]);
        exit();
    }

    $productoBLL = new ProductoBLL();
    $productos = $productoBLL->obtenerProductos();

    // Buscar el producto con el ID proporcionado
    foreach ($productos as $producto) {
        if ($producto->getId() == $id) {
            echo json_encode([
                "id" => $producto->getId(),
                "nombre" => $producto->getNombre(),
                "precio" => $producto->getPrecio(),
                "stock" => $producto->getStock(),
                "id_marca" => $producto->getIdMarca(),
                "id_color" => $producto->getIdColor(),
                "id_microprocesador" => $producto->getIdMicroprocesador(),
                "id_ram" => $producto->getIdRam(),
                "id_tamano_pantalla" => $producto->getIdTamanoPantalla(),
                "id_idioma_teclado" => $producto->getIdIdiomaTeclado()
            ]);
            exit();
        }
    }

    // Si no se encuentra el producto
    echo json_encode(["error" => "Producto no encontrado"]);
    exit();
}

// Si no se cumple la solicitud
echo json_encode(["error" => "Solicitud inválida"]);
exit();
