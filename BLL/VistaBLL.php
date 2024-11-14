<?php
require_once '../EL/VistaEL.php';
require_once '../conexion/conexion.php'; // Asegúrate de que este archivo existe y es accesible

class VistaBLL {
    private $db;

    public function __construct() {
        $conexion = new Conexion(); // Usa tu clase de conexión
        $this->db = $conexion->getConexion(); // Cambiado a getConexion()
    }

    public function obtenerProductos($filtros = []) {
        $query = "SELECT id, nombre, precio, stock, imagen_url FROM productos WHERE 1=1";

        // Aplicar filtros
        if (!empty($filtros['color'])) {
            $query .= " AND id_color = :color";
        }
        if (!empty($filtros['marca'])) {
            $query .= " AND id_marca = :marca";
        }
        if (!empty($filtros['tamano'])) {
            $query .= " AND id_tamano_pantalla = :tamano";
        }
        if (!empty($filtros['buscar'])) {
            $query .= " AND (nombre LIKE :buscar OR id LIKE :buscar)";
        }

        $stmt = $this->db->prepare($query);

        // Vincular parámetros
        if (!empty($filtros['color'])) {
            $stmt->bindValue(':color', $filtros['color'], PDO::PARAM_INT);
        }
        if (!empty($filtros['marca'])) {
            $stmt->bindValue(':marca', $filtros['marca'], PDO::PARAM_INT);
        }
        if (!empty($filtros['tamano'])) {
            $stmt->bindValue(':tamano', $filtros['tamano'], PDO::PARAM_INT);
        }
        if (!empty($filtros['buscar'])) {
            $searchValue = '%' . $filtros['buscar'] . '%';
            $stmt->bindValue(':buscar', $searchValue, PDO::PARAM_STR);
        }

        $stmt->execute();
        $productos = [];

        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productos[] = new VistaEL(
                $fila['id'],
                $fila['nombre'],
                $fila['precio'],
                $fila['stock'],
                $fila['imagen_url']
            );
        }

        return $productos;
    }
}
