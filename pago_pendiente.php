<?php
// Archivo: pago_pendiente.php
// Página de pago pendiente
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Pendiente</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-yellow-50 to-yellow-100">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md text-center">
            <div class="mb-4">
                <svg class="w-16 h-16 mx-auto text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-yellow-600 mb-2">Pago Pendiente</h1>
            <p class="text-gray-600 mb-6">Tu pago está siendo procesado. Esto puede tomar algunos minutos.</p>
            
            <div class="bg-gray-50 p-4 rounded mb-6 text-left">
                <h2 class="font-bold text-gray-800 mb-2">¿Qué sucede ahora?</h2>
                <ul class="text-sm text-gray-700 space-y-2">
                    <li>• Tu pago está en proceso</li>
                    <li>• Recibirás una confirmación por correo</li>
                    <li>• En caso de duda, contacta a unminutosintiempo@gmail.com</li>
                </ul>
            </div>

            <a href="index.html" class="inline-block bg-yellow-600 text-white px-6 py-3 rounded-md hover:bg-yellow-700 transition">
                Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
