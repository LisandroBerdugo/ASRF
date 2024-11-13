<?php
require_once '../BLL/ProductoBLL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    if (!is_numeric($id)) {
        echo "error";
        exit();
    }

    $productoBLL = new ProductoBLL();
    $resultado = $productoBLL->eliminarProducto($id);

    echo $resultado ? "success" : "error";
    exit();
}

echo "error";
exit();
