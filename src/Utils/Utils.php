<?php


namespace DigiPayzone\PHP\Payment\Utils;

use DigiPayzone\PHP\Payment\Exception\PaymentException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;

class Utils
{

    // DigiPayzone API Base URL
    protected $baseUrl = "https://checkout.digipayzone.com";

    // DigiPayzone API End Point
    protected $create_transaction_end_point = "/api/v1/create/transaction";
    protected $fetch_transaction_end_point = "/api/v1/fetch/transaction";
    protected $refund_transaction_end_point = "/api/v1/refund/transaction";
    protected $fetch_refund_transaction_end_point = "/api/v1/fetch/refund";
    protected $refund_transaction_list_end_point = "/api/v1/transaction/refund/list";
    protected $create_payout_end_point = "/api/v1/create/payout";
    protected $fetch_payout_end_point = "/api/v1/fetch/payout";

    // Generate Signature
    protected function generate_hash($data, $key) {
        return hash_hmac('SHA256', $data, $key);
    }

    /**
     * Verify Signature
     * @throws PaymentException
     */
    protected function verify_signature($hashedValue, $data, $key)
    {
        $generateSignature = $this->generate_hash($data, $key);

        if(hash_equals($generateSignature, $hashedValue)) {
            return true;
        }
        throw new PaymentException("Invalid Hash");
    }

    /**
     * @throws PaymentException
     */
    protected function send_request($end_point, $request_data, $headers) {
        try {
            $client = new Client(['base_uri' => $this->baseUrl]);
            $client_response = $client->post($end_point, [
                "headers"   => $headers,
                "json"      => $request_data
            ]);
            $responseBody = json_decode($client_response->getBody()->getContents());
            $responseHeader = $this->parseResponseHeader($client_response->getHeaders());

            return json_decode(json_encode([
                "body" => $responseBody,
                "header" => $responseHeader
            ]));
        } catch (ConnectException $ex) {
            throw new PaymentException( "Client request error");
        }
        catch (GuzzleException $ex) {
            return json_decode(json_encode([
                "body" => json_decode($ex->getResponse()->getBody()->getContents()),
                "header" => null
            ]));
        }
    }

    protected function _empty($value) {
        return isset($value) ? $value : '';
    }

    /**
     * @throws PaymentException
     */
    protected function parseResponse($response, $isHashCheck, $apiSecret) {
        if($response->body->status) {
            if($isHashCheck) {
                if(isset($response->header->x_request_hash)) {
                    if($this->validateResponseHash($response->body->data, $response->header->x_response_hash, $apiSecret)) {
                        $response->body->data = json_decode(base64_decode($response->body->data));
                        return $response->body;
                    } else{
                        throw new PaymentException( "Response Hash not matched");
                    }
                } else {
                    throw new PaymentException( "Response Hash not available");
                }
            } else{
                if(isset($response->body->data)) {
                    if(gettype($response->body->data) === "string") {
                        $response->body->data = json_decode(base64_decode($response->body->data));
                    }
                }
                return $response->body;
            }
        }
        return $response->body;
    }

    private function parseResponseHeader($getHeaders)
    {
        $responseHeader = [];
        if(isset($getHeaders)) {
            foreach ($getHeaders as $key => $value) {
                $responseHeader[strtolower(str_replace("-", "_", $key))] = json_decode($value[0]) ? json_decode($value[0]) : $value[0];
            }
        }
        return $responseHeader;
    }

    private function validateResponseHash($dataString, $apiSecret, $responseHash)
    {
        $generatedHash = $this->generate_hash($dataString, $apiSecret);
        return strcmp($responseHash, $generatedHash) === 0;
    }

}