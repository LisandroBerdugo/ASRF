<?php
class VistaEL {
    private $id;
    private $codigoUnico;
    private $nombre;
    private $marca;
    private $color;
    private $microprocesador;
    private $ram;
    private $tamanoPantalla;
    private $idiomaTeclado;
    private $precio;
    private $stock;
    private $imagen;

    public function __construct($id, $codigoUnico, $nombre, $marca, $color, $microprocesador, $ram, $tamanoPantalla, $idiomaTeclado, $precio, $stock, $imagen) {
        $this->id = $id;
        $this->codigoUnico = $codigoUnico;
        $this->nombre = $nombre;
        $this->marca = $marca;
        $this->color = $color;
        $this->microprocesador = $microprocesador;
        $this->ram = $ram;
        $this->tamanoPantalla = $tamanoPantalla;
        $this->idiomaTeclado = $idiomaTeclado;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->imagen = $imagen;
    }

    public function getId() { return $this->id; }
    public function getCodigoUnico() { return $this->codigoUnico; }
    public function getNombre() { return $this->nombre; }
    public function getMarca() { return $this->marca; }
    public function getColor() { return $this->color; }
    public function getMicroprocesador() { return $this->microprocesador; }
    public function getRam() { return $this->ram; }
    public function getTamanoPantalla() { return $this->tamanoPantalla; }
    public function getIdiomaTeclado() { return $this->idiomaTeclado; }
    public function getPrecio() { return $this->precio; }
    public function getStock() { return $this->stock; }
    public function getImagen() {
        return '/ASRF/' . $this->imagen; // Ruta correcta
    }
}

?>
