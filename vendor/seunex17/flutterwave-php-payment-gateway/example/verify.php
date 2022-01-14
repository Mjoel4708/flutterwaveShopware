<?php

use Flutterwave\Transaction;

require_once "../src/Flutterwave.php";

$rave = new Transaction('YOUR-SEC-KEY', 'YOUR-PUBLIC-KEY');

//* Check payment status from the url get param
if (isset($_GET['status']) && isset($_GET['transaction_id']))
{
    if ($_GET['status'] == 'successful')
    {
        //* Grab the transaction id
        $txnID = $_GET['transaction_id'];

        //* Validate transaction

        /*
        ----------------------------------------------------------
        * Uncomment below to var dump the api output for wider use
        ----------------------------------------------------------
        */

        // echo '<pre>';
        // print_r($rave->verify($txnID));
        // echo '</pre>';
        // exit();

        //* Validation example
        $response = $rave->verify($txnID);
        
        if ($response->status == 'success')
        {
            //* Extracted data
            $id = $response->data->id;
            $txRef = $response->data->tx_ref;
            $flwRef = $response->data->flw_ref;
            $deviceFingerPrint = $response->data->device_fingerprint;
            $amount = $response->data->amount;
            $currency = $response->data->currency;
            $chargeAmount = $response->data->charged_amount;
            $appFee = $response->data->app_fee;
            $merchantFee = $response->data->merchant_fee;
            $processorResponse = $response->data->processor_response;
            $authModel = $response->data->auth_model;
            $ip = $response->data->ip;
            $narration = $response->data->narration;
            $status = $response->data->status;
            $paymentType = $response->data->payment_type;
            $createdAt = $response->data->created_at;
            $accountId = $response->data->account_id;

            //* Payment card information
            $firstSixDigit = $response->data->card->first_6digits;
            $lastFourDigit = $response->data->card->last_4digits;
            $issuer = $response->data->card->issuer;
            $country = $response->data->card->country;
            $cardType = $response->data->card->type;
            $cardToken = $response->data->card->token;
            $cardExpire = $response->data->country;

            //* meta
            $meta = $response->meta;

            //* Customer
            $name = $response->data->customer->name;
            $phone = $response->data->customer->phone;
            $email = $response->data->customer->phone;


            //* hint check if amount paid correct
            $productPrice = "YOUR PRODUCT PRICE FROM DATABASE";
            if($chargeAmount >= $productPrice)
            {
                //* Give item
            }
            else
            {
                //* Don't give item
            }
        }
    }

    //* Throw user to error page
}

//* Throw user to error page

?>