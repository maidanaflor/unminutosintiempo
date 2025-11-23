<?php

namespace UnMinutoSinTiempo\PaymentProcessor;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Exceptions\MPApiException;

class PaymentService
{
    private PaymentClient $client;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $environment = $config['environment'];
        $accessToken = $config[$environment]['access_token'];
        
        MercadoPagoConfig::setAccessToken($accessToken);
        $this->client = new PaymentClient();
        
        error_log("PaymentService initialized for environment: " . $environment);
    }

    public function processPayment(PaymentData $paymentData): PaymentResult
    {
        try {
            error_log('Processing payment with data: ' . json_encode($paymentData->toArray()));
            
            $request_options = new RequestOptions();
            $idempotencyKey = 'pay-' . uniqid();
            $request_options->setCustomHeaders(["X-Idempotency-Key: " . $idempotencyKey]);
            
            error_log('Using idempotency key: ' . $idempotencyKey);
            
            $payment = $this->client->create($paymentData->toArray(), $request_options);
            
            error_log('Payment created - ID: ' . $payment->id . ', Status: ' . $payment->status);
            
            return new PaymentResult(
                success: true,
                paymentId: $payment->id,
                status: $payment->status,
                statusDetail: $payment->status_detail,
                message: 'Pago procesado correctamente'
            );
            
        } catch (MPApiException $e) {
            error_log('MPApiException: ' . $e->getMessage());
            error_log('HTTP Status Code: ' . $e->getCode());
            
            $apiResponse = $e->getApiResponse();
            
            // Convertir el objeto MPResponse a array para logging
            $responseData = null;
            if ($apiResponse && method_exists($apiResponse, 'getContent')) {
                $responseData = $apiResponse->getContent();
                error_log('API Response Content: ' . json_encode($responseData, JSON_PRETTY_PRINT));
            } else {
                error_log('API Response: No content available or empty response');
            }
            
            // También intentar obtener el status code de la respuesta
            if ($apiResponse && method_exists($apiResponse, 'getStatusCode')) {
                error_log('API Response Status Code: ' . $apiResponse->getStatusCode());
            }
            
            return new PaymentResult(
                success: false,
                message: 'Error de MercadoPago: ' . $e->getMessage(),
                errorDetails: $responseData
            );
            
        } catch (\Exception $e) {
            error_log('Exception: ' . $e->getMessage());
            
            return new PaymentResult(
                success: false,
                message: 'Error interno: ' . $e->getMessage()
            );
        }
    }
}