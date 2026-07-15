<?php
require_once __DIR__ . '/../data/Database.php';

class Comentario {
    private PDO $db;

    public function __construct() {
        $this->db = (new Database())->conectar();
    }

    public function crear(int $id_reporte, int $id_usuario, string $comentario): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO Comentarios (id_reporte, id_usuario, comentario) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$id_reporte, $id_usuario, $comentario]);
    }

    public function listarPorReporte(int $id_reporte): array {
        $stmt = $this->db->prepare(
            "SELECT c.*, u.nombre FROM Comentarios c 
             JOIN Usuarios u ON c.id_usuario = u.id_usuario 
             WHERE c.id_reporte = ? 
             ORDER BY c.fecha ASC"
        );
        $stmt->execute([$id_reporte]);
        return $stmt->fetchAll();
    }
}