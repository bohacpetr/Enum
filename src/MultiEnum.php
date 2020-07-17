<?php

declare(strict_types=1);

namespace bohyn\Enum;

use Iterator;

/**
 * @implements Iterator<int, mixed>
 */
abstract class MultiEnum extends Enum implements Iterator
{

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $value = $value instanceof MultiEnum ? $value->get() : $value;
        $value = is_array($value) ? $value : [$value];

        parent::__construct($value);
    }

    /**
     * @param MultiEnum|mixed $value
     * @return bool
     */
    public function equals($value): bool
    {
        $value = $value instanceof static ? $value->get() : $value;
        $value = !is_array($value) ? [$value] : $value;

        return array_intersect($this->value, $value) === $this->value;
    }

    /**
     * @param Enum|mixed $values
     * @return bool
     */
    public function match($values): bool
    {
        $values = $values instanceof Enum ? $values->get() : $values;
        $values = !is_array($values) ? [$values] : $values;
        sort($values);

        return $values === array_intersect($values, $this->value);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->value);
    }

    public function key(): int
    {
        return key($this->value);
    }

    public function next(): void
    {
        next($this->value);
    }

    public function rewind(): void
    {
        reset($this->value);
    }

    public function valid(): bool
    {
        return (bool)current($this->value);
    }

    /**
     * @param mixed $values
     * @return bool
     */
    public static function isValid($values): bool
    {
        $values = is_array($values) ? $values : [$values];
        sort($values);

        return $values === array_intersect($values, self::$constList[static::class]);
    }

    /**
     * Returns imploded values
     *
     * @return string
     */
    public function __toString(): string
    {
        return implode(',', $this->value);
    }
}
