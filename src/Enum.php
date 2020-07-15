<?php

declare(strict_types=1);

namespace bohyn\Enum;

use ReflectionClass;

abstract class Enum
{

    /** @var mixed */
    protected $value;
    /** @var mixed[][] */
    protected static $constList = [];

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        self::getConstList();
        $this->set($value);
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * Get all valid values
     *
     * @return mixed[] [CONSTANT_NAME => value]
     */
    public static function getValidValues(): array
    {
        self::getConstList();

        return self::$constList[get_called_class()];
    }

    /**
     * @param static|mixed $value
     * @return bool
     */
    public function equals($value): bool
    {
        $value = $value instanceof static ? $value->get() : $value;

        return $this->value === $value;
    }

    /**
     * Returns true if actual enum value is one of $values. False otherwise
     *
     * @param mixed[] $values
     * @return bool
     */
    public function equalsAny(array $values): bool
    {
        $values = array_map(
            static function ($value) {
                return $value instanceof static ? $value->get() : $value;
            },
            $values
        );

        return in_array($this->value, $values, true);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isValid($value): bool
    {
        self::getConstList();

        return in_array($value, static::$constList[get_called_class()], true);
    }

    private static function getConstList(): void
    {
        if (array_key_exists(get_called_class(), static::$constList)) {
            return;
        }

        $reflection = new ReflectionClass(get_called_class());
        $constList = array_values($reflection->getConstants());
        static::$constList[get_called_class()] = $constList;
    }

    /**
     * @param mixed $value
     */
    private function set($value): void
    {
        if ($value instanceof static) {
            $value = $value->get();
        }

        if (static::isValid($value)) {
            $this->value = $value;

            return;
        }

        throw new EnumException(
            sprintf(
                "%s: Unknown enumeration value '%s'. Possible values: '%s'",
                get_called_class(),
                var_export($value, true),
                implode("','", static::$constList[get_called_class()])
            )
        );
    }
}
