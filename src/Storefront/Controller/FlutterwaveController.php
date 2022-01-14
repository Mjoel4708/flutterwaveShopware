<?php

declare(strict_types=1);

namespace FlutterwavePay\Storefront\Contoller;

use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use FlutterwavePay\Api\ProcessPayment;
use Symfony\Component\HttpFoundation\Request;

class FlutterwaveController
{
    
    
    
    
    /**
     * @Route("/flutterwave/payment", name="flutterwave_payment", methods={"POST"})
     */
    public function flutterwavePayment(OrderEntity $order, Request $request, SalesChannelContext $salesChannelContext)
    {
        $order = $order;
        
        $transactionData = $this->getTransactionData($order, $salesChannelContext);
        $this->startPayment($transactionData, $salesChannelContext);

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
