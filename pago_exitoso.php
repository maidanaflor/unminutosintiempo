<?php
// Archivo: pago_exitoso.php
// Página de confirmación exitosa
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Pago Exitoso!</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-green-50 to-green-100">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md text-center">
            <div class="mb-4">
                <svg class="w-16 h-16 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-green-600 mb-2">¡Pago Exitoso!</h1>
            <p class="text-gray-600 mb-6">Tu compra ha sido procesada correctamente. Recibirás un correo de confirmación en breve.</p>
            
            <div class="bg-gray-50 p-4 rounded mb-6 text-left">
                <h2 class="font-bold text-gray-800 mb-2">Próximos pasos:</h2>
                <ul class="text-sm text-gray-700 space-y-2">
                    <li>✓ Verifica tu correo electrónico</li>
                    <li>✓ Acceso inmediato a la mentoría</li>
                    <li>✓ Contacta a través de Instagram @unminutosintiempo</li>
                </ul>
            </div>

            <a href="index.html" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition">
                Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
