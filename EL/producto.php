<?php
class Producto {
    private $id;
    private $codigo_unico;
    private $nombre;
    private $imagen; // Ruta de la imagen
    private $id_marca;
    private $id_color;
    private $id_microprocesador;
    private $id_ram;
    private $id_tamano_pantalla;
    private $id_idioma_teclado;
    private $precio;
    private $stock;

    public function __construct(
        $id = null,
        $codigo_unico = null,
        $nombre = null,
        $imagen = null,
        $id_marca = null,
        $id_color = null,
        $id_microprocesador = null,
        $id_ram = null,
        $id_tamano_pantalla = null,
        $id_idioma_teclado = null,
        $precio = null,
        $stock = null
    ) {
        $this->id = $id;
        $this->codigo_unico = $codigo_unico;
        $this->nombre = $nombre;
        $this->imagen = $imagen;
        $this->id_marca = $id_marca;
        $this->id_color = $id_color;
        $this->id_microprocesador = $id_microprocesador;
        $this->id_ram = $id_ram;
        $this->id_tamano_pantalla = $id_tamano_pantalla;
        $this->id_idioma_teclado = $id_idioma_teclado;
        $this->precio = $precio;
        $this->stock = $stock;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getCodigoUnico() {
        return $this->codigo_unico;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getImagen() {
        return $this->imagen; // Este es el campo que debería coincidir con tu lógica
    }

    public function getIdMarca() {
        return $this->id_marca;
    }

    public function getIdColor() {
        return $this->id_color;
    }

    public function getIdMicroprocesador() {
        return $this->id_microprocesador;
    }

    public function getIdRam() {
        return $this->id_ram;
    }

    public function getIdTamanoPantalla() {
        return $this->id_tamano_pantalla;
    }

    public function getIdIdiomaTeclado() {
        return $this->id_idioma_teclado;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getStock() {
        return $this->stock;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setCodigoUnico($codigo_unico) {
        $this->codigo_unico = $codigo_unico;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen; // Ruta de imagen asignada correctamente
    }

    public function setIdMarca($id_marca) {
        $this->id_marca = $id_marca;
    }

    public function setIdColor($id_color) {
        $this->id_color = $id_color;
    }

    public function setIdMicroprocesador($id_microprocesador) {
        $this->id_microprocesador = $id_microprocesador;
    }

    public function setIdRam($id_ram) {
        $this->id_ram = $id_ram;
    }

    public function setIdTamanoPantalla($id_tamano_pantalla) {
        $this->id_tamano_pantalla = $id_tamano_pantalla;
    }

    public function setIdIdiomaTeclado($id_idioma_teclado) {
        $this->id_idioma_teclado = $id_idioma_teclado;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }
}
