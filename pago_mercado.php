<?php
// Archivo: pago_mercado.php
// Procesa pagos con Mercado Pago usando su SDK

require_once __DIR__ . '/vendor/autoload.php';

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

// Configurar credenciales (reemplaza con tu token de acceso)
// IMPORTANTE: Usa variables de entorno en producción
$access_token = getenv('MERCADO_PAGO_ACCESS_TOKEN') ?: 'YOUR_ACCESS_TOKEN_HERE';
MercadoPagoConfig::setAccessToken($access_token);

// Datos del comprador y mentoria
$metodo_pago = isset($_POST['pago']) ? htmlspecialchars($_POST['pago']) : null;
$nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
$correo = isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : '';
$dni = isset($_POST['dni']) ? htmlspecialchars($_POST['dni']) : '';
$telefono = isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : '';
$mentoria = isset($_POST['mentoria']) ? htmlspecialchars($_POST['mentoria']) : 'Mentoría';
$precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;

// Si el método de pago es Mercado Pago
if ($metodo_pago === 'mercado' && $precio > 0) {
    try {
        // Crear preferencia de pago
        $client = new PreferenceClient();
        
        $preference = $client->create([
            "items" => [
                [
                    "id" => "1",
                    "title" => $mentoria,
                    "quantity" => 1,
                    "unit_price" => $precio,
                    "description" => "Acceso a " . $mentoria
                ]
            ],
            "payer" => [
                "name" => $nombre,
                "email" => $correo,
                "phone" => [
                    "area_code" => "54", // Argentina
                    "number" => $telefono
                ],
                "identification" => [
                    "type" => "DNI",
                    "number" => $dni
                ],
                "address" => [
                    "street_name" => "Argentina"
                ]
            ],
            "back_urls" => [
                "success" => "https://" . $_SERVER['HTTP_HOST'] . "/pago_exitoso.php",
                "pending" => "https://" . $_SERVER['HTTP_HOST'] . "/pago_pendiente.php",
                "failure" => "https://" . $_SERVER['HTTP_HOST'] . "/pago_fallido.php"
            ],
            "auto_return" => "approved",
            "notification_url" => "https://" . $_SERVER['HTTP_HOST'] . "/webhook.php",
            "external_reference" => "REF-" . time(),
            "expires" => false
        ]);

        // Redirigir a Mercado Pago
        if ($preference->id) {
            header("Location: " . $preference->init_point);
            exit;
        } else {
            $error = "Error al crear la preferencia de pago";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
} else if ($metodo_pago === 'mercado') {
    $error = "Método de pago no válido o precio faltante";
}

// Si hay error, mostrar mensaje
if (isset($error)) {
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Error de Pago</title></head><body>";
    echo "<h1>Error en el pago</h1>";
    echo "<p>" . htmlspecialchars($error) . "</p>";
    echo "<p><a href='javascript:history.back()'>Volver atrás</a></p>";
    echo "</body></html>";
    exit;
}
?>
