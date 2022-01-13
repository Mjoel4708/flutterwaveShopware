<?php declare(strict_types=1);

namespace FlutterwavePay\Storefront\Contoller;

use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class FlutterwaveController
{
    function getTransactionData(
        OrderEntity $order,
        SalesChannelContext $salesChannelContext,
        string $returnUrl,

    ): array {
        $shopwarePaymentMethod = $salesChannelContext->getPaymentMethod()->getId();
        $salesChannelId = $salesChannelContext->getSalesChannel()->getId();
        $amount = $order->getAmountTotal();
        $customerId = $order->getOrderCustomer()->getId();
        $currency = $salesChannelContext->getCurrency()->getIsoCode();


        $transactionDate = [
            'amount' =>  $amount,
            'currency' => $currency,
            

        ]
    }
}