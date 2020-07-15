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

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
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

        return in_array($value, static::$constList[static::class], true);
    }

    /**
     * Get all valid values
     *
     * @return mixed[] [CONSTANT_NAME => value]
     */
    public static function getValidValues(): array
    {
        self::getConstList();

        return self::$constList[static::class];
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
                static::class,
                var_export($value, true),
                implode("','", static::$constList[static::class])
            )
        );
    }

    private static function getConstList(): void
    {
        if (array_key_exists(static::class, static::$constList)) {
            return;
        }

        $reflection = new ReflectionClass(static::class);
        $constList = array_values($reflection->getConstants());
        static::$constList[static::class] = $constList;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }
}
