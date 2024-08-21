<?php

namespace ThePay\ApiClient\ValueObject;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

class EmailAddress extends NonEmptyString
{
    public static function filter($value)
    {
        $nonEmptyString = parent::filter($value);

        if ((new EmailValidator())->isValid($nonEmptyString, new RFCValidation()) === false) {
            throw self::invalidValue('e-mail address', $nonEmptyString);
        }

        return $nonEmptyString;
    }
}
