
# DigiPayzone  PHP

## Installation

```sh  
composer require digipayzone-php/payment
``` 
### Create Transaction
```sh  
$newPayment = new Payment("MERCHANT_ID", "MERCHANT_KEY", "API_SECRET");  
  
$newPayment->setOrderId("ORDER_ID");  
$newPayment->setCustomer("EMAIL", "MOBILE", "NAME");  
$newPayment->setPaymentAmount("AMOUNT");  
$newPayment->setReturnUrl("RETURN_URL");  
$newPayment->setUdf("UDF1", "UDF2", "UDF3", "UDF4", "UDF5");  
  
try {  
    $paymentResponse = $newPayment->createTransaction();
 } catch(\DigiPayzone\PHP\Payment\Exception\PaymentException $ex)
  {  
    // Handle Exception
 }  
```  

### Fetch Transaction
```sh  
 $newPayment = new Payment("MERCHANT_ID", "MERCHANT_KEY", "API_SECRET");
 try {  
    $paymentResponse = $newPayment->fetchTransactionByTransactionId("TRANSACTION_ID"); // By Transaction Id  
    $paymentResponse = $newPayment->fetchTransactionByOrderId("ORDER_ID"); // By Order Id  
 } catch(\DigiPayzone\PHP\Payment\Exception\PaymentException $ex) {  
    // Handle Exception
 }  
```  

### Refund Transaction
```sh  
$refund = new Refund("MERCHANT_ID", "MERCHANT_KEY", "API_SECRET");  
try {  
    $refundResponse = $refund->createRefund("TRANSACTION_ID", "REFUND_AMOUNT", "REFUND_REASON);
 } catch(\DigiPayzone\PHP\Payment\Exception\PaymentException $ex) 
 {  
    // Handle Exception
 }  
```

### Fetch Refund
```sh  
$refund = new Refund("MERCHANT_ID", "MERCHANT_KEY", "API_SECRET");  
try {  
    $refundResponse = $refund->fetchRefund("REFUND_ID");
 } catch(\DigiPayzone\PHP\Payment\Exception\PaymentException $ex) 
 {  
    // Handle Exception
 }  
```

### Fetch Transaction Refund List
```sh  
$refund = new Refund("MERCHANT_ID", "MERCHANT_KEY", "API_SECRET");  
try {  
    $refundResponse = $refund->fetchTransactionRefundList("TRANSACTION_ID");
 } catch(\DigiPayzone\PHP\Payment\Exception\PaymentException $ex) 
 {  
    // Handle Exception
 }  
```

### Create Payout Request
```sh
$payout = new Payout("MERCHANT_ID", "MERCHANT_KEY", "API_SECRET");  
$payout->setPayoutType("PAYOUT_TYPE"); // IMPS, NEFT, RTGS, UPI, PAYTM  
$payout->setReferenceId("REFERENCE_ID");  
$payout->setCustomer("NAME", "EMAIL", "MOBILE");  
$payout->setPayoutAmount("PAYOUT_AMOUNT");  
$payout->setAccountDetails("HOLDER_NAME", "ACCOUNT_NUMBER", "IFSC_CODE"); // For Bank Transfer  
// $payout->setVpaDetails("HOLDER_NAME", "UPI_ADDRESS"); // For UPI Transfer  

$payout->setUDF("UDF1", "UDF2", "UDF3", "UDF4", "UDF5");  
try {
  $response = $payout->createPayout();
} catch(\DigiPayzone\PHP\Payment\Exception\PaymentException $ex) {
  // Handle Exception
}

```

### Fetch Payout

```sh
$payout = new Payout("MERCHANT_ID", "MERCHANT_KEY", "API_SECRET");  
try {
    $response = $payout->fetchPayoutByPayoutId("PAYOUT_ID"); // By Payout Id
  $response = $payout->fetchPayoutByRefId("REFERENCE_ID"); // By Reference Id
} catch(\DigiPayzone\PHP\Payment\Exception\PaymentException $ex) {
  // Handle Exception
}
```