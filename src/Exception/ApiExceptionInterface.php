<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Exception;

/**
 * Base interface for all exceptions thrown by the API.
 *
 * @todo breaking change: remove {@see ApiException} and then remove `Interface` suffix
 */
interface ApiExceptionInterface extends \Throwable
{
}
