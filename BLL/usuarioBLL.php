<?php
require_once __DIR__ . '/../DAL/usuarioDAL.php';
require_once __DIR__ . '/../EL/usuario.php';

class UsuarioBLL {
    private $usuarioDAL;

    public function __construct() {
        $this->usuarioDAL = new UsuarioDAL();
    }

    public function crearUsuario($nombre, $email, $password, $rol) {
        $usuario = new Usuario(null, $nombre, $email, $password, $rol);
        return $this->usuarioDAL->crearUsuario($usuario);
    }

    public function obtenerUsuarios() {
        return $this->usuarioDAL->obtenerUsuarios();
    }

    public function obtenerUsuarioPorId($id) {
        return $this->usuarioDAL->obtenerUsuarioPorId($id);
    }

    public function actualizarUsuario($id, $nombre, $email, $password, $rol) {
        $usuario = new Usuario($id, $nombre, $email, $password, $rol);
        return $this->usuarioDAL->actualizarUsuario($usuario);
    }


    public function eliminarUsuario($id) {
        return $this->usuarioDAL->eliminarUsuario($id);
    }

    // Nuevo método para obtener usuario por email
    public function obtenerUsuarioPorEmail($email) {
        return $this->usuarioDAL->obtenerUsuarioPorEmail($email);
    }
}
