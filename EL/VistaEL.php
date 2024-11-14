<?php
class VistaEL {
    private $id;
    private $nombre;
    private $precio;
    private $stock;
    private $imagen;

    public function __construct($id, $nombre, $precio, $stock, $imagen) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->imagen = $imagen;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getStock() {
        return $this->stock;
    }

    public function getImagen() {
        return '/ASRF/' . $this->imagen; // Ruta correcta
    }
}

?>
