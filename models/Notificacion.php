<?php
require_once __DIR__ . '/../data/Database.php';

class Notificacion {
    private PDO $db;

    public function __construct() {
        $this->db = (new Database())->conectar();
    }

    public function crear(int $id_usuario, string $mensaje, ?int $id_reporte = null): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO Notificaciones (id_usuario, mensaje, id_reporte) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$id_usuario, $mensaje, $id_reporte]);
    }

    public function listarPorUsuario(int $id_usuario): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM Notificaciones 
             WHERE id_usuario = ? 
             ORDER BY fecha_envio DESC 
             LIMIT 10"
        );
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll();
    }

    public function marcarLeida(int $id_notificacion): bool {
        $stmt = $this->db->prepare(
            "UPDATE Notificaciones SET leida = 1 WHERE id_notificacion = ?"
        );
        return $stmt->execute([$id_notificacion]);
    }

    public function contarNoLeidas(int $id_usuario): int {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM Notificaciones WHERE id_usuario = ? AND leida = 0"
        );
        $stmt->execute([$id_usuario]);
        return (int)$stmt->fetchColumn();
    }

    public function marcarTodasLeidas(int $id_usuario): bool {
        $stmt = $this->db->prepare(
            "UPDATE Notificaciones SET leida = 1 WHERE id_usuario = ?"
        );
        return $stmt->execute([$id_usuario]);
    }
}
?>