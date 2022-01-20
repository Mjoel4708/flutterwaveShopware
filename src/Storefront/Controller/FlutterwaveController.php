<?php

declare(strict_types=1);

namespace FlutterwavePay\Storefront\Controller;

use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use FlutterwaveApi\myEventHandler;
use Flutterwave\Rave;
use FlutterwaveApi\processPayment;
use Shopware\Core\Checkout\Cart\Error\Error;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Read\EntityReaderInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Storefront\Framework\Cache\Annotation\HttpCache;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Page\Account\Order\AccountOrderPageLoader;
use Shopware\Storefront\Page\Product\QuickView\MinimalQuickViewPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @RouteScope(scopes={"storefront"})
 */
class FlutterwaveController extends StorefrontController
{

    private EntityRepositoryInterface $orderRepository;
    private EntityRepositoryInterface $transactionRepository;
    private AccountOrderPageLoader $orderPageLoader;


    public function __construct(EntityRepositoryInterface $orderRepository, EntityRepositoryInterface $transactionRepository, AccountOrderPageLoader $orderPageLoader)
    {
        $this->orderRepository = $orderRepository;
        $this->transactionRepository = $transactionRepository;
        $this->orderPageLoader = $orderPageLoader;
    }




    /**
     * @HttpCache
     * @Route("/kamsw/flutterwave/payment/process/{transactionId}", name="flutterwave.payment.method", options={"seo"="false"}, methods={"GET","POST"}, defaults={"XmlHttpRequest": true})
     */
    public function flutterwavePayment(string $transactionId, Request $request, SalesChannelContext $context)
    {
        $criteria = new Criteria([$transactionId]);
        $transaction = $this->transactionRepository->search($criteria, $context->getContext())->first();
        $order = $transaction->getOrder();
        $data = $this->getTransactionData($order, $context);

        if ($data['amount'] != $request->request->get('amount')) {
            //echo json_encode(['status' => 'error', 'message' => $data['amount'] . ' ' . $request->request->get('amount')]);
            //throw new \Exception('Amount mismatch');
            $respose = new Response('Amount mismatch', 400);
            return $this->renderStorefront(
                '@Storefront/storefront/component/payment/flutterwave/pay-button.html.twig',
                [
                    'order' => $order, 'response' => $respose,
                    'transaction' => $transaction,
                    'data' => $data
                ]
            );
        }
        $processPayment = new processPayment();
    }
    /**
     * 
     * @HttpCache
     * @Route("/kamsw/flutterwave/payment/{transactionId}", name="flutterwave.payment.form", options={"seo"="false"}, methods={"GET"}, defaults={"XmlHttpRequest"=true})
     */
    public function flutterwavePaymentForm(string $transactionId, Request $request, SalesChannelContext $context): Response
    {
        $criteria = new Criteria([$transactionId]);
        $transaction = $this->transactionRepository->search($criteria, $context->getContext())->first();
        $order = $transaction->getOrder();



        return $this->renderStorefront('@Storefront/storefront/component/payment/flutterwave/pay-button.html.twig', [
            'order' => $order,
            'transaction' => $transaction,
            'response' => "Complete your transaction to pay for your order"
        ]);
    }

    /**
     * @HttpCache
     * @Route("/kamsw/flutterwave/payment/success", name="flutterwave.payment.complete", options={"seo"="false"}, methods={"POST"}, defaults={"XmlHttpRequest"=true})
     */
    public function flutterwavePaymentSuccess(Request $request, SalesChannelContext $context)
    {
        $page = $this->orderPageLoader->load($request, $context);
        return $this->renderStorefront('@Storefront/storefront/page/account/order-history/index.html.twig', ['page' => $page]);
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
