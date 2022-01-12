<?php declare(strict_types=1);

namespace FlutterwavePay;



use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class FlutterwavePaymentDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'flutterwave_payment';
    

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([]);
    }
}