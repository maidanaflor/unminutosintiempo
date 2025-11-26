<?php
// Configuración de MercadoPago
// IMPORTANTE: Cambia estos valores por los de tu cuenta real

return [
    'sandbox' => [
        'access_token' => ''
    ],
    'production' => [
        'access_token' => '' // Tu access token de producción
    ],
    'environment' => 'sandbox', // Cambiar a 'production' cuando vayas en vivo
];
?>