<?php
define('ROOT_PATH', dirname(__DIR__) . '/'); // Definimos la ruta raíz del proyecto

class Conexion {
    private $host = "bk9r5ce4zodtopicbcmr-mysql.services.clever-cloud.com";
    private $dbname = "bk9r5ce4zodtopicbcmr";
    private $username = "u5vychjpedxfm6rk";
    private $password = "Ahyj1u2WY0klWxIY9leL";
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }

    public function getConexion() {
        return $this->conn;
    }
}
?>
