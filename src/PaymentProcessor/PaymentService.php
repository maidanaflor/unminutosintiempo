<?php

namespace UnMinutoSinTiempo\PaymentProcessor;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
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
            
            $payment = $this->client->create($paymentData->toArray());
            
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
            error_log('API Response: ' . json_encode($e->getApiResponse()));
            
            return new PaymentResult(
                success: false,
                message: 'Error de MercadoPago: ' . $e->getMessage(),
                errorDetails: $e->getApiResponse()
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