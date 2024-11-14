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
    $query = "
        SELECT 
            p.id,
            p.codigo_unico,
            p.nombre,
            m.marca AS marca,
            c.color AS color,
            mp.microprocesador AS microprocesador,
            r.ram AS ram,
            tp.tamano AS tamano_pantalla,
            it.idioma AS idioma_teclado,
            p.precio,
            p.stock,
            p.imagen_url
        FROM productos p
        LEFT JOIN marca m ON p.id_marca = m.id
        LEFT JOIN color c ON p.id_color = c.id
        LEFT JOIN microprocesador mp ON p.id_microprocesador = mp.id
        LEFT JOIN ram r ON p.id_ram = r.id
        LEFT JOIN tamano_pantalla tp ON p.id_tamano_pantalla = tp.id
        LEFT JOIN idioma_teclado it ON p.id_idioma_teclado = it.id
        WHERE 1=1
    ";

    // Aplicar filtros
    if (!empty($filtros['color'])) {
        $query .= " AND p.id_color = " . (int)$filtros['color'];
    }
    if (!empty($filtros['marca'])) {
        $query .= " AND p.id_marca = " . (int)$filtros['marca'];
    }
    if (!empty($filtros['tamano'])) {
        $query .= " AND p.id_tamano_pantalla = " . (int)$filtros['tamano'];
    }
    if (!empty($filtros['buscar'])) {
    $searchTerm = '%' . $filtros['buscar'] . '%';
    $query .= " AND (p.nombre LIKE :buscar OR p.codigo_unico LIKE :buscar)";
}

$stmt = $this->db->prepare($query);

if (!empty($filtros['buscar'])) {
    $stmt->bindValue(':buscar', $searchTerm, PDO::PARAM_STR);
}

// Ejecutar la consulta
$stmt->execute();
    $productos = [];

    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $productos[] = new VistaEL(
            $fila['id'],
            $fila['codigo_unico'],
            $fila['nombre'],
            $fila['marca'],
            $fila['color'],
            $fila['microprocesador'],
            $fila['ram'],
            $fila['tamano_pantalla'],
            $fila['idioma_teclado'],
            $fila['precio'],
            $fila['stock'],
            $fila['imagen_url']
        );
    }
    return $productos;
}

public function obtenerColores() {
    $query = "SELECT id, color FROM color";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function obtenerMarcas() {
    $query = "SELECT id, marca FROM marca";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function obtenerTamanosPantalla() {
    $query = "SELECT id, tamano FROM tamano_pantalla";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
