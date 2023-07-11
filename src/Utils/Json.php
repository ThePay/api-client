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

    /**
     * @param mixed $value
     *
     * @return non-empty-string
     */
    public static function encode($value, int $flags = 0, int $depth = 512): string
    {
        $json = \json_encode($value, $flags, $depth);
        if ($json === false) {
            throw new \InvalidArgumentException('Error while encoding json: ' . \json_last_error());
        }

        return $json;
    }
}
