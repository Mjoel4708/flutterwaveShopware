<?php

declare(strict_types=1);

namespace FlutterwavePay\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1641987648FluttewwavePaymentEntity extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1641987648;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $query = <<< SQL
        DROP TABLE IF EXISTS `flutterwave_payment`;
        CREATE TABLE IF NOT EXISTS `flutterwave_payment` (
            `id` BINARY(16) NOT NULL,
            `customer_id` BINARY(16),
            `order_id` BINARY(16) NOT NULL,
            `order_transaction_id` BINARY(16) NULL,
            `flutterwave_transaction_id` VARCHAR(255),
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            `payment_method` VARCHAR(255),
            `amount` FLOAT NOT NULL,
            `currency` VARCHAR(255) NULL,
            `status` VARCHAR(255) NOT NULL,
            `order_state_id` BINARY(16) NULL,
            `environment` VARCHAR(255) NULL,
            PRIMARY KEY (`id`),

            KEY `fk.flutterwave_payment.customer_id` (`customer_id`),
            KEY `fk.flutterwave_payment.order_id` (`order_id`),
            KEY `fk.flutterwave_payment.order_state_id` (`order_state_id`),
            

            CONSTRAINT `fk.flutterwave_payment.customer_id` FOREIGN KEY (`customer_id`)
                REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `fk.flutterwave_payment.order_id` FOREIGN KEY (`order_id`)
                REFERENCES `order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `fk.flutterwave_payment.order_transaction_id` FOREIGN KEY (`order_transaction_id`)
                REFERENCES `order_transaction` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `fk.flutterwave_payment.order_state_id` FOREIGN KEY (`order_state_id`)
                REFERENCES `state_machine_state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE

        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeUpdate($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
