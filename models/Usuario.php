<?php
require_once __DIR__ . '/../data/Database.php';

class Usuario {
    private PDO $db;

    public function __construct() {
        $this->db = (new Database())->conectar();
    }

    public function crear(string $nombre, string $correo, string $contrasena): bool {
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            "INSERT INTO Usuarios (nombre, correo, contrasena, id_rol) VALUES (?, ?, ?, 2)"
        );
        return $stmt->execute([$nombre, $correo, $hash]);
    }

    public function buscarPorCorreo(string $correo): array|false {
        $stmt = $this->db->prepare("SELECT * FROM Usuarios WHERE correo = ? LIMIT 1");
        $stmt->execute([$correo]);
        return $stmt->fetch();
    }

    public function listar(): array {
        $stmt = $this->db->prepare(
            "SELECT id_usuario, nombre, correo, id_rol FROM Usuarios ORDER BY id_usuario DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}