<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

use InvalidArgumentException;

/**
 * @template TValue of mixed
 */
abstract class BaseValueObject implements ValueObject
{
    /**
     * @var TValue
     */
    protected $value;

    /**
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    final public function __construct($value)
    {
        $this->value = static::filter($value);
    }

    /**
     * @param mixed $value
     *
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public static function create($value)
    {
        return new static($value);
    }

    /**
     * @return bool
     */
    public function equals(ValueObject $object)
    {
        if (get_class($object) !== get_class($this)) {
            return false;
        }

        return $this->getValue() === $object->getValue();
    }

    /**
     * @return TValue
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return TValue
     *
     * @throws InvalidArgumentException
     */
    abstract protected static function filter($value);

    protected static function invalidValue(string $expected, ?string $actual = null): InvalidArgumentException
    {
        return new InvalidArgumentException(
            'Value ' . ($actual === null ? '' : '"' . $actual . '" ') . 'is not ' . $expected,
        );
    }
}
