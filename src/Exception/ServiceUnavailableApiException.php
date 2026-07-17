<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Exception;

/**
 * Covers all HTTP 5xx server errors, connection timeouts, and network failure scenarios (not limited to HTTP 503)
 *
 * @todo breaking change: remove {@see ApiException} and then extend {@see \RuntimeException}
 */
final class ServiceUnavailableApiException extends ApiException implements ApiExceptionInterface
{
}
