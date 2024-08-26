<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\EmailAddress;

final class EmailAddressTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return EmailAddress::class;
    }

    /**
     * @see https://uasg.tech/download/uasg-004-use-cases-for-ua-readiness-evaluation-en/
     */
    public static function validValuesAndStringRepresentationsDataProvider(): array
    {
        return [
            // user part
            ['user@universal-acceptance-test.icu', 'user@universal-acceptance-test.icu'],
            ['user+label@universal-acceptance-test.icu', 'user+label@universal-acceptance-test.icu'],
            // domain part
            ['ascii+short@universal-acceptance-test.icu', 'ascii+short@universal-acceptance-test.icu'],
            ['ascii+long@universal-acceptance-test.international', 'ascii+long@universal-acceptance-test.international'],
            ['idn+ltr@համընդհանուր-ընկալում-թեստ.հայ', 'idn+ltr@համընդհանուր-ընկալում-թեստ.հայ'],
            ['idn+rtl@تجربة-القبول-الشامل.موريتانيا', 'idn+rtl@تجربة-القبول-الشامل.موريتانيا'],
        ];
    }

    public static function invalidValuesAndExceptionMessagesDataProvider(): array
    {
        return array_merge(
            NonEmptyStringTest::invalidValuesAndExceptionMessagesDataProvider(),
            [
                ['something', 'Value "something" is not public e-mail address'],
                ['user@example.com', 'Value "user@example.com" is not public e-mail address'],
                ['user@domain.internal', 'Value "user@domain.internal" is not public e-mail address'],
            ],
        );
    }
}
