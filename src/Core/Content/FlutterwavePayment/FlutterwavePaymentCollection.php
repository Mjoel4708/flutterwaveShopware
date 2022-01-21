<?php

declare(strict_types=1);

namespace FlutterwavePay\Core\Content\FlutterwavePayment;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use FlutterwavePay\Core\Content\FlutterwavePayment\FlutterwavePaymentEntity;

/**
 * @method void                                                                 add(FlutterwavePaymentEntity $entity)
 * @method void set(string $key, FlutterwavePaymentEntity $entity)
 * @method FlutterwavePaymentEntity[]                                          getIterator()
 * @method FlutterwavePaymentEntity[]                                          getElements()
 * @method FlutterwavePaymentEntity|null                                       get(string $key)
 * @method FlutterwavePaymentEntity|null                                       first()
 * @method FlutterwavePaymentEntity|null                                       last()
 */


class FluttewavePaymentCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return FlutterwavePaymentEntity::class;
    }
}
