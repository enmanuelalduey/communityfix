<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../models/Comentario.php';
$comentarioModel = new Comentario();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CommunityFix - Mis Reportes</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">

<header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
<div class="flex justify-between items-center px-6 py-4 max-w-4xl mx-auto">
    <div class="flex items-center gap-2">
        <a href="/communityfix/views/dashboard.php" class="text-gray-400 hover:text-gray-600 mr-2">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-white text-sm">location_city</span>
        </div>
        <span class="text-lg font-bold text-blue-700">CommunityFix</span>
    </div>
    <a href="/communityfix/?action=logout" class="text-sm text-red-500 hover:text-red-700 flex items-center gap-1">
        <span class="material-symbols-outlined text-sm">logout</span> Salir
    </a>
</div>
</header>

<main class="max-w-4xl mx-auto py-10 px-4">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mis Reportes</h1>
            <p class="text-gray-500 mt-1">Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></p>
        </div>
        <a href="/communityfix/?action=nuevo-reporte"
           class="bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add</span>
            Nuevo Reporte
        </a>
    </div>

    <?php if (isset($_GET['exito'])): ?>
    <div class="flex items-center gap-2 bg-green-50 text-green-700 border border-green-200 px-4 py-3 rounded-lg mb-6">
        <span class="material-symbols-outlined text-green-500">check_circle</span>
        ¡Reporte enviado correctamente!
    </div>
    <?php endif; ?>

    <?php if (empty($reportes)): ?>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
        <span class="material-symbols-outlined text-gray-300" style="font-size:64px;">report_problem</span>
        <h3 class="text-lg font-semibold text-gray-500 mt-4">No tienes reportes aún</h3>
        <p class="text-gray-400 mt-2">Crea tu primer reporte para mejorar tu comunidad</p>
        <a href="/communityfix/?action=nuevo-reporte"
           class="inline-flex items-center gap-2 mt-6 bg-blue-700 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-800 transition-all">
            <span class="material-symbols-outlined text-sm">add</span>
            Crear Reporte
        </a>
    </div>
    <?php else: ?>
    <div class="space-y-4">
        <?php foreach ($reportes as $reporte): ?>
        <?php $comentarios = $comentarioModel->listarPorReporte($reporte['id_reporte']); ?>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-blue-100 text-blue-700">
                            <?= htmlspecialchars($reporte['nombre_categoria']) ?>
                        </span>
                        <?php
                            $estado = $reporte['nombre_estado'];
                            $color = match($estado) {
                                'pendiente'  => 'bg-yellow-100 text-yellow-700',
                                'en proceso' => 'bg-blue-100 text-blue-700',
                                'resuelto'   => 'bg-green-100 text-green-700',
                                default      => 'bg-gray-100 text-gray-700'
                            };
                        ?>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full <?= $color ?>">
                            <?= htmlspecialchars($estado) ?>
                        </span>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">
                        <?= htmlspecialchars($reporte['titulo']) ?>
                    </h3>
                    <p class="text-sm text-gray-500 mb-2">
                        <?= htmlspecialchars($reporte['descripcion']) ?>
                    </p>
                    <div class="flex items-center gap-4 text-xs text-gray-400 mb-3">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:14px;">location_on</span>
                            <?= htmlspecialchars($reporte['ubicacion']) ?>
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:14px;">schedule</span>
                            <?= date('d/m/Y H:i', strtotime($reporte['fecha_reporte'])) ?>
                        </span>
                    </div>

                    <!-- Boton comentarios -->
                    <button onclick="toggleComentarios(<?= $reporte['id_reporte'] ?>)"
                            class="flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 transition-all">
                        <span class="material-symbols-outlined" style="font-size:16px;">chat</span>
                        Comentarios (<?= count($comentarios) ?>)
                    </button>

                    <!-- Seccion comentarios -->
                    <div id="comentarios-<?= $reporte['id_reporte'] ?>" class="hidden mt-4">
                        <div class="space-y-2 mb-3">
                            <?php if (empty($comentarios)): ?>
                                <p class="text-xs text-gray-400">No hay comentarios aún.</p>
                            <?php else: ?>
                                <?php foreach ($comentarios as $c): ?>
                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                    <p class="text-xs font-semibold <?= $c['nombre'] === $_SESSION['nombre'] ? 'text-blue-700' : 'text-red-600' ?>">
                                        <?= htmlspecialchars($c['nombre']) ?>
                                        <?= $c['nombre'] === $_SESSION['nombre'] ? '(Tú)' : '(Admin)' ?>
                                    </p>
                                    <p class="text-sm text-gray-700 mt-1"><?= htmlspecialchars($c['comentario']) ?></p>
                                    <p class="text-xs text-gray-400 mt-1"><?= date('d/m/Y H:i', strtotime($c['fecha'])) ?></p>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <form method="POST" action="/communityfix/?action=comentar" class="flex gap-2">
                            <input type="hidden" name="id_reporte" value="<?= $reporte['id_reporte'] ?>">
                            <input type="text" name="comentario" placeholder="Escribe un comentario..."
                                   class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-blue-500">
                            <button type="submit" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">
                                Enviar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>

<script>
function toggleComentarios(id) {
    const div = document.getElementById('comentarios-' + id);
    div.classList.toggle('hidden');
}
</script>
</body>
</html>