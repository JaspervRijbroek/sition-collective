<?php declare(strict_types=1);

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;

class PaymentMethodSetupService
{
    public function create(Context $context, EntityRepositoryInterface $paymentMethodRepository, string $paymentMethodName): void
    {
        $paymentMethodExists = $paymentMethodRepository->search(new Criteria([Uuid::fromStringToHex($paymentMethodName)]), $context);

        if ($paymentMethodExists->getTotal() === 0) {
            $paymentMethodRepository->create([
                [
                    'id' => Uuid::fromStringToHex($paymentMethodName),
                    'name' => $paymentMethodName,
                    'position' => 1,
                    'active' => false,
                    'afterOrderEnabled' => true,
                ],
            ], $context);
        }
    }

    public function remove(Context $context, EntityRepositoryInterface $paymentMethodRepository, string $paymentMethodName): void
    {
        $paymentMethodRepository->delete([
            ['id' => Uuid::fromStringToHex($paymentMethodName)],
        ], $context);
    }

    public function activate(Context $context, EntityRepositoryInterface $paymentMethodRepository, string $paymentMethodName): void
    {
        $paymentMethodRepository->update([
            [
                'id' => Uuid::fromStringToHex($paymentMethodName),
                'active' => true
            ]
        ], $context);
    }

    public function deactivate(Context $context, EntityRepositoryInterface $paymentMethodRepository, string $paymentMethodName): void
    {
        $paymentMethodRepository->update([
            [
                'id' => Uuid::fromStringToHex($paymentMethodName),
                'active' => false
            ]
        ], $context);
    }
}
