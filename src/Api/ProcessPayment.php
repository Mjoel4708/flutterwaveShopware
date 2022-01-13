<?php declare(strict_types=1);

namespace FlutterwavePay\Api;

use FlutterwavePay\FlutterwavePay;

class ProcessPayment
{
    private $secret_key, $public_key, $curl, $mode;

    

    public function __construct()
    {
        $this->public_key = FlutterwavePay:: PUBLIC_KEY;
        $this->secret_key = FlutterwavePay:: SECRET_KEY;
        $this->mode = FlutterwavePay:: ENVIRONMENT;
        $this->encryption_key = FlutterwavePay::ENCRYPTION_KEY;
        $this->currency_code = "KES";
        
    }

    public function getCredentials()
    {
        $credentials = array(
            "public_key" => $this->public_key,
            "secret_key" => $this->secret_key,
            "encryption_key" => $this->encryption_key,
            "currency" => $this->currency_code,
            "mode" => $this->mode
        );
        return $credentials;
    }

    function verifyTransaction($transaction_id)
    {
        

        $url = "https://api.flutterwave.com/v3/transactions/$transaction_id/verify";;
        $method = "GET";
        $create_transfer = $this->curl_request($url, $method);
        return $create_transfer;
    }

    function createPayment($data){
        
        $url = "https://api.flutterwave.com/v3/transactions";
        $method = "POST";
        $create_transfer = $this->curl_request($url, $method, $data);
        return $create_transfer;
    }

    public function curl_request($end_point, $method, $data = array()){
        $this->curl = curl_init();
        $data['seckey'] = $this->secret_key;

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => $end_point,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . $this->secret_key
            ),
        ));
        $response = curl_exec($this->curl);
        curl_close($this->curl);
        return $response;
    }

}