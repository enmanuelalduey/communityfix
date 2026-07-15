# communityfix

## Descripción

CommunityFix es una aplicación web diseñada para facilitar el reporte de problemas comunitarios por parte de los ciudadanos.

Los usuarios pueden registrar incidencias como basura acumulada, alumbrado público dañado, fugas de agua, calles dañadas, alcantarillas, señales de tránsito o problemas de seguridad, permitiendo a los administradores dar seguimiento y gestionar el estado de cada reporte hasta su resolución.

---

## Objetivo

Desarrollar una plataforma que permita a los ciudadanos reportar problemas comunitarios de manera rápida y organizada, mejorando la comunicación con las autoridades responsables.

---

# Estado del proyecto

El proyecto se encuentra **compilable y ejecutable**, con todos los módulos definidos en los requerimientos iniciales implementados y probados mediante pruebas unitarias y de integración con PHPUnit.

---

## Tecnologías utilizadas

- PHP 8 (programación orientada a objetos, sin frameworks)
- PDO (PHP Data Objects) para el acceso a la base de datos
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- PHPUnit (pruebas unitarias y de integración)
- Git / GitHub

---

## Requisitos previos

- PHP 8.0 o superior con la extensión `pdo_mysql` habilitada
- Servidor MySQL o MariaDB
- Servidor web con soporte PHP (servidor embebido de PHP, XAMPP, WAMP o similar)
- Composer (opcional, solo si se desea gestionar PHPUnit vía `vendor/`)
- Git

---

## Instalación y ejecución

```bash
git clone https://github.com/usuario/CommunityFix.git

cd CommunityFix
```

### Levantar el servidor

Con el servidor embebido de PHP, desde la raíz del proyecto:

```bash
php -S localhost:8000
```

La aplicación se ejecutará en:

```text
http://localhost:8000
```

También puede desplegarse dentro de un servidor Apache/Nginx con PHP (por ejemplo XAMPP), colocando el proyecto en el directorio `htdocs`/`www` bajo la carpeta `communityfix/` y accediendo mediante:

```text
http://localhost/communityfix/
```

> **Nota Importante a tomar en cuenta:** las rutas internas de la aplicación (login, logout, redirecciones) están construidas sobre `/communityfix/`, por lo que se recomienda mantener ese nombre de carpeta si se despliega en un servidor Apache/Nginx.

---

### Base de Datos

1. Abrir MySQL (por consola, phpMyAdmin o el gestor de preferencia).
2. Ejecutar el script `database/communityfix.sql`, el cual crea automáticamente la base de datos `communityfix`, sus tablas (`Roles`, `Usuarios`, `Categorias`, `Estados`, `Reportes`, `Imagenes`, `Notificaciones`, `Historial_Estados`) y los datos iniciales (roles, estados y categorías).
3. Verificar que las tablas y los datos semilla se hayan creado correctamente.
4. Configurar las credenciales de conexión en `data/Database.php` (host, nombre de la base de datos, usuario y contraseña) según el entorno local.
   
---

## Módulos implementados

- [x] Módulo de autenticación (registro e inicio de sesión con contraseñas cifradas con `password_hash`/`password_verify`)
- [x] Módulo de gestión de usuarios y roles (ciudadano / administrador)
- [x] Módulo de gestión de reportes (creación, listado por usuario, listado general, cambio de estado, eliminación)
- [x] Módulo de imágenes (carga y asociación de imágenes a los reportes)
- [x] Módulo de comentarios (comentarios de administradores y ciudadanos sobre un reporte)
- [x] Módulo de notificaciones (notificación automática al ciudadano cuando cambia el estado de su reporte o recibe un comentario)
- [x] Panel administrativo (estadísticas de reportes por estado, cambio de estado, eliminación de reportes)

---
## Estructura del proyecto

```text
communityfix/
├── assets/            Recursos estáticos (CSS, JS, imágenes de reportes)
├── controllers/        Controladores (Auth, Reporte, Admin)
├── data/                Conexión a base de datos (PDO)
├── database/           Script SQL de creación de la base de datos
├── models/              Modelos (Usuario, Reporte, Comentario, Notificacion)
├── services/           Lógica de negocio (AuthService, ReporteService)
├── views/               Vistas (login, registro, dashboard, reportes, admin)
├── tests/               Pruebas unitarias y de integración (PHPUnit)
├── index.php           Enrutador principal de la aplicación
└── phpunit.xml          Configuración de PHPUnit

---

## Autores

- Bryan Uriel Jones Tineo (2025-1391)
- Mayim Rodríguez Quezada (2024-1686)
- Enmanuel Alduey Santana (2024-2400)
- Ismeiri Adames Varona (2024-1467)
- Hidekel Ogando Lorenzo (2024-1426)
