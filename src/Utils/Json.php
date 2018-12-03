<?php

namespace ThePay\ApiClient\Utils;

final class Json
{
    /**
     * @param string $json
     * @param bool $assoc
     * @param int $depth
     * @return mixed
     */
    public static function decode($json, $assoc = false, $depth = 512)
    {
        $data = \json_decode($json, $assoc, $depth);
        $error = \json_last_error();
        if (JSON_ERROR_NONE !== $error) {
            throw new \InvalidArgumentException('Error while decoding json: ' . $error);
        }

        return $data;
    }
}
