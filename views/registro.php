<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>CommunityFix - Registro</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">

<header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
<div class="flex justify-between items-center px-6 py-4 max-w-6xl mx-auto">
    <div class="flex items-center gap-2">
        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-white">location_city</span>
        </div>
        <span class="text-xl font-bold text-blue-700">CommunityFix</span>
    </div>
    <a href="/communityfix/?action=login" class="bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition-all">
        Iniciar Sesión
    </a>
</div>
</header>

<main class="flex-grow flex items-center justify-center py-12 px-4 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full opacity-20 pointer-events-none">
        <div class="absolute -top-1/4 -right-1/4 w-[600px] h-[600px] bg-blue-200 blur-[120px] rounded-full"></div>
        <div class="absolute -bottom-1/4 -left-1/4 w-[500px] h-[500px] bg-green-200 blur-[100px] rounded-full"></div>
    </div>

    <!-- Formulario -->
    <div class="w-full max-w-[480px] bg-white rounded-xl shadow-lg relative z-10 p-8 border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Crea tu cuenta</h1>
            <p class="text-gray-500">Únete a CommunityFix para mejorar tu entorno.</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="flex items-center gap-2 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-lg mb-6">
            <span class="material-symbols-outlined text-red-500">error</span>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="/communityfix/?action=registro" class="space-y-5">
            <div>
                <label class="text-sm font-medium text-gray-600 block mb-1 ml-1">Nombre completo</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">person</span>
                    <input type="text" name="nombre" placeholder="Ingresa tu nombre"
                           value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                           class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" required>
                </div>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600 block mb-1 ml-1">Correo electrónico</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">mail</span>
                    <input type="email" name="correo" placeholder="usuario@ejemplo.com"
                           value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
                           class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600 block mb-1 ml-1">Contraseña</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                        <input type="password" name="contrasena" placeholder="Mín. 6 caracteres"
                               class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" required>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600 block mb-1 ml-1">Confirmar</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">verified_user</span>
                        <input type="password" name="confirmar" placeholder="Repite"
                               class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" required>
                    </div>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-blue-700 text-white py-3 rounded-lg font-semibold text-base shadow hover:bg-blue-800 active:scale-95 transition-all flex items-center justify-center gap-2 mt-2">
                <span>Registrarme</span>
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-500">
                ¿Ya tienes cuenta?
                <a href="/communityfix/?action=login" class="text-blue-700 font-medium hover:underline">Iniciar sesión</a>
            </p>
        </div>
    </div>

    <!-- Panel derecho -->
    <div class="hidden lg:flex flex-col ml-12 max-w-[380px]">
        <div class="bg-white/60 backdrop-blur-sm p-8 rounded-xl border border-gray-100 shadow-sm">
            <div class="mb-6">
                <span class="material-symbols-outlined text-green-600" style="font-size:48px; font-variation-settings:'FILL' 1;">eco</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Ciudadanía Activa</h2>
            <p class="text-gray-500 mb-6 text-base leading-relaxed">Participa en el cambio positivo de tu comunidad. Reporta incidencias, sugiere mejoras y haz seguimiento en tiempo real.</p>
            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600" style="font-size:18px;">check</span>
                    </div>
                    <span class="text-sm text-gray-600">Reporta problemas en segundos</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600" style="font-size:18px;">notifications</span>
                    </div>
                    <span class="text-sm text-gray-600">Recibe actualizaciones en tiempo real</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600" style="font-size:18px;">map</span>
                    </div>
                    <span class="text-sm text-gray-600">Visualiza reportes en el mapa</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex -space-x-3">
                    <div class="w-10 h-10 rounded-full border-2 border-white shadow bg-blue-200 flex items-center justify-center text-blue-700 font-bold text-sm">A</div>
                    <div class="w-10 h-10 rounded-full border-2 border-white shadow bg-green-200 flex items-center justify-center text-green-700 font-bold text-sm">M</div>
                    <div class="w-10 h-10 rounded-full border-2 border-white shadow bg-purple-200 flex items-center justify-center text-purple-700 font-bold text-sm">R</div>
                </div>
                <span class="text-xs text-gray-500 font-semibold">+500 vecinos activos hoy</span>
            </div>
        </div>
    </div>

</main>

<footer class="bg-gray-50 border-t border-gray-200 py-8 mt-12">
<div class="flex flex-col md:flex-row justify-between items-center px-10 max-w-6xl mx-auto gap-4">
    <div>
        <span class="text-lg font-bold text-blue-700">CommunityFix</span>
        <p class="text-xs text-gray-400">© 2024 CommunityFix.</p>
    </div>
    <div class="flex gap-6">
        <a href="#" class="text-xs text-gray-400 hover:text-blue-700">Términos</a>
        <a href="#" class="text-xs text-gray-400 hover:text-blue-700">Privacidad</a>
        <a href="#" class="text-xs text-gray-400 hover:text-blue-700">Contacto</a>
    </div>
</div>
</footer>

<script>
const inputs = document.querySelectorAll('input');
inputs.forEach(input => {
    input.addEventListener('blur', () => {
        if (input.value.length > 0) {
            input.classList.add('border-green-400');
            input.classList.remove('border-gray-200');
        } else {
            input.classList.remove('border-green-400');
            input.classList.add('border-gray-200');
        }
    });
});
</script>
</body>
</html>