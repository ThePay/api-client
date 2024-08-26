<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;

class EmailAddress extends NonEmptyString
{
    public static function filter($value)
    {
        $nonEmptyString = parent::filter($value);

        if ((new EmailValidator())->isValid($nonEmptyString, new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation(),
        ])) === false) {
            throw self::invalidValue('public e-mail address', $nonEmptyString);
        }

        return $nonEmptyString;
    }
}
