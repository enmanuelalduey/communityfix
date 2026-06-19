<?php
require_once '../data/Database.php';

class Usuario {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar();
    }

    public function crear($nombre, $correo, $contrasena) {
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            "INSERT INTO Usuarios (nombre, correo, contrasena, id_rol) VALUES (?, ?, ?, 2)"
        );
        return $stmt->execute([$nombre, $correo, $hash]);
    }

    public function buscarPorCorreo($correo) {
        $stmt = $this->db->prepare(
            "SELECT * FROM Usuarios WHERE correo = ?"
        );
        $stmt->execute([$correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listar() {
        $stmt = $this->db->prepare(
            "SELECT id_usuario, nombre, correo, id_rol FROM Usuarios"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>