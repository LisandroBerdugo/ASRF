<?php
require_once '../BLL/ProductoBLL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_marca = $_POST['id_marca'] ?? null;

    if ($id_marca) {
        $productoBLL = new ProductoBLL();
        $modelos = $productoBLL->obtenerModelosPorMarca($id_marca);
        echo json_encode($modelos);
    } else {
        echo json_encode([]);
    }
}
?>
