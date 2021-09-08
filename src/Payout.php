<?php


namespace DigiPayzone\PHP\Payment;


use DigiPayzone\PHP\Payment\Exception\PaymentException;
use DigiPayzone\PHP\Payment\Utils\Utils;

class Payout extends Utils
{

    private $merchantId;
    private $merchantKey;
    private $apiSecret;
    private $payoutType;
    private $refId;
    private $customerName;
    private $customerEmail;
    private $customerMobile;
    private $accountHolder;
    private $accountNumber;
    private $ifscCode;
    private $vpa;
    private $payoutAmount;
    private $udf1;
    private $udf2;
    private $udf3;
    private $udf4;
    private $udf5;

    const IMPS = "IMPS";
    const NEFT = "NEFT";
    const RTGS = "RTGS";
    const UPI = "UPI";
    const PAYTM = "PAYTM";

    public function __construct($merchant_id, $merchant_key, $api_secret)
    {
        $this->merchantId = $merchant_id;
        $this->merchantKey = $merchant_key;
        $this->apiSecret = $api_secret;
    }

    /**
     * @throws PaymentException
     */
    public function createPayout() {
        $payload = $this->prepareCreatePayoutRequestData();
        $requestPayload = [
            "enc_data" => base64_encode(json_encode($payload))
        ];
        $requestHash = $this->generate_hash($requestPayload["enc_data"], $this->apiSecret);
        $requestHeaders = [
            "X-MERCHANT-ID"     => $this->merchantId,
            "X-MERCHANT-KEY"    => $this->merchantKey,
            "X-REQUEST-HASH"    => $requestHash
        ];
        $response = $this->send_request($this->create_payout_end_point,
            $requestPayload, $requestHeaders);
        return $this->parseResponse($response, false, $this->apiSecret);
    }


    /**
     * @throws PaymentException
     */
    public function fetchPayoutByRefId($ref_id, $is_hash_check = false) {
        $payload = [
            "ref_id" => $ref_id
        ];
        return $this->fetchPayout($payload, $is_hash_check);
    }


    /**
     * @throws PaymentException
     */
    public function fetchPayoutByPayoutId($payout_id, $is_hash_check = false) {
        $payload = [
            "payout_id" => $payout_id
        ];
        return $this->fetchPayout($payload, $is_hash_check);
    }

    /**
     * @throws PaymentException
     */
    private function fetchPayout($payload, $isHashCheck) {
        $requestPayload = [
            "enc_data" => base64_encode(json_encode($payload))
        ];
        $requestHash = $this->generate_hash($requestPayload["enc_data"], $this->apiSecret);
        $requestHeaders = [
            "X-MERCHANT-ID"     => $this->merchantId,
            "X-MERCHANT-KEY"    => $this->merchantKey,
            "X-REQUEST-HASH"    => $requestHash
        ];
        $response = $this->send_request($this->fetch_payout_end_point,
            $requestPayload, $requestHeaders);
        return $this->parseResponse($response, $isHashCheck, $this->apiSecret);
    }

    private function prepareCreatePayoutRequestData()
    {
        return [
            "payout_type" => $this->_empty($this->payoutType),
            "ref_id" => $this->_empty($this->refId),
            "customer_name" => $this->_empty($this->customerName),
            "customer_email" => $this->_empty($this->customerEmail),
            "customer_mobile" => $this->_empty($this->customerMobile),
            "account_holder" => $this->_empty($this->accountHolder),
            "account_number" => $this->_empty($this->accountNumber),
            "ifsc_code" => $this->_empty($this->ifscCode),
            "vpa" => $this->_empty($this->vpa),
            "payout_amount" => $this->_empty($this->payoutAmount),
            "udf_1" => $this->_empty($this->udf1),
            "udf_2" => $this->_empty($this->udf2),
            "udf_3" => $this->_empty($this->udf3),
            "udf_4" => $this->_empty($this->udf4),
            "udf_5" => $this->_empty($this->udf5),
        ];
    }

    public function setPayoutType($payoutType)
    {
        $this->payoutType = $payoutType;
        return $this;
    }

    public function setReferenceId($refId)
    {
        $this->refId = $refId;
        return $this;
    }

    public function setCustomer($customer_name, $customer_email, $customer_mobile)
    {
        $this->customerName = $customer_name;
        $this->customerEmail = $customer_email;
        $this->customerMobile = $customer_mobile;
        return $this;
    }

    public function setPayoutAmount($payout_amount)
    {
        $this->payoutAmount = $payout_amount;
        return $this;
    }

    public function setAccountDetails($account_holder_name, $account_number, $ifsc_code)
    {
        $this->accountHolder = $account_holder_name;
        $this->accountNumber = $account_number;
        $this->ifscCode = $ifsc_code;
        return $this;
    }

    public function setVpaDetails($account_holder_name, $vpa)
    {
        $this->accountHolder = $account_holder_name;
        $this->vpa = $vpa;
        return $this;
    }

    public function setUDF($udf1 = null, $udf2 = null, $udf3 = null, $udf4 = null, $udf5 = null)
    {
        $this->udf1 = $udf1;
        $this->udf2 = $udf2;
        $this->udf3 = $udf3;
        $this->udf4 = $udf4;
        $this->udf5 = $udf5;
        return $this;
    }
}