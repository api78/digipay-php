<?php


namespace DigiPayzone\PHP\Payment\Exception;


class PaymentException extends \Exception
{

    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}