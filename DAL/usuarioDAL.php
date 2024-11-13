<?php
require_once __DIR__ . '/../Conexion/conexion.php';
require_once __DIR__ . '/../EL/usuario.php';

class UsuarioDAL {
    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->getConexion();
    }

    public function crearUsuario($usuario) {
        try {
            $query = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':nombre', $usuario->getNombre());
            $stmt->bindValue(':email', $usuario->getEmail());
            $stmt->bindValue(':password', $usuario->getPassword());
            $stmt->bindValue(':rol', $usuario->getRol());
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al crear usuario: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerUsuarios() {
        try {
            $query = "SELECT * FROM usuarios";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $usuarios = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $usuarios[] = new Usuario(
                    $row['id'],
                    $row['nombre'],
                    $row['email'],
                    $row['password'],
                    $row['rol']
                );
            }
            return $usuarios;
        } catch (PDOException $e) {
            echo "Error al obtener usuarios: " . $e->getMessage();
            return [];
        }
    }

    public function obtenerUsuarioPorId($id) {
        try {
            $query = "SELECT * FROM usuarios WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new Usuario(
                    $row['id'],
                    $row['nombre'],
                    $row['email'],
                    $row['password'],
                    $row['rol']
                );
            }
            return null;
        } catch (PDOException $e) {
            echo "Error al obtener usuario: " . $e->getMessage();
            return null;
        }
    }

    public function actualizarUsuario($usuario) {
        try {
            $query = "UPDATE usuarios SET nombre = :nombre, email = :email, password = :password, rol = :rol WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':nombre', $usuario->getNombre());
            $stmt->bindValue(':email', $usuario->getEmail());
            $stmt->bindValue(':password', $usuario->getPassword());
            $stmt->bindValue(':rol', $usuario->getRol());
            $stmt->bindValue(':id', $usuario->getId());
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al actualizar usuario: " . $e->getMessage();
            return false;
        }
    }

    public function eliminarUsuario($id) {
        try {
            $query = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al eliminar usuario: " . $e->getMessage();
            return false;
        }
    }


    // Nuevo método para obtener un usuario por email
    public function obtenerUsuarioPorEmail($email) {
        try {
            $query = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new Usuario(
                    $row['id'],
                    $row['nombre'],
                    $row['email'],
                    $row['password'],
                    $row['rol']
                );
            }
            return null;
        } catch (PDOException $e) {
            echo "Error al obtener usuario por email: " . $e->getMessage();
            return null;
        }
    }
}
