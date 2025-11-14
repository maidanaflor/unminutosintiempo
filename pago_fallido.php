<?php
// Archivo: pago_fallido.php
// Página de pago fallido
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Fallido</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-red-50 to-red-100">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md text-center">
            <div class="mb-4">
                <svg class="w-16 h-16 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-red-600 mb-2">Pago Fallido</h1>
            <p class="text-gray-600 mb-6">Lo sentimos, tu pago no pudo ser procesado. Por favor, intenta nuevamente.</p>
            
            <div class="bg-gray-50 p-4 rounded mb-6 text-left">
                <h2 class="font-bold text-gray-800 mb-2">Posibles razones:</h2>
                <ul class="text-sm text-gray-700 space-y-2">
                    <li>• Fondos insuficientes</li>
                    <li>• Datos de tarjeta incorrectos</li>
                    <li>• Tarjeta rechazada por el banco</li>
                </ul>
            </div>

            <div class="space-y-2">
                <button onclick="history.back()" class="w-full bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700 transition">
                    Reintentar Pago
                </button>
                <a href="index.html" class="block text-indigo-600 hover:text-indigo-700 text-sm">
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</body>
</html>
