<?php 
namespace Flutterwave;

Class Transaction 
{
    protected $secKey;
    protected $pubKey;
    protected $baseUrl;

    public function __construct($secKey, $pubKey)
    {
        $this->secKey = $secKey;
        $this->pubKey = $pubKey;
        $this->baseUrl = 'https://api.flutterwave.com/v3';
    }

    public function checkout($req)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->baseUrl}/payments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($req),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->secKey,
                'Content-Type: application/json'
            ),
            ));

        try {
            $response = curl_exec($curl);
        } catch (\Exception $e) {
            $error = "Curl error {$e} ".curl_error($curl);
        }
        curl_close($curl);

        if ($response)
        {
            $res = json_decode($response);

            //* Check response status
            if ($res->status == 'success')
            {
                //* Redirect to payment link
                header('Location: '.$res->data->link);
            }
        }
        else
        {
            return "Problem calling the qpi";
        }
    }

    //* Verify transaction
    public function verify($txnID)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->baseUrl}/transactions/{$txnID}/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->secKey,
                'Content-Type: application/json'
            ),
            ));

        try {
            $response = curl_exec($curl);
        } catch (\Exception $e) {
            $error = "Curl error {$e} ".curl_error($curl);
        }
        curl_close($curl);

        if ($response)
        {
            return $res = json_decode($response);
        }
        else
        {
            return 'Return Problem verifying payment';
        }
    }
}