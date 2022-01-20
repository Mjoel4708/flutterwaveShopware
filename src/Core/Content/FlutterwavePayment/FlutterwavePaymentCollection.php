<?php declare(strict_types=1);

namespace FlutterwavePay\Core\Content\FlutterwavePayment;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class FluttewavePaymentCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return FlutterwavePaymentEntity::class;
    }
}