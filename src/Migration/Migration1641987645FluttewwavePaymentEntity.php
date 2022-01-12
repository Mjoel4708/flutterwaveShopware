<?php declare(strict_types=1);

namespace FlutterwavePay\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1641987645FluttewwavePaymentEntity extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1641987645;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $query = <<< SQL
        CREATE TABLE IF NOT EXISTS `flutterwave_payment` (
            `id` BINARY(16) NOT NULL,
            `customer_id` BINARY(16),
            `order_id` BINARY(16) NOT NULL,
            `transaction_id` VARCHAR(255),
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            `payment_method` VARCHAR(255),
            `amount` DECIMAL(10,2) NOT NULL,
            `currency` VARCHAR(255) NULL,
            `exception` VARCHAR(255) NULL,
            `status` VARCHAR(255) NULL,
            `environment` VARCHAR(255) NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($query);


            
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
