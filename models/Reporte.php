<?php
require_once __DIR__ . '/../data/Database.php';

class Reporte {
    private PDO $db;

    public function __construct() {
        $this->db = (new Database())->conectar();
    }

    public function crear(int $id_usuario, string $titulo, string $descripcion, string $ubicacion, int $id_categoria): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO Reportes (titulo, descripcion, ubicacion, id_usuario, id_categoria, id_estado)
             VALUES (?, ?, ?, ?, ?, 1)"
        );
        return $stmt->execute([$titulo, $descripcion, $ubicacion, $id_usuario, $id_categoria]);
    }

    public function listarPorUsuario(int $id_usuario): array {
        $stmt = $this->db->prepare(
            "SELECT r.id_reporte, r.titulo, r.descripcion, r.ubicacion, r.fecha_reporte,
                    c.nombre_categoria, e.nombre_estado
             FROM Reportes r
             JOIN Categorias c ON r.id_categoria = c.id_categoria
             JOIN Estados e ON r.id_estado = e.id_estado
             WHERE r.id_usuario = ?
             ORDER BY r.fecha_reporte DESC"
        );
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll();
    }

    public function listarTodos(): array {
    $stmt = $this->db->prepare(
        "SELECT r.id_reporte, r.titulo, r.descripcion, r.ubicacion, r.fecha_reporte,
                u.nombre AS nombre_usuario, u.id_usuario,
                c.nombre_categoria, e.nombre_estado, e.id_estado,
                i.ruta_imagen
         FROM Reportes r
         JOIN Usuarios u ON r.id_usuario = u.id_usuario
         JOIN Categorias c ON r.id_categoria = c.id_categoria
         JOIN Estados e ON r.id_estado = e.id_estado
         LEFT JOIN Imagenes i ON r.id_reporte = i.id_reporte
         ORDER BY r.fecha_reporte DESC"
    );
    $stmt->execute();
    return $stmt->fetchAll();
}



    public function obtenerCategorias(): array {
        $stmt = $this->db->prepare("SELECT * FROM categorias ORDER BY nombre_categoria");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function cambiarEstado(int $id_reporte, int $id_estado): bool {
        $stmt = $this->db->prepare(
            "UPDATE Reportes SET id_estado = ? WHERE id_reporte = ?"
        );
        return $stmt->execute([$id_estado, $id_reporte]);
    }

    public function eliminar(int $id_reporte): bool {
        $stmt = $this->db->prepare("DELETE FROM Imagenes WHERE id_reporte = ?");
        $stmt->execute([$id_reporte]);

        $stmt2 = $this->db->prepare("DELETE FROM Notificaciones WHERE id_usuario IN (SELECT id_usuario FROM Reportes WHERE id_reporte = ?)");
        $stmt2->execute([$id_reporte]);

        $stmt3 = $this->db->prepare("DELETE FROM Reportes WHERE id_reporte = ?");
        return $stmt3->execute([$id_reporte]);
    }

    public function obtenerEstados(): array {
        $stmt = $this->db->prepare("SELECT * FROM Estados");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function guardarImagen(int $id_reporte, string $ruta): bool {
    $stmt = $this->db->prepare(
        "INSERT INTO Imagenes (id_reporte, ruta_imagen) VALUES (?, ?)"
    );
    return $stmt->execute([$id_reporte, $ruta]);
}

    public function obtenerImagenPorReporte(int $id_reporte): ?string {
        $stmt = $this->db->prepare(
            "SELECT ruta_imagen FROM Imagenes WHERE id_reporte = ? LIMIT 1"
        );
        $stmt->execute([$id_reporte]);
        $result = $stmt->fetch();
        return $result ? $result['ruta_imagen'] : null;
    }
}