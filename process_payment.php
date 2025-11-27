<?php
// Versión compatible con Hostinger (PHP 8.0+)
// Cargar configuración de rutas
$pathConfig = include __DIR__ . '/config_paths.php';

// Usar ruta concreta del autoload
$autoloadPath = $pathConfig['autoload_path'];

if (!file_exists($autoloadPath)) {
    error_log("ERROR: Autoload no encontrado en: " . $autoloadPath);
    die("Error: Composer autoload no encontrado en la ruta configurada");
}

require_once $autoloadPath;

use UnMinutoSinTiempo\PaymentProcessor\PaymentService;
use UnMinutoSinTiempo\PaymentProcessor\PaymentData;

// Configuración para Hostinger
ini_set('display_errors', 0); // Ocultar errores en producción
ini_set('log_errors', 1);

// Detectar si estamos en Hostinger
$isHostinger = strpos($_SERVER['DOCUMENT_ROOT'], '/home/') === 0;

if ($isHostinger) {
    // Configuración específica para Hostinger
    $logPath = dirname($_SERVER['DOCUMENT_ROOT']) . '/logs/';
    if (!is_dir($logPath)) {
        $logPath = $_SERVER['DOCUMENT_ROOT'] . '/';
    }
    ini_set('error_log', $logPath . 'payment_errors.log');
} else {
    // Configuración para desarrollo local
    ini_set('error_log', __DIR__ . '/payment_errors.log');
}

error_log("=== Payment Process Started - " . date('Y-m-d H:i:s') . " ===");
error_log("Server: " . ($isHostinger ? 'Hostinger' : 'Local'));

// Headers CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $rawInput = file_get_contents('php://input');
    error_log("Raw input received: " . $rawInput);
    
    $input = json_decode($rawInput, true);
    error_log("Parsed input: " . json_encode($input, JSON_PRETTY_PRINT));
    
    if (!$input) {
        error_log("ERROR: Failed to decode JSON data");
        throw new InvalidArgumentException('Invalid JSON data');
    }

    // Cargar configuración (usar la versión de Hostinger si existe)
    $configFile = 'config_mercadopago.php';
    
    $config = include $configFile;
    
    $paymentData = PaymentData::fromArray($input);
    $errors = $paymentData->validate();
    
    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        exit;
    }

    $paymentService = new PaymentService($config);
    $result = $paymentService->processPayment($paymentData);
    
    if (!$result->isSuccess()) {
        echo $result->toJson();
        http_response_code(422);
    }
    
    echo $result->toJson();

} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error'
    ]);
}
?>