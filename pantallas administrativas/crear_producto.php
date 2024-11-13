<?php
require_once '../BLL/ProductoBLL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'] ?? null;
        $precio = $_POST['precio'] ?? null;
        $stock = $_POST['stock'] ?? null;
        $id_marca = $_POST['id_marca'] ?? null;
        $id_color = $_POST['id_color'] ?? null;
        $id_microprocesador = $_POST['id_microprocesador'] ?? null;
        $id_ram = $_POST['id_ram'] ?? null;
        $id_tamano_pantalla = $_POST['id_tamano_pantalla'] ?? null;
        $id_idioma_teclado = $_POST['id_idioma_teclado'] ?? null;
        $rutaImagen = null;

        // Validar datos obligatorios
        if (!$nombre || !$precio || !$stock || !$id_marca || !$id_color || !$id_microprocesador || !$id_ram || !$id_tamano_pantalla || !$id_idioma_teclado) {
            die("Error: Datos incompletos.");
        }

        // Procesar la imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = uniqid() . "_" . basename($_FILES['imagen']['name']);
            $rutaDestino = __DIR__ . '/../uploads/' . $nombreArchivo;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $rutaImagen = 'uploads/' . $nombreArchivo; // Ruta relativa
            } else {
                die("Error: No se pudo mover la imagen al directorio de uploads.");
            }
        } else {
            die("Error: No se proporcionó una imagen válida.");
        }

        // Crear producto en la base de datos
        $productoBLL = new ProductoBLL();
        $codigo_unico = uniqid(); // Generar un código único para el producto
        $resultado = $productoBLL->crearProducto(
            $codigo_unico,
            $nombre,
            $rutaImagen,
            $id_marca,
            null, // Modelo se deja como NULL
            $id_color,
            $id_microprocesador,
            $id_ram,
            $id_tamano_pantalla,
            $id_idioma_teclado,
            $precio,
            $stock
        );

        echo $resultado ? "success" : "Error al guardar el producto en la base de datos.";
    } catch (Exception $e) {
        echo "Excepción: " . $e->getMessage();
    }
    exit();
}
