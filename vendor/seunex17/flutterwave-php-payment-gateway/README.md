# Flutterwave Payment Gateway Package For PHP

An easy to use php package for flutterway payment gateway
The easiest way to make and accept payments from customers anywhere in the world.

## Installation
Install via composer RECOMMENDED 

```bash
composer require seunex17/flutterwave-php-payment-gateway
```

## Usage

Checkout page

```php
use Flutterwave\Transaction;

require '../vendor/autoload.php';

$rave = new Transaction('YOUR-SEC-KEY', 'YOUR-PUBLIC-KEY');

$request = [
    'tx_ref' => time(), //* A unique transaction references
    'amount' => 500, //* Amount to be charged
    'currency' => 'NGN', //* Payment currency
    'payment_options' => 'card', //* Payment options
    'redirect_url' => 'http://localhost/repo/flutterwave/example/verify.php', //* Redirect url to check payment status
    'customer' => [
        'email' => 'user@mail.com', //* Customer email
        'name' => 'Zubdev', //* Customer name,
        'phone' => '' //* Customer phone number
    ],
    'meta' => [
        //* An array of an additional information
    ],
    'customizations' => [
        'title' => 'Paying for a sample product', //* Payment title
        'description' => 'sample', //* Payment description
        'logo' => '' //* Your website logo url
    ]
];


//* Send data to Flutterwave hosted checkout page
$rave->checkout($request);
```

Payment Verification

```php
use Flutterwave\Transaction;

require '../vendor/autoload.php';

$rave = new Transaction('YOUR-SEC-KEY', 'YOUR-PUBLIC-KEY');

//* Check payment status from the url get param
if (isset($_GET['status']) && isset($_GET['transaction_id']))
{
    if ($_GET['status'] == 'successful')
    {
        //* Grab the transaction id
        $txnID = $_GET['transaction_id'];

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
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
