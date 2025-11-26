<?php

namespace UnMinutoSinTiempo\PaymentProcessor;

class PaymentResult
{
    private bool $success;
    private ?string $paymentId;
    private ?string $status;
    private ?string $statusDetail;
    private ?string $message;
    private mixed $errorDetails;

    public function __construct(
        bool $success,
        ?string $paymentId = null,
        ?string $status = null,
        ?string $statusDetail = null,
        ?string $message = null,
        mixed $errorDetails = null
    ) {
        $this->success = $success;
        $this->paymentId = $paymentId;
        $this->status = $status;
        $this->statusDetail = $statusDetail;
        $this->message = $message;
        $this->errorDetails = $errorDetails;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function toArray(): array
    {
        $result = [
            'success' => $this->success,
            'message' => $this->message
        ];

        if ($this->paymentId) {
            $result['payment_id'] = $this->paymentId;
        }

        if ($this->status) {
            $result['status'] = $this->status;
        }

        if ($this->statusDetail) {
            $result['status_detail'] = $this->statusDetail;
        }

        if ($this->errorDetails) {
            $result['error_details'] = $this->errorDetails;
        }

        return $result;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}