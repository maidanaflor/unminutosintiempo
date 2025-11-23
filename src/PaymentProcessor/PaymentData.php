<?php

namespace UnMinutoSinTiempo\PaymentProcessor;

class PaymentData
{
    private float $transactionAmount;
    private string $token;
    private string $description;
    private int $installments;
    private string $paymentMethodId;
    private ?string $issuerId;
    private array $payer;

    public function __construct(
        float $transactionAmount,
        string $token,
        string $description = 'Compra en unminutosintiempo.com',
        int $installments = 1,
        string $paymentMethodId = '',
        ?string $issuerId = null,
        array $payer = []
    ) {
        $this->transactionAmount = $transactionAmount;
        $this->token = $token;
        $this->description = $description;
        $this->installments = $installments;
        $this->paymentMethodId = $paymentMethodId;
        $this->issuerId = $issuerId;
        $this->payer = $payer ?: [
            'email' => 'test@example.com',
            'identification' => [
                'type' => 'DNI',
                'number' => '12345678'
            ]
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            transactionAmount: floatval($data['amount']),
            token: $data['token'],
            description: $data['description'] ?? 'Compra en unminutosintiempo.com',
            installments: intval($data['installments'] ?? 1),
            paymentMethodId: $data['payment_method_id'] ?? '',
            issuerId: $data['issuer_id'] ?? null,
            payer: $data['payer'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'transaction_amount' => $this->transactionAmount,
            'token' => $this->token,
            'description' => $this->description,
            'installments' => $this->installments,
            'payment_method_id' => $this->paymentMethodId,
            'issuer_id' => $this->issuerId,
            'payer' => $this->payer
        ];
    }

    public function validate(): array
    {
        $errors = [];

        if (empty($this->token)) {
            $errors[] = 'Token is required';
        }

        if ($this->transactionAmount <= 0) {
            $errors[] = 'Transaction amount must be greater than 0';
        }

        if (empty($this->paymentMethodId)) {
            $errors[] = 'Payment method ID is required';
        }

        return $errors;
    }
}