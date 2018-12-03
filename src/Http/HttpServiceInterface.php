<?php

namespace ThePay\ApiClient\Http;

/**
 * Interface is an abstract HTTP requests layer
 */
interface HttpServiceInterface
{

    /**
     * @param string $url
     * @return HttpResponse
     */
    public function get($url);

    /**
     * @param string $url
     * @param string $data POST request body content
     * @return HttpResponse
     */
    public function post($url, $data = '');

    /**
     * @param string $url
     * @return HttpResponse
     */
    public function delete($url);
}
