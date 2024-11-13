<?php
require_once __DIR__ . '/../DAL/ProductoDAL.php';
require_once __DIR__ . '/../EL/Producto.php';

class ProductoBLL {
    private $productoDAL;

    public function __construct() {
        $this->productoDAL = new ProductoDAL();
    }

public function crearProducto($codigo_unico, $nombre, $imagen_url, $id_marca, $id_color, $id_microprocesador, $id_ram, $id_tamano_pantalla, $id_idioma_teclado, $precio, $stock) {
    $producto = new Producto(null, $codigo_unico, $nombre, $imagen_url, $id_marca, null, $id_color, $id_microprocesador, $id_ram, $id_tamano_pantalla, $id_idioma_teclado, $precio, $stock);
    return $this->productoDAL->crearProducto($producto);
}


    public function obtenerProductos() {
        return $this->productoDAL->obtenerProductos();
    }

    public function obtenerMicroprocesadores() {
        return $this->productoDAL->obtenerMicroprocesadores();
    }

    public function obtenerMarcas() {
        return $this->productoDAL->obtenerMarcas();
    }

    public function obtenerModelos() {
        return $this->productoDAL->obtenerModelos();
    }

    public function obtenerColores() {
        return $this->productoDAL->obtenerColores();
    }

    public function obtenerRAM() {
        return $this->productoDAL->obtenerRAM();
    }

    public function obtenerTamanosPantalla() {
        return $this->productoDAL->obtenerTamanosPantalla();
    }

    public function obtenerIdiomasTeclado() {
        return $this->productoDAL->obtenerIdiomasTeclado();
    }

    public function eliminarProducto($id) {
        return $this->productoDAL->eliminarProducto($id);
    }

    public function editarProducto($id, $nombre, $precio, $stock) {
        $producto = new Producto(
            $id, null, $nombre, null, null, null, null, null, null, null, null, $precio, $stock
        );
        return $this->productoDAL->actualizarProducto($producto);
    }
    
    public function obtenerModelosPorMarca($id_marca) {
    return $this->productoDAL->obtenerModelosPorMarca($id_marca);
}

}
?>
