<?php

namespace ThePay\ApiClient\Http;

/**
 * Interface is an abstract HTTP requests layer
 *
 * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
 */
interface HttpServiceInterface
{
    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
     *
     * @param string $url
     * @return HttpResponse
     */
    public function get($url);

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
     *
     * @param string $url
     * @param string $data POST request body content
     * @return HttpResponse
     */
    public function post($url, $data = '');

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
     *
     * @param string $url
     * @param string $data PUT request body content
     * @return HttpResponse
     */
    public function put($url, $data = '');

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
     *
     * @param string $url
     * @return HttpResponse
     */
    public function delete($url);
}
