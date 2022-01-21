<?php

declare(strict_types=1);

namespace FlutterwavePay\Core\Content\FlutterwavePayment;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionDefinition;
use Shopware\Core\Checkout\Order\OrderDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\StateMachine\StateMachineDefinition;

class FlutterwavePaymentDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'flutterwave_payment';


    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
    public function getColletionClass(): string
    {
        return FluttewavePaymentCollection::class;
    }
    public function getEntityClass(): string
    {
        return FlutterwavePaymentEntity::class;
    }
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('customer_id', 'customerId', CustomerDefinition::class))
                ->addFlags(new Required()),
            (new FkField('order_id', 'orderId', OrderDefinition::class))
                ->addFlags(new Required()),
            (new FkField('order_transaction_id', 'orderTransactionId', OrderTransactionDefinition::class))
                ->addFlags(new Required()),
            (new FkField('order_state_id', 'orderStateId', StateMachineDefinition::class))
                ->addFlags(new Required()),
            (new StringField('flutterwave_transaction_id', 'flutterwaveTransactionId')),
            (new FloatField('amount', 'amount'))->addFlags(new Required()),
            (new StringField('currency', 'currency'))->addFlags(new Required()),
            (new StringField('payment_method', 'paymentMethod'))->addFlags(new Required()),
            (new StringField('status', 'status'))->addFlags(new Required()),
            (new StringField('environment', 'environment'))->addFlags(new Required()),
            new CreatedAtField(),
            new UpdatedAtField(),
            new ManyToOneAssociationField(
                'order',
                'order_id',
                OrderDefinition::class,
                'id'
            ),
            new ManyToOneAssociationField(
                'customer',
                'customer_id',
                CustomerDefinition::class,
                'id',
                true
            ),
            new ManyToOneAssociationField(
                'stateMachineState',
                'order_state_id',
                StateMachineDefinition::class,
                'id',
                true
            ),
            new OneToOneAssociationField(
                'orderTransaction',
                'order_transaction_id',
                'id',
                OrderTransactionDefinition::class,
                true
            ),

        ]);
    }
}
