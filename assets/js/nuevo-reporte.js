
document.querySelector('input[type="file"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('preview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
});


function obtenerUbicacion() {
    const status = document.getElementById('gps-status');
    if (!navigator.geolocation) {
        status.textContent = 'Tu navegador no soporta GPS.';
        return;
    }
    status.textContent = 'Obteniendo ubicación...';
    navigator.geolocation.getCurrentPosition(
        async (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
                const data = await res.json();
                document.getElementById('ubicacion').value = data.display_name;
                status.textContent = '✅ Ubicación detectada';
            } catch {
                document.getElementById('ubicacion').value = `${lat}, ${lng}`;
                status.textContent = '✅ Coordenadas obtenidas';
            }
        },
        () => {
            status.textContent = '❌ No se pudo obtener la ubicación.';
        }
    );
}