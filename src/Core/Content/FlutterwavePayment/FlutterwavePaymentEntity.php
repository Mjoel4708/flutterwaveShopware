<?php declare(strict_types=1);

namespace FlutterwavePay\Core\Content\FlutterwavePayment;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionEntity;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateEntity;

class FlutterwavePaymentEntity extends Entity
{   
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $customerId;


    /**
     * @var string
     */
    protected $orderId;

    /**
     * @var string
     */
    protected $orderTransactionId;


    /**
     * @var string
     */

    protected $flutterwaveTransactionId;


    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */

    protected $currency;

    /**
     * @var string
     */

    protected $paymentMethod;


    /**
     * @var string
     */


    protected $status;


    /**
     * @var string
     */

    protected $environment;

    /**
     * @var OrderEntity|null
     */
    protected $order;

    /**
     * @var CustomerEntity|null
     */
    protected $customer;

    /**
     * @var StateMachineStateEntity|null
     */

    protected $stateMachineState;

    /**
     * @var OrderTransactionEntity|null
     */
    protected $orderTransaction;

    /**
     * @var string|null
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }
    /**
     * @param string $customerId
     */
    public function setCustomerId(string $customerId): void
    {
        $this->customerId = $customerId;
    }
    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }
    /**
     * @param string $orderId
     */
    public function setOrderId(string $orderId): void
    {
        $this->orderId = $orderId;
    }
    /**
     * @return string
     */
    public function getOrderTransactionId(): string
    {
        return $this->orderTransactionId;
    }
    /**
     * @param string $orderTransactionId
     */
    public function setOrderTransactionId(string $orderTransactionId): void
    {
        $this->orderTransactionId = $orderTransactionId;
    }
    /**
     * @return string
     */
    public function getFlutterwaveTransactionId(): string
    {
        return $this->flutterwaveTransactionId;
    }
    /**
     * @param string $flutterwaveTransactionId
     */
    public function setFlutterwaveTransactionId(string $flutterwaveTransactionId): void
    {
        $this->flutterwaveTransactionId = $flutterwaveTransactionId;
    }
    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
    /**
     * @param string $currency
     */
    public function setCurreny(string $currency): void
    {
        $this->currency = $currency;
    }
    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }
    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }
    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }
    /**
     * @param string $orderStateId
     * 
     */
    public function setOrderStateId(string $orderStateId){
        $this->orderStateId = $orderStateId;
    }
    /**
     * @return orderStateId
     */
    public function getOrderStateId(){
        return $this->orderStateId;
    }
    /**
     * @return OrderEntity
     */
    public function getOrder(): OrderEntity
    {
        return $this->order;
    }
    /**
     * @param OrderEntity $order
     */
    public function setOrder(OrderEntity $order): void
    {
        $this->order = $order;
    }

    /**
     * @return CustomerEntity
     */
    public function getCustomer(): CustomerEntity
    {
        return $this->customer;
    }
    /**
     * @param CustomerEntity $customer
     */
    public function setCustomer(CustomerEntity $customer): void
    {
        $this->customer = $customer;

    }

    /**
     * @return OrderTransactionEntity
     */
    public function getOrderTransaction(): OrderTransactionEntity
    {
        return $this->orderTransaction;
    }
    /**
     * @param OrderTransactionEntity $orderTransaction
     */
    public function setOrderTransaction(OrderTransactionEntity $orderTransaction): void
    {
        $this->orderTransaction = $orderTransaction;
    }

    /**
     * @return StateMachineStateEntity
     */
    public function getStateMachineState(): StateMachineStateEntity
    {
        return $this->stateMachineState;
    }
    /**
     * @param StateMachineStateEntity $stateMachineState
     */
    public function setStateMachineState(StateMachineStateEntity $stateMachineState): void
    {   
        $this->stateMachineState = $stateMachineState;

    }
    public function getData(): array
    {
        return[
            'id' => $this->getId(),
            'customerId' => $this->getCustomerId(),
            'orderId' => $this->getOrderId(),
            'orderTransactionId' => $this->getOrderTransactionId(),
            'flutterwaveTransactionId' => $this->getFlutterwaveTransactionId(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'paymentMethod' => $this->getPaymentMethod(),
            'status' => $this->getStatus(),
            'environment' => $this->getEnvironment(),
            'customer' => $this->getCustomer(),
            'orderStateId' => $this->getOrderStateId(),
            'status' => $this->getStatus(),
        ];

        
    }





}