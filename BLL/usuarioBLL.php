<?php
require_once __DIR__ . '/../DAL/usuarioDAL.php'; // Asegúrate de que la ruta sea correcta
require_once __DIR__ . '/../EL/usuario.php';

class UsuarioBLL {
    private $usuarioDAL;

    public function __construct() {
        $this->usuarioDAL = new UsuarioDAL();
    }

    // Método para crear un nuevo usuario con la contraseña encriptada
    public function crearUsuario($nombre, $email, $password, $rol) {
        // Encripta la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $usuario = new Usuario(null, $nombre, $email, $passwordHash, $rol);
        
        return $this->usuarioDAL->crearUsuario($usuario);
    }

    // Método para obtener todos los usuarios
    public function obtenerUsuarios() {
        return $this->usuarioDAL->obtenerUsuarios();
    }

    // Método para obtener un usuario por su ID
    public function obtenerUsuarioPorId($id) {
        return $this->usuarioDAL->obtenerUsuarioPorId($id);
    }

    // Método para actualizar un usuario
    public function actualizarUsuario($id, $nombre, $email, $password, $rol) {
        // Encripta la contraseña antes de actualizarla
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $usuario = new Usuario($id, $nombre, $email, $passwordHash, $rol);
        
        return $this->usuarioDAL->actualizarUsuario($usuario);
    }

    // Método para eliminar un usuario por su ID
    public function eliminarUsuario($id) {
        return $this->usuarioDAL->eliminarUsuario($id);
    }
}
?>
