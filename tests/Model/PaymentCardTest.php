<?php

namespace ThePay\ApiClient\Tests\Model;

use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\Model\PaymentCard;

final class PaymentCardTest extends TestCase
{
    public function testNullableExpirationDate(): void
    {
        $card = new PaymentCard([
            'number' => '1234******4321',
            'expiration_date' => null,
            'brand' => 'Visa',
            'type' => PaymentCard::TYPE_CREDIT,
        ]);

        self::assertNull($card->getExpirationDate());
    }
    public function testExpirationDate(): void
    {
        $card = new PaymentCard([
            'number' => '1234******4321',
            'expiration_date' => '2024-08',
            'brand' => 'Visa',
            'type' => PaymentCard::TYPE_CREDIT,
        ]);

        self::assertNotNull($card->getExpirationDate());
        self::assertSame(
            (new \DateTime('2024-08-31 23:59:59.999999'))->format('Y-m-d H:i:s.u'),
            $card->getExpirationDate()->format('Y-m-d H:i:s.u')
        );
    }
}
