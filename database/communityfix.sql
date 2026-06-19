CREATE DATABASE IF NOT EXISTS communityfix;
USE communityfix;

CREATE TABLE Roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL
);

CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    id_rol INT NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES Roles(id_rol)
);

CREATE TABLE Categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50) NOT NULL
);

CREATE TABLE Estados (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre_estado VARCHAR(50) NOT NULL
);

CREATE TABLE Reportes (
    id_reporte INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_reporte DATETIME DEFAULT CURRENT_TIMESTAMP,
    ubicacion VARCHAR(255) NOT NULL,
    id_usuario INT NOT NULL,
    id_categoria INT NOT NULL,
    id_estado INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_categoria) REFERENCES Categorias(id_categoria),
    FOREIGN KEY (id_estado) REFERENCES Estados(id_estado)
);

CREATE TABLE Imagenes (
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,
    ruta_imagen VARCHAR(255) NOT NULL,
    id_reporte INT NOT NULL,
    FOREIGN KEY (id_reporte) REFERENCES Reportes(id_reporte)
);

CREATE TABLE Notificaciones (
    id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
    mensaje VARCHAR(255) NOT NULL,
    fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    leida TINYINT(1) DEFAULT 0,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Historial_Estados (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_reporte INT NOT NULL,
    id_estado INT NOT NULL,
    fecha_cambio DATETIME DEFAULT CURRENT_TIMESTAMP,
    comentario VARCHAR(255) NULL,
    FOREIGN KEY (id_reporte) REFERENCES Reportes(id_reporte),
    FOREIGN KEY (id_estado) REFERENCES Estados(id_estado)
);

-- Datos iniciales
INSERT INTO Roles (nombre_rol) VALUES ('administrador'), ('ciudadano');
INSERT INTO Estados (nombre_estado) VALUES ('pendiente'), ('en proceso'), ('resuelto');
INSERT INTO Categorias (nombre_categoria) VALUES ('Calles dañadas'), ('Basura acumulada'), ('Alumbrado público'), ('Fugas de agua'), ('Alcantarillas'), ('Señales de tránsito'), ('Seguridad');