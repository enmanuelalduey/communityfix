<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header('Location: /communityfix/?action=login');
    exit();
}

require_once __DIR__ . '/../models/Comentario.php';
$comentarioModel = new Comentario();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CommunityFix - Panel Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">

<header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
<div class="flex justify-between items-center px-6 py-4 max-w-6xl mx-auto">
    <div class="flex items-center gap-2">
        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-white">location_city</span>
        </div>
        <span class="text-xl font-bold text-blue-700">CommunityFix</span>
        <span class="ml-2 text-xs font-semibold bg-red-100 text-red-700 px-2 py-1 rounded-full">ADMIN</span>
    </div>
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 bg-red-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600">admin_panel_settings</span>
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

<main class="max-w-6xl mx-auto px-6 py-8">

    <div class="bg-gradient-to-r from-red-700 to-red-500 rounded-2xl p-8 mb-6 flex justify-between items-center text-white shadow-lg">
        <div>
            <h1 class="text-2xl font-bold mb-1">Panel de Administración 🛠️</h1>
            <p class="text-red-100">Gestiona y da seguimiento a todos los reportes comunitarios.</p>
        </div>
        <span class="material-symbols-outlined text-white/30" style="font-size:80px;">admin_panel_settings</span>
    </div>

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

    <?php if (isset($_GET['exito'])): ?>
    <div class="flex items-center gap-2 bg-green-50 text-green-700 border border-green-200 px-4 py-3 rounded-lg mb-6">
        <span class="material-symbols-outlined text-green-500">check_circle</span>
        Acción realizada correctamente.
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Todos los Reportes</h2>
            <select id="filtroEstado" onchange="filtrar()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-blue-500">
                <option value="">Todos los estados</option>
                <option value="pendiente">Pendiente</option>
                <option value="en proceso">En proceso</option>
                <option value="resuelto">Resuelto</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left text-xs font-semibold text-gray-500 px-4 py-3">ID</th>
                        <th class="text-left text-xs font-semibold text-gray-500 px-4 py-3">Foto</th>
                        <th class="text-left text-xs font-semibold text-gray-500 px-4 py-3">Ciudadano</th>
                        <th class="text-left text-xs font-semibold text-gray-500 px-4 py-3">Título</th>
                        <th class="text-left text-xs font-semibold text-gray-500 px-4 py-3">Categoría</th>
                        <th class="text-left text-xs font-semibold text-gray-500 px-4 py-3">Ubicación</th>
                        <th class="text-left text-xs font-semibold text-gray-500 px-4 py-3">Fecha</th>
                        <th class="text-left text-xs font-semibold text-gray-500 px-4 py-3">Estado</th>
                        <th class="text-left text-xs font-semibold text-gray-500 px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="tablaBody">
                    <?php if (empty($reportes)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-12 text-gray-400">
                            <span class="material-symbols-outlined" style="font-size:48px;">report_problem</span>
                            <p class="mt-2">No hay reportes aún</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($reportes as $r): ?>
                    <?php
                        $color = match($r['nombre_estado']) {
                            'pendiente'  => 'bg-yellow-100 text-yellow-700',
                            'en proceso' => 'bg-blue-100 text-blue-700',
                            'resuelto'   => 'bg-green-100 text-green-700',
                            default      => 'bg-gray-100 text-gray-700'
                        };
                        $comentarios = $comentarioModel->listarPorReporte($r['id_reporte']);
                    ?>
                    <tr class="fila-reporte hover:bg-gray-50" data-estado="<?= $r['nombre_estado'] ?>">
                        <td class="px-4 py-4 text-sm text-gray-500">#<?= $r['id_reporte'] ?></td>
                        <td class="px-4 py-4">
                            <?php if (!empty($r['ruta_imagen'])): ?>
                                <img src="/communityfix/<?= htmlspecialchars($r['ruta_imagen']) ?>"
                                     class="w-12 h-12 object-cover rounded-lg cursor-pointer hover:scale-110 transition-all"
                                     onclick="verFoto(this.src)">
                            <?php else: ?>
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-300" style="font-size:20px;">image_not_supported</span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($r['nombre_usuario']) ?></td>
                        <td class="px-4 py-4 text-sm text-gray-700"><?= htmlspecialchars($r['titulo']) ?></td>
                        <td class="px-4 py-4 text-sm text-gray-500"><?= htmlspecialchars($r['nombre_categoria']) ?></td>
                        <td class="px-4 py-4 text-sm text-gray-500 max-w-[150px] truncate"><?= htmlspecialchars($r['ubicacion']) ?></td>
                        <td class="px-4 py-4 text-sm text-gray-400"><?= date('d/m/Y', strtotime($r['fecha_reporte'])) ?></td>
                        <td class="px-4 py-4">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full <?= $color ?>">
                                <?= htmlspecialchars($r['nombre_estado']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex gap-2">
                                <form method="POST" action="/communityfix/?action=admin-cambiar-estado" class="flex gap-1">
                                    <input type="hidden" name="id_reporte" value="<?= $r['id_reporte'] ?>">
                                    <select name="id_estado" class="text-xs border border-gray-200 rounded px-2 py-1 outline-none">
                                        <?php foreach ($estados as $e): ?>
                                            <option value="<?= $e['id_estado'] ?>" <?= $e['nombre_estado'] === $r['nombre_estado'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($e['nombre_estado']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="bg-blue-600 text-white text-xs px-2 py-1 rounded hover:bg-blue-700 transition-all">
                                        <span class="material-symbols-outlined" style="font-size:14px;">save</span>
                                    </button>
                                </form>
                                <button type="button" onclick="toggleComentarios(<?= $r['id_reporte'] ?>)"
                                    class="bg-green-500 text-white text-xs px-2 py-1 rounded hover:bg-green-600 transition-all">
                                    <span class="material-symbols-outlined" style="font-size:14px;">chat</span>
                                    <?php if (count($comentarios) > 0): ?>
                                    <span class="ml-1"><?= count($comentarios) ?></span>
                                    <?php endif; ?>
                                </button>
                                <form method="POST" action="/communityfix/?action=admin-eliminar" onsubmit="return confirm('¿Eliminar este reporte?')">
                                    <input type="hidden" name="id_reporte" value="<?= $r['id_reporte'] ?>">
                                    <button type="submit" class="bg-red-500 text-white text-xs px-2 py-1 rounded hover:bg-red-600 transition-all">
                                        <span class="material-symbols-outlined" style="font-size:14px;">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <!-- Fila comentarios -->
                    <tr id="comentarios-<?= $r['id_reporte'] ?>" class="hidden bg-gray-50">
                        <td colspan="9" class="px-6 py-4">
                            <div class="space-y-2 mb-3">
                                <?php if (empty($comentarios)): ?>
                                    <p class="text-xs text-gray-400">No hay comentarios aún.</p>
                                <?php else: ?>
                                    <?php foreach ($comentarios as $c): ?>
                                    <div class="bg-white rounded-lg p-3 border border-gray-100">
                                        <p class="text-xs font-semibold text-blue-700"><?= htmlspecialchars($c['nombre']) ?></p>
                                        <p class="text-sm text-gray-700 mt-1"><?= htmlspecialchars($c['comentario']) ?></p>
                                        <p class="text-xs text-gray-400 mt-1"><?= date('d/m/Y H:i', strtotime($c['fecha'])) ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <form method="POST" action="/communityfix/?action=comentar" class="flex gap-2">
                                <input type="hidden" name="id_reporte" value="<?= $r['id_reporte'] ?>">
                                <input type="text" name="comentario" placeholder="Escribe un comentario..."
                                       class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-blue-500">
                                <button type="submit" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">
                                    Enviar
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal foto -->
<div id="modalFoto" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center" onclick="this.classList.add('hidden'); this.classList.remove('flex')">
    <img id="modalImg" class="max-w-2xl max-h-[80vh] rounded-xl shadow-2xl">
</div>

<script>
function filtrar() {
    const estado = document.getElementById('filtroEstado').value.toLowerCase();
    const filas  = document.querySelectorAll('.fila-reporte');
    filas.forEach(fila => {
        const estadoFila = fila.dataset.estado.toLowerCase();
        fila.style.display = (!estado || estadoFila === estado) ? '' : 'none';
    });
}

function verFoto(src) {
    document.getElementById('modalImg').src = src;
    const modal = document.getElementById('modalFoto');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function toggleComentarios(id) {
    const fila = document.getElementById('comentarios-' + id);
    fila.classList.toggle('hidden');
}
</script>
</body>
</html>