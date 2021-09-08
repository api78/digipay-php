<?php


namespace DigiPayzone\PHP\Payment;


use DigiPayzone\PHP\Payment\Exception\PaymentException;
use DigiPayzone\PHP\Payment\Utils\Utils;

class Refund extends Utils
{
    private $merchantId;
    private $merchantKey;
    private $apiSecret;

    public function __construct($merchant_id, $merchant_key, $api_secret)
    {
        $this->merchantId = $merchant_id;
        $this->merchantKey = $merchant_key;
        $this->apiSecret = $api_secret;
    }

    /**
     * @throws PaymentException
     */
    public function createRefund($transaction_id, $refund_amount, $refund_reason) {
        $payload = [
            "transaction_id" => $transaction_id,
            "refund_amount" => $refund_amount,
            "refund_reason" => $refund_reason
        ];

        $requestPayload = [
            "enc_data" => base64_encode(json_encode($payload))
        ];
        $requestHash = $this->generate_hash($requestPayload["enc_data"], $this->apiSecret);
        $requestHeaders = [
            "X-MERCHANT-ID"     => $this->merchantId,
            "X-MERCHANT-KEY"    => $this->merchantKey,
            "X-REQUEST-HASH"    => $requestHash
        ];
        $response = $this->send_request($this->refund_transaction_end_point,
            $requestPayload, $requestHeaders);
        return $this->parseResponse($response, false, $this->apiSecret);
    }

    /**
     * @throws PaymentException
     */
    public function fetchRefund($refund_id, $is_hash_check = false) {
        $payload = [
            "refund_id" => $refund_id
        ];

        $requestPayload = [
            "enc_data" => base64_encode(json_encode($payload))
        ];
        $requestHash = $this->generate_hash($requestPayload["enc_data"], $this->apiSecret);
        $requestHeaders = [
            "X-MERCHANT-ID"     => $this->merchantId,
            "X-MERCHANT-KEY"    => $this->merchantKey,
            "X-REQUEST-HASH"    => $requestHash
        ];
        $response = $this->send_request($this->fetch_refund_transaction_end_point,
            $requestPayload, $requestHeaders);
        return $this->parseResponse($response, $is_hash_check, $this->apiSecret);
    }

    /**
     * @throws PaymentException
     */
    public function fetchTransactionRefundList($transaction_id, $is_hash_check = false) {
        $payload = [
            "transaction_id" => $transaction_id
        ];

        $requestPayload = [
            "enc_data" => base64_encode(json_encode($payload))
        ];
        $requestHash = $this->generate_hash($requestPayload["enc_data"], $this->apiSecret);
        $requestHeaders = [
            "X-MERCHANT-ID"     => $this->merchantId,
            "X-MERCHANT-KEY"    => $this->merchantKey,
            "X-REQUEST-HASH"    => $requestHash
        ];
        $response = $this->send_request($this->refund_transaction_list_end_point,
            $requestPayload, $requestHeaders);
        return $this->parseResponse($response, $is_hash_check, $this->apiSecret);
    }

}