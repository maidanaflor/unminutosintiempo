<?php
require_once 'vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

// Habilitar logs de error
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/payment_errors.log');

// Log de inicio
error_log("=== INICIO PROCESO DE PAGO - " . date('Y-m-d H:i:s') . " ===");

// Configurar CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    error_log("REQUEST OPTIONS - Enviando headers CORS");
    exit(0);
}

// Cargar configuración de MercadoPago
error_log("Cargando configuración de MercadoPago...");
$config = include 'config_mercadopago.php';
$environment = $config['environment'];
$accessToken = $config[$environment]['access_token'];

error_log("Ambiente: " . $environment);
error_log("Access Token (primeros 20 caracteres): " . substr($accessToken, 0, 20) . "...");

// Configurar MercadoPago
MercadoPagoConfig::setAccessToken($accessToken);
error_log("MercadoPago configurado correctamente");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $rawInput = file_get_contents('php://input');
        error_log('Raw input: ' . $rawInput);
        
        $input = json_decode($rawInput, true);
        error_log('Datos parseados: ' . json_encode($input));
        
        // Validar datos requeridos
        if (!$input) {
            error_log('ERROR: No se recibieron datos JSON válidos');
            echo json_encode([
                'success' => false,
                'message' => 'Datos JSON inválidos'
            ]);
            exit;
        }
        
        // Validar datos requeridos
        if (!isset($input['token']) || !isset($input['amount'])) {
            error_log('ERROR: Faltan campos requeridos - token o amount');
            echo json_encode([
                'success' => false,
                'message' => 'Datos incompletos: se requiere token y amount'
            ]);
            exit;
        }
        
        error_log('MODO REAL: Procesando pago con MercadoPago SDK');
        
        $paymentData = [
            "transaction_amount" => floatval($input['amount']),
            "token" => $input['token'],
            "description" => $input['description'] ?? 'Compra en unminutosintiempo.com',
            "installments" => intval($input['installments'] ?? 1),
            "payment_method_id" => $input['payment_method_id'],
            "issuer_id" => $input['issuer_id'] ?? null,
            "payer" => [
                "email" => $input['payer']['email'] ?? 'test@example.com',
                "identification" => [
                    "type" => $input['payer']['identification']['type'] ?? 'DNI',
                    "number" => $input['payer']['identification']['number'] ?? '12345678'
                ]
            ]
        ];
        
        error_log('Datos del pago: ' . json_encode($paymentData));
        
        $client = new PaymentClient();
        
        // Crear el pago
        $payment = $client->create($paymentData);
        
        error_log('Pago creado - ID: ' . $payment->id . ', Status: ' . $payment->status);
        
        // Responder con el resultado
        echo json_encode([
            'success' => true,
            'payment_id' => $payment->id,
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'message' => 'Pago procesado correctamente'
        ]);
        
    } catch (MPApiException $e) {
        error_log('ERROR MPApiException: ' . $e->getMessage());
        error_log('API Response: ' . json_encode($e->getApiResponse()));
        
        echo json_encode([
            'success' => false,
            'message' => 'Error de MercadoPago: ' . $e->getMessage(),
            'error_details' => $e->getApiResponse()
        ]);
    } catch (Exception $e) {
        error_log('ERROR Exception: ' . $e->getMessage());
        error_log('Stack trace: ' . $e->getTraceAsString());
        
        echo json_encode([
            'success' => false,
            'message' => 'Error interno: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
?>