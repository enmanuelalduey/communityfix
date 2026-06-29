<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CommunityFix - Nuevo Reporte</title>
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

<main class="max-w-2xl mx-auto py-10 px-4">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Nuevo Reporte</h1>
        <p class="text-gray-500 mt-1">Reporta un problema en tu comunidad</p>
    </div>

    <?php if (!empty($error)): ?>
    <div class="flex items-center gap-2 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-lg mb-6">
        <span class="material-symbols-outlined text-red-500">error</span>
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form method="POST" action="/communityfix/?action=nuevo-reporte" enctype="multipart/form-data" class="space-y-6">

            <div>
                <label class="text-sm font-medium text-gray-700 block mb-2">Categoría</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">category</span>
                    <select name="id_categoria" class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" required>
                        <option value="">Selecciona una categoría</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id_categoria'] ?>">
                                <?= $cat['nombre_categoria'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 block mb-2">Título del problema</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">title</span>
                    <input type="text" name="titulo" placeholder="Ej. Basura acumulada en la esquina"
                           value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>"
                           class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" required>
                </div>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 block mb-2">Descripción</label>
                <textarea name="descripcion" placeholder="Describe el problema con detalle..."
                          rows="4"
                          class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all resize-none" required><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 block mb-2">Ubicación</label>
                <div class="relative flex gap-2">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">location_on</span>
                    <input type="text" name="ubicacion" id="ubicacion" placeholder="Ej. Calle 5 de Mayo, esquina con Av. Juárez"
                           value="<?= htmlspecialchars($_POST['ubicacion'] ?? '') ?>"
                           class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" required>
                    <button type="button" onclick="obtenerUbicacion()"
                            class="shrink-0 bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-lg text-sm font-medium transition-all flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">my_location</span>
                        GPS
                    </button>
                </div>
                <p id="gps-status" class="text-xs text-gray-400 mt-1"></p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 block mb-2">Fotografía <span class="text-gray-400">(opcional)</span></label>
                <label class="flex flex-col items-center justify-center w-full py-8 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all">
                    <span class="material-symbols-outlined text-gray-400 mb-2" style="font-size:40px;">photo_camera</span>
                    <p class="text-sm text-gray-500">Agregar fotografía</p>
                    <p class="text-xs text-gray-400 mt-1">PNG, JPG hasta 5MB</p>
                    <input type="file" name="imagen" accept="image/*" class="hidden">
                </label>
                <div id="preview" class="mt-3 hidden">
                    <img id="imgPreview" class="w-full rounded-lg max-h-48 object-cover border border-gray-200">
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-blue-700 text-white py-3 rounded-lg font-semibold text-base shadow hover:bg-blue-800 active:scale-95 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">send</span>
                Enviar Reporte
            </button>
        </form>
    </div>
</main>

<script src="/communityfix/assets/js/nuevo-reporte.js"></script>

</body>
</html>