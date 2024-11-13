<?php
require_once __DIR__ . '/../Conexion/conexion.php';
require_once __DIR__ . '/../EL/Producto.php';

class ProductoDAL {
    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->getConexion();
    }

    // Obtener microprocesadores
    public function obtenerMicroprocesadores() {
        try {
            $query = "SELECT id, microprocesador FROM microprocesador";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener microprocesadores: " . $e->getMessage();
            return [];
        }
    }

    // Obtener marcas
    public function obtenerMarcas() {
        try {
            $query = "SELECT id, marca FROM marca";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener marcas: " . $e->getMessage();
            return [];
        }
    }

    // Obtener modelos
    public function obtenerModelos() {
        try {
            $query = "SELECT id, modelo FROM modelo";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener modelos: " . $e->getMessage();
            return [];
        }
    }

    // Obtener modelos filtrados por marca
    public function obtenerModelosPorMarca($id_marca) {
        try {
            $query = "SELECT id, modelo FROM modelo WHERE id_marca = :id_marca";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id_marca', $id_marca, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener modelos por marca: " . $e->getMessage();
            return [];
        }
    }

    // Obtener colores
    public function obtenerColores() {
        try {
            $query = "SELECT id, color FROM color";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener colores: " . $e->getMessage();
            return [];
        }
    }

    // Obtener RAM
    public function obtenerRAM() {
        try {
            $query = "SELECT id, ram FROM ram";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener RAM: " . $e->getMessage();
            return [];
        }
    }

    // Obtener tamaños de pantalla
    public function obtenerTamanosPantalla() {
        try {
            $query = "SELECT id, tamano FROM tamano_pantalla";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener tamaños de pantalla: " . $e->getMessage();
            return [];
        }
    }

    // Obtener idiomas de teclado
    public function obtenerIdiomasTeclado() {
        try {
            $query = "SELECT id, idioma FROM idioma_teclado";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener idiomas de teclado: " . $e->getMessage();
            return [];
        }
    }

    // Obtener productos
    public function obtenerProductos() {
        try {
            $query = "SELECT * FROM productos";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $productos = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $productos[] = new Producto(
                    $row['id'],
                    $row['codigo_unico'],
                    $row['nombre'],
                    $row['imagen_url'],
                    $row['id_marca'],
                    null, // Modelo no necesario
                    $row['id_color'],
                    $row['id_microprocesador'],
                    $row['id_ram'],
                    $row['id_tamano_pantalla'],
                    $row['id_idioma_teclado'],
                    $row['precio'],
                    $row['stock']
                );
            }
            return $productos;
        } catch (PDOException $e) {
            echo "Error al obtener productos: " . $e->getMessage();
            return [];
        }
    }

    // Crear un producto
public function crearProducto($producto) {
    try {
        $query = "INSERT INTO productos (codigo_unico, nombre, imagen_url, id_marca, id_color, id_microprocesador, id_ram, id_tamano_pantalla, id_idioma_teclado, precio, stock) 
                  VALUES (:codigo_unico, :nombre, :imagen_url, :id_marca, :id_color, :id_microprocesador, :id_ram, :id_tamano_pantalla, :id_idioma_teclado, :precio, :stock)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':codigo_unico', $producto->getCodigoUnico());
        $stmt->bindValue(':nombre', $producto->getNombre());
        $stmt->bindValue(':imagen_url', $producto->getImagen()); // Cambiar aquí
        $stmt->bindValue(':id_marca', $producto->getIdMarca());
        $stmt->bindValue(':id_color', $producto->getIdColor());
        $stmt->bindValue(':id_microprocesador', $producto->getIdMicroprocesador());
        $stmt->bindValue(':id_ram', $producto->getIdRam());
        $stmt->bindValue(':id_tamano_pantalla', $producto->getIdTamanoPantalla());
        $stmt->bindValue(':id_idioma_teclado', $producto->getIdIdiomaTeclado());
        $stmt->bindValue(':precio', $producto->getPrecio());
        $stmt->bindValue(':stock', $producto->getStock());
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Error al crear producto: " . $e->getMessage();
        return false;
    }
}

}
