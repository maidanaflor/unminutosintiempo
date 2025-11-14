<?php
// Archivo: webhook.php
// Recibe notificaciones de Mercado Pago sobre cambios de pago

require_once __DIR__ . '/vendor/autoload.php';

use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

// Configurar credenciales
$access_token = getenv('MERCADO_PAGO_ACCESS_TOKEN') ?: 'YOUR_ACCESS_TOKEN_HERE';
MercadoPagoConfig::setAccessToken($access_token);

// Recibir notificación
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['data']['id'])) {
    try {
        $client = new PaymentClient();
        $payment = $client->get($data['data']['id']);
        
        // Guardar información del pago en un archivo de log o base de datos
        $log_data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'payment_id' => $payment->id,
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'payer_email' => $payment->payer->email ?? 'N/A',
            'amount' => $payment->transaction_amount,
            'external_reference' => $payment->external_reference ?? 'N/A'
        ];
        
        // Guardar en archivo de log
        $log_file = __DIR__ . '/pagos_log.txt';
        file_put_contents(
            $log_file,
            json_encode($log_data) . "\n",
            FILE_APPEND
        );
        
        // Aquí puedes enviar emails de confirmación, actualizar base de datos, etc.
        if ($payment->status === 'approved') {
            // Pago aprobado - enviar acceso a la mentoría
            enviar_acceso_mentoria($payment->payer->email, $payment->external_reference);
        }
        
        // Responder con 200 OK
        http_response_code(200);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
}

/**
 * Envía el acceso a la mentoría después de pago confirmado
 */
function enviar_acceso_mentoria($email, $reference) {
    $asunto = "¡Tu pago ha sido confirmado! - Un minuto sin tiempo";
    $mensaje = "
    <h2>¡Gracias por tu compra!</h2>
    <p>Tu pago ha sido confirmado correctamente.</p>
    <p>Accederás a tu mentoría en breve. Por favor, contacta a través de Instagram @unminutosintiempo para coordinar.</p>
    <p>Referencia: " . htmlspecialchars($reference) . "</p>
    ";
    
    $headers = "From: unminutosintiempo@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    mail($email, $asunto, $mensaje, $headers);
}
?>
