<?php
namespace DigiPayzone\PHP\Payment;

use DigiPayzone\PHP\Payment\Exception\PaymentException;
use DigiPayzone\PHP\Payment\Utils\Utils;

class Payment extends Utils
{

    private $merchantId;
    private $merchantKey;
    private $apiSecret;
    private $merchantOrderId;
    private $customerEmail;
    private $customerMobile;
    private $customerName;
    private $paymentAmount;
    private $returnUrl;
    private $udf1;
    private $udf2;
    private $udf3;
    private $udf4;
    private $udf5;


    public function __construct($merchant_id, $merchant_key, $api_secret)
    {
        $this->merchantId = $merchant_id;
        $this->merchantKey = $merchant_key;
        $this->apiSecret = $api_secret;
    }

    /**
     * @throws PaymentException
     */
    public function createTransaction() {
        $create_transaction_request_data =
            $this->get_payment_request_data();

        $enc_create_transaction_data =
            base64_encode(json_encode($create_transaction_request_data));

        $create_transaction_request_hash =
            $this->generate_hash($enc_create_transaction_data, $this->apiSecret);

        $api_request_data =
            [
                "enc_data" => $enc_create_transaction_data
            ];

        $api_request_headers =
            [
                "X-MERCHANT-ID"     => $this->merchantId,
                "X-MERCHANT-KEY"    => $this->merchantKey,
                "X-REQUEST-HASH"    => $create_transaction_request_hash
            ];

        $response = $this->send_request($this->create_transaction_end_point,
            $api_request_data, $api_request_headers);
        return $this->parseResponse($response, false, $this->apiSecret);

    }

    /**
     * @throws PaymentException
     */
    public function fetchTransactionByOrderId($order_id, $is_hash_check = false) {
        $fetch_transaction_request_data =
            [
                "order_id" => $order_id
            ];
        return $this->fetchTransaction($fetch_transaction_request_data, $is_hash_check);
    }

    /**
     * @throws PaymentException
     */
    public function fetchTransactionByTransactionId($transaction_id, $is_hash_check = false) {
        $fetch_transaction_request_data =
            [
                "transaction_id" => $transaction_id
            ];
        return $this->fetchTransaction($fetch_transaction_request_data, $is_hash_check);
    }

    /**
     * @throws PaymentException
     */
    private function fetchTransaction($data, $isHashCheck) {
        $enc_create_transaction_data =
            base64_encode(json_encode($data));

        $create_transaction_request_hash =
            $this->generate_hash($enc_create_transaction_data, $this->apiSecret);

        $api_request_data =
            [
                "enc_data" => $enc_create_transaction_data
            ];

        $api_request_headers =
            [
                "X-MERCHANT-ID"     => $this->merchantId,
                "X-MERCHANT-KEY"    => $this->merchantKey,
                "X-REQUEST-HASH"    => $create_transaction_request_hash
            ];

        $response = $this->send_request($this->fetch_transaction_end_point,
            $api_request_data, $api_request_headers);

        return $this->parseResponse($response, $isHashCheck, $this->apiSecret);
    }

    public function setOrderId($merchantOrderId)
    {
        $this->merchantOrderId = $merchantOrderId;
        return $this;
    }

    public function setCustomer($customer_email, $customer_mobile, $customer_name)
    {
        $this->customerEmail = $customer_email;
        $this->customerMobile = $customer_mobile;
        $this->customerName = $customer_name;
        return $this;
    }

    public function setPaymentAmount($payment_amount)
    {
        $this->paymentAmount = $payment_amount;
        return $this;
    }

    public function setReturnUrl($return_url)
    {
        $this->returnUrl = $return_url;
        return $this;
    }

    public function setUdf($udf_1 = null, $udf_2 = null, $udf_3 = null, $udf_4 = null, $udf_5 = null)
    {
        $this->udf1 = $udf_1;
        $this->udf2 = $udf_2;
        $this->udf3 = $udf_3;
        $this->udf4 = $udf_4;
        $this->udf5 = $udf_5;
        return $this;
    }

    private function get_payment_request_data() {
        return [
            "merchant_order_id"     => $this->_empty($this->merchantOrderId),
            "customer_email"        => $this->_empty($this->customerEmail),
            "customer_mobile"       => $this->_empty($this->customerMobile),
            "customer_name"         => $this->_empty($this->customerName),
            "payment_amount"        => $this->_empty($this->paymentAmount),
            "return_url"            => $this->_empty($this->returnUrl),
            "udf_1"                 => $this->_empty($this->udf1),
            "udf_2"                 => $this->_empty($this->udf2),
            "udf_3"                 => $this->_empty($this->udf3),
            "udf_4"                 => $this->_empty($this->udf4),
            "udf_5"                 => $this->_empty($this->udf5)
        ];
    }


}

