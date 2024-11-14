<?php
require_once '../BLL/ProductoBLL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    
    $productoBLL = new ProductoBLL();
    //$producto = $productoBLL->obtener_producto($id);
    // crear funcion de eliminar imagen antes de borrar de la base el registro 
    
    $resultado = $productoBLL->eliminarProducto($id);

    if ($resultado) {
        echo json_encode(['status' => 'success', 'message' => 'El producto se eliminó correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar |el producto. '. $resultado]);
    }
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
exit();
