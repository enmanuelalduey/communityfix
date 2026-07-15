<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: /communityfix/?action=login');
    exit();
}

require_once __DIR__ . '/../models/Reporte.php';
require_once __DIR__ . '/../models/Notificacion.php';

$reporteModel   = new Reporte();
$notifModel     = new Notificacion();
$reportes       = $reporteModel->listarPorUsuario($_SESSION['id_usuario']);
$notificaciones = $notifModel->listarPorUsuario($_SESSION['id_usuario']);
$noLeidas       = $notifModel->contarNoLeidas($_SESSION['id_usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CommunityFix - Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">

<header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
<div class="flex justify-between items-center px-6 py-4 max-w-5xl mx-auto">
    <div class="flex items-center gap-2">
        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-white">location_city</span>
        </div>
        <span class="text-xl font-bold text-blue-700">CommunityFix</span>
    </div>
    <div class="flex items-center gap-4">

        <!-- Notificaciones -->
        <div class="relative" id="notifBtn" onclick="toggleNotif()">
            <span class="material-symbols-outlined text-gray-500 cursor-pointer">notifications</span>
            <?php if ($noLeidas > 0): ?>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">
                <?= $noLeidas ?>
            </span>
            <?php endif; ?>

            <!-- Panel -->
            <div id="panelNotif" class="hidden absolute right-0 top-10 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-900 text-sm">Notificaciones</h3>
                    <?php if ($noLeidas > 0): ?>
                    <a href="/communityfix/?action=marcar-leidas" class="text-xs text-blue-600 hover:underline">Marcar todas leídas</a>
                    <?php endif; ?>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    <?php if (empty($notificaciones)): ?>
                    <div class="p-4 text-center text-gray-400 text-sm">No tienes notificaciones</div>
                    <?php else: ?>
                    <?php foreach ($notificaciones as $n): ?>
                    <a href="/communityfix/?action=ver-notificacion&id=<?= $n['id_notificacion'] ?>&reporte=<?= $n['id_reporte'] ?>"
                       class="block p-3 border-b border-gray-50 hover:bg-gray-50 transition-all <?= $n['leida'] ? 'bg-white' : 'bg-blue-50' ?>">
                        <div class="flex items-start gap-2">
                            <?php if (!$n['leida']): ?>
                            <span class="mt-1.5 w-2 h-2 bg-blue-500 rounded-full shrink-0"></span>
                            <?php endif; ?>
                            <div>
                                <p class="text-sm text-gray-700"><?= htmlspecialchars($n['mensaje']) ?></p>
                                <p class="text-xs text-gray-400 mt-1"><?= date('d/m/Y H:i', strtotime($n['fecha_envio'])) ?></p>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-600">person</span>
            </div>
            <span class="text-sm font-medium text-gray-700">
                <?= htmlspecialchars($_SESSION['nombre']) ?>
            </span>
        </div>
        <a href="/communityfix/?action=logout" class="flex items-center gap-1 text-sm text-red-500 hover:text-red-700 transition-all">
            <span class="material-symbols-outlined text-sm">logout</span> Salir
        </a>
    </div>
</div>
</header>

<main class="max-w-5xl mx-auto px-6 py-8">

    <div class="bg-gradient-to-r from-blue-700 to-blue-500 rounded-2xl p-8 mb-6 flex justify-between items-center text-white shadow-lg">
        <div>
            <h1 class="text-2xl font-bold mb-1">¡Bienvenido, <?= htmlspecialchars(explode(' ', $_SESSION['nombre'])[0]) ?>! 👋</h1>
            <p class="text-blue-100">Reporta problemas en tu comunidad y ayúdanos a mejorar juntos.</p>
        </div>
        <span class="material-symbols-outlined text-white/30" style="font-size:80px;">location_city</span>
    </div>

    <?php
        $total     = count($reportes);
        $pendiente = count(array_filter($reportes, fn($r) => $r['nombre_estado'] === 'pendiente'));
        $proceso   = count(array_filter($reportes, fn($r) => $r['nombre_estado'] === 'en proceso'));
        $resuelto  = count(array_filter($reportes, fn($r) => $r['nombre_estado'] === 'resuelto'));
    ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm text-center">
            <p class="text-3xl font-bold text-blue-700"><?= $total ?></p>
            <p class="text-sm text-gray-500 mt-1">Total</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm text-center">
            <p class="text-3xl font-bold text-yellow-500"><?= $pendiente ?></p>
            <p class="text-sm text-gray-500 mt-1">Pendientes</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm text-center">
            <p class="text-3xl font-bold text-blue-500"><?= $proceso ?></p>
            <p class="text-sm text-gray-500 mt-1">En proceso</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm text-center">
            <p class="text-3xl font-bold text-green-500"><?= $resuelto ?></p>
            <p class="text-sm text-gray-500 mt-1">Resueltos</p>
        </div>
    </div>

    <a href="/communityfix/?action=nuevo-reporte"
       class="flex items-center gap-4 bg-green-600 hover:bg-green-700 text-white rounded-xl p-5 mb-6 shadow transition-all active:scale-95">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-white">add_circle</span>
        </div>
        <div>
            <h3 class="font-bold text-lg">NUEVO REPORTE</h3>
            <p class="text-green-100 text-sm">Reporta un problema en tu comunidad</p>
        </div>
        <span class="material-symbols-outlined ml-auto">arrow_forward</span>
    </a>

    <div class="grid md:grid-cols-3 gap-6">

        <div class="md:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-bold text-gray-900">Mis Reportes</h2>
                <a href="/communityfix/?action=reportes" class="text-sm text-blue-600 hover:underline">Ver todos</a>
            </div>

            <?php if (empty($reportes)): ?>
            <div class="text-center py-8">
                <span class="material-symbols-outlined text-gray-300" style="font-size:48px;">report_problem</span>
                <p class="text-gray-400 mt-2">No tienes reportes aún</p>
            </div>
            <?php else: ?>
            <div class="space-y-3">
                <?php foreach (array_slice($reportes, 0, 3) as $r): ?>
                <?php
                    $color = match($r['nombre_estado']) {
                        'pendiente'  => 'bg-yellow-100 text-yellow-700',
                        'en proceso' => 'bg-blue-100 text-blue-700',
                        'resuelto'   => 'bg-green-100 text-green-700',
                        default      => 'bg-gray-100 text-gray-700'
                    };
                ?>
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-semibold text-sm text-gray-900"><?= htmlspecialchars($r['titulo']) ?></p>
                        <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:12px;">location_on</span>
                            <?= htmlspecialchars($r['ubicacion']) ?>
                        </p>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full <?= $color ?>">
                        <?= htmlspecialchars($r['nombre_estado']) ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-5">Categorías</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="/communityfix/?action=nuevo-reporte" class="flex flex-col items-center p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition-all">
                    <span class="material-symbols-outlined text-blue-600 mb-1">delete</span>
                    <p class="text-xs text-gray-600">Basura</p>
                </a>
                <a href="/communityfix/?action=nuevo-reporte" class="flex flex-col items-center p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition-all">
                    <span class="material-symbols-outlined text-blue-600 mb-1">water_drop</span>
                    <p class="text-xs text-gray-600">Agua</p>
                </a>
                <a href="/communityfix/?action=nuevo-reporte" class="flex flex-col items-center p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition-all">
                    <span class="material-symbols-outlined text-blue-600 mb-1">lightbulb</span>
                    <p class="text-xs text-gray-600">Alumbrado</p>
                </a>
                <a href="/communityfix/?action=nuevo-reporte" class="flex flex-col items-center p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition-all">
                    <span class="material-symbols-outlined text-blue-600 mb-1">road</span>
                    <p class="text-xs text-gray-600">Vialidad</p>
                </a>
                <a href="/communityfix/?action=nuevo-reporte" class="flex flex-col items-center p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition-all col-span-2">
                    <span class="material-symbols-outlined text-blue-600 mb-1">more_horiz</span>
                    <p class="text-xs text-gray-600">Otros</p>
                </a>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 cursor-pointer hover:bg-blue-50 transition-all">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-blue-600">map</span>
        </div>
        <div>
            <h4 class="font-semibold text-gray-900">Ver mapa de reportes</h4>
            <p class="text-sm text-gray-500">Explora los reportes en tu área</p>
        </div>
        <span class="material-symbols-outlined ml-auto text-gray-400">arrow_forward</span>
    </div>

</main>

<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex justify-around py-3 md:hidden z-50">
    <a href="/communityfix/views/dashboard.php" class="flex flex-col items-center text-blue-600">
        <span class="material-symbols-outlined">home</span>
        <p class="text-xs">Inicio</p>
    </a>
    <a href="/communityfix/?action=reportes" class="flex flex-col items-center text-gray-400">
        <span class="material-symbols-outlined">description</span>
        <p class="text-xs">Reportes</p>
    </a>
    <a href="/communityfix/?action=nuevo-reporte" class="flex flex-col items-center text-gray-400">
        <span class="material-symbols-outlined">add_circle</span>
        <p class="text-xs">Nuevo</p>
    </a>
    <a href="/communityfix/?action=logout" class="flex flex-col items-center text-gray-400">
        <span class="material-symbols-outlined">logout</span>
        <p class="text-xs">Salir</p>
    </a>
</nav>

<script>
function toggleNotif() {
    const panel = document.getElementById('panelNotif');
    panel.classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    const btn   = document.getElementById('notifBtn');
    const panel = document.getElementById('panelNotif');
    if (!btn.contains(e.target)) {
        panel.classList.add('hidden');
    }
});
</script>

</body>
</html>