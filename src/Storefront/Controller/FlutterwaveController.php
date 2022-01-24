<?php

declare(strict_types=1);

namespace FlutterwavePay\Storefront\Controller;

use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use FlutterwaveApi\myEventHandler;
use Flutterwave\Rave;
use FlutterwaveApi\processPayment;
use FlutterwavePay\Service\FlutterwavePayment;
use Shopware\Core\Checkout\Cart\Error\Error;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionEntity;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStateHandler;
use Shopware\Core\Checkout\Payment\Exception\CustomerCanceledAsyncPaymentException;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Read\EntityReaderInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\StateMachine\Aggregation\StateMachineTransition\StateMachineTransitionActions;
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
    private EntityRepositoryInterface $stateMachineStateRepository;
    private EntityRepositoryInterface $flutterwavePaymentRepository;
    private OrderTransactionStateHandler $transactionStateHandler;
    private AccountOrderPageLoader $orderPageLoader;


    public function __construct(
        EntityRepositoryInterface $orderRepository, 
        EntityRepositoryInterface $transactionRepository, 
        EntityRepositoryInterface $flutterwavePaymentRepository,
        EntityRepositoryInterface $stateMachineStateRepository,
        OrderTransactionStateHandler $transactionStateHandler,
        AccountOrderPageLoader $orderPageLoader)
    {
        $this->orderRepository = $orderRepository;
        $this->transactionRepository = $transactionRepository;
        $this->orderPageLoader = $orderPageLoader;
        $this->flutterwavePaymentRepository = $flutterwavePaymentRepository;
        $this->stateMachineStateRepository = $stateMachineStateRepository;
        $this->transactionStateHandler = $transactionStateHandler;
    }




    /**
     * @HttpCache
     * @Route("/kamsw/flutterwave/payment/process/{transactionId}", name="flutterwave.payment.method", options={"seo"="false"}, methods={"GET","POST"}, defaults={"XmlHttpRequest": true})
     */
    public function flutterwavePayment(string $transactionId, Request $request, Context $context, SalesChannelContext $salesChannelContext)
    {
        $criteria = new Criteria([$transactionId]);
        $transaction = $this->transactionRepository->search($criteria, $context)->first();
        $orderId = $transaction->getOrderId();
        $order = $this->orderRepository->search(new Criteria([$orderId]), $context)->first();
        $data = $this->getTransactionData($order, $salesChannelContext);

        

        if (!$request->request->get('amount')) {
            //echo json_encode(['status' => 'error', 'message' => $data['amount'] . ' ' . $request->request->get('amount')]);
            //throw new \Exception('Amount mismatch');
            $respose = new Response('Unable to complete the tranaction', Response::HTTP_BAD_REQUEST);
            $this->transactionStateHandler->cancel($transactionId, $context);
            // throw new CustomerCanceledAsyncPaymentException(
            //     $transactionId,
            //     "Customer canceled the payment on flutterwave"
            // );
            return $this->renderStorefront(
                '@Storefront/storefront/component/payment/flutterwave/pay-button.html.twig',
                [
                    'order' => $order,
                    'response' => 'Unable to complete the tranaction',
                    'transaction' => $transaction,
                    'data' => $data
                ]
            );
        }
        $processPayment = new processPayment($data['amount']);
    }
    /**
     * 
     * @HttpCache
     * @Route("/kamsw/flutterwave/payment/{transactionId}", name="flutterwave.payment.form", options={"seo"="false"}, methods={"GET"}, defaults={"XmlHttpRequest"=true})
     */
    public function flutterwavePaymentForm(string $transactionId, Request $request, Context $context, SalesChannelContext $salesChannelContext): Response
    {
        $criteria = new Criteria([$transactionId]);
        $transaction = $this->transactionRepository->search($criteria, $context)->first();
        $orderId = $transaction->getOrderId();
        $order = $this->orderRepository->search(new Criteria([$orderId]), $context)->first();

        $this->saveFlutterwaveTransaction($order, $transaction, $salesChannelContext);
        $this->transactionStateHandler->processUnconfirmed($transactionId, $context);
        return $this->renderStorefront('@Storefront/storefront/component/payment/flutterwave/pay-button.html.twig', [
            'order' => $order,
            'transaction' => $transaction,
            'response' => "Please pay using the button below to complete your order",
        ]);
    }

    /**
     * @HttpCache
     * @Route("/kamsw/flutterwave/payment/success", name="flutterwave.payment.complete", options={"seo"="false"}, methods={"POST"}, defaults={"XmlHttpRequest"=true})
     */
    public function flutterwavePaymentSuccess(Request $request, SalesChannelContext $context)
    {
        //$page = $this->orderPageLoader->load($request, $context);
        return $this->renderStorefront('@Storefront/storefront/page/account/order-history/index.html.twig');
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


    function saveFlutterwaveTransaction(
        OrderEntity $order,
        OrderTransactionEntity $orderTransaction,
        SalesChannelContext $context
    ): void
    {
        
        $shopwarePaymentMethodId = $context->getPaymentMethod()->getId();
        /** @var CustomerEntity $customer */
        $customer = $context->getCustomer();
        $salesChannelId = $context->getSalesChannel()->getId();
        $transactionData = [
            'transactionId' => $orderTransaction->getId(),
            'customerId' => $customer->getId(),
            'orderId' => $order->getId(),
            'orderTransactionId' => $orderTransaction->getId(),
            'flutterwaveTransactionId' => $orderTransaction->getId(),
            'paymentMethod' => 'mpesa',
            'amount' => $order->getAmountTotal(),
            'status' => StateMachineTransitionActions::ACTION_REOPEN,
            'currency' => $context->getCurrency()->getIsoCode(),
            'orderStateId' => $orderTransaction->getStateId(),
            'environment' => 'staging',
            
        ];

        $this->flutterwavePaymentRepository->create([$transactionData], $context->getContext());



    }

    
    function updateFlutterwaveTransaction(
        OrderTransactionEntity $orderTransaction,
        SalesChannelContext $context
    ): void
    {
        $criteria = new Criteria([$orderTransaction->getId()]);
        $transaction = $this->flutterwavePaymentRepository->search($criteria, $context->getContext())->first();
        $transaction->setStatus('success');
        
        $this->flutterwavePaymentRepository->update([$transaction], $context->getContext());


    }
        
}
