<?php
// Configuración de MercadoPago
// IMPORTANTE: Cambia estos valores por los de tu cuenta real

return [
    'sandbox' => [
        'access_token' => 'TEST-8906640851873215-112302-e76b4d6c35a050b7dbbad7bac75f43f5-149366760'
    ],
    'production' => [
        'access_token' => 'APP_USR-1456013210536434-111416-1902e6c083405c587f432e2db5eead08-2991323352' // Tu access token de producción
    ],
    'environment' => 'sandbox', // Cambiar a 'production' cuando vayas en vivo
];
?>