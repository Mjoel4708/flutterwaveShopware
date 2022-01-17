<?php

declare(strict_types=1);

namespace FlutterwavePay\Storefront\Contoller;

use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use FlutterwaveApi\myEventHandler;
use Flutterwave\Rave;
use Symfony\Component\HttpFoundation\Request;

class FlutterwaveController
{
    
    
    
    
    
    /**
     * @HttpCache
     * @Route("/kamsware/flutterwave/payment", name="flutterwave.payment.method", options={"seo"="false"} methods={"POST", "GET"})
     */
    public function flutterwavePayment(OrderEntity $order, Request $request, SalesChannelContext $salesChannelContext)
    {
        $URL = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $getData = $_GET;
        $postData = $_POST;
        $publicKey = $_SERVER['PUBLIC_KEY'];
        $secretKey = $_SERVER['SECRET_KEY'];
        if(isset($_POST) && isset($postData['successurl']) && isset($postData['failureurl'])){
            $success_url = $postData['successurl'];
            $failure_url = $postData['failureurl'];
        }
        
        $env = $_SERVER['ENV'];
        
        if(isset($postData['amount'])){
            $_SESSION['publicKey'] = $publicKey;
            $_SESSION['secretKey'] = $secretKey;
            $_SESSION['env'] = $env;
            $_SESSION['successurl'] = $success_url;
            $_SESSION['failureurl'] = $failure_url;
            $_SESSION['currency'] = $postData['currency'];
            $_SESSION['amount'] = $postData['amount'];
        }
        
        $prefix = 'TP'; // Change this to the name of your business or app
        $overrideRef = false;
        
        // Uncomment here to enforce the useage of your own ref else a ref will be generated for you automatically
        if(isset($postData['ref'])){
            $prefix = $postData['ref'];
            $overrideRef = true;
        }
        
        $payment = new Rave($_SESSION['secretKey'], $prefix, $overrideRef);
        
        
        if(isset($postData['amount'])){
            // Make payment
            $payment
            ->eventHandler(new myEventHandler)
            ->setAmount($postData['amount'])
            ->setPaymentOptions($postData['payment_options']) // value can be card, account or both
            ->setDescription($postData['description'])
            ->setLogo($postData['logo'])
            ->setTitle($postData['title'])
            ->setCountry($postData['country'])
            ->setCurrency($postData['currency'])
            ->setEmail($postData['email'])
            ->setFirstname($postData['firstname'])
            ->setLastname($postData['lastname'])
            ->setPhoneNumber($postData['phonenumber'])
            ->setPayButtonText($postData['pay_button_text'])
            ->setRedirectUrl($URL)
            // ->setMetaData(array('metaname' => 'SomeDataName', 'metavalue' => 'SomeValue')) // can be called multiple times. Uncomment this to add meta datas
            // ->setMetaData(array('metaname' => 'SomeOtherDataName', 'metavalue' => 'SomeOtherValue')) // can be called multiple times. Uncomment this to add meta datas
            ->initialize();
        }else{
            if(isset($getData['cancelled'])){
                // Handle canceled payments
                $payment
                ->eventHandler(new myEventHandler)
                ->paymentCanceled($getData['cancelled']);
            }elseif(isset($getData['tx_ref'])){
                // Handle completed payments
                $payment->logger->notice('Payment completed. Now requerying payment.');
                $payment
                ->eventHandler(new myEventHandler)
                ->requeryTransaction($getData['transaction_id']);
            }else{
                $payment->logger->warn('Stop!!! Please pass the txref parameter!');
                echo 'Stop!!! Please pass the txref parameter!';
            }
        }
    }
    
    
    function getTransactionData(
        OrderEntity $order,
        SalesChannelContext $salesChannelContext

    ): array {
        $shopwarePaymentMethod = $salesChannelContext->getPaymentMethod()->getId();
        $salesChannelId = $salesChannelContext->getSalesChannel()->getId();
        $saleChannelName = $salesChannelContext->getSalesChannel()->getName();
        $amount = $order->getAmountTotal();
        $customerId = $order->getOrderCustomer()->getId();
        $currency = $salesChannelContext->getCurrency()->getIsoCode();
        $customer_phone = $order->getOrderCustomer()->getCustomerNumber();
        $customer_first_name = $order->getOrderCustomer()->getFirstName();
        $customer_email = $order->getOrderCustomer()->getEmail();

        $transactionData = [
            'amount' =>  $amount,
            'currency' => $currency,
            'customer_id' => $customerId,
            'customer_first_name' => $customer_first_name,
            'customer_phone' => $customer_phone,
            'customer_email' => $customer_email,
            'salesChannelName' => $saleChannelName




        ];
        return $transactionData;
    }

    function startPayment($transactionData)
    {
        $data['tx_ref'] = $this->generateTxref();
        $data['redirect_url'] = "";
        //redirect to the response url after payment
        $data['customer']['email'] = $transactionData['customer_email'];
        $data['customer']['phone_number'] = $transactionData['customer_phone'];
        $data['customer']['name'] = $transactionData['customer_first_name'];
        $data['customizations']['title'] = $transactionData['saleChannelName'] . "payments";
        $data['customizations']['description'] = "Online payments on " . $transactionData['saleChannelName'];
        $data['customizations']['logo'] = "";
        $data['amount'] = $this->input->post('amount', true);
        $data['currency'] = $transactionData['currency'];
        $data['redirect_url'] = "";
        $data['payment_options'] = "card,account,mpesa";
        $data['meta']['user_id'] = $transactionData["customer_id"];



        
        
    }
    function generateTxref()
    {
        $txref = "";
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $timeStamp = time();
        for ($i = 0; $i < 10; $i++) {
            $txref .= $characters[rand(0, $charactersLength - 1)];
        }
        $txref .= $timeStamp;
    }
    function generateRedirectUrl()
    {
    }
}
