<?php

declare(strict_types=1);

namespace bohyn\Enum;

use bohyn\Enum\fixtures\Test1MultiEnum;
use PHPUnit\Framework\TestCase;

class MultiEnumTest extends TestCase
{

    private const INVALID_VALUE_1 = 10;
    private const INVALID_VALUE_2 = 11;

    /** @var MultiEnum */
    protected $object;

    public function testGet(): void
    {
        $MultiEnum = new Test1MultiEnum(Test1MultiEnum::A);
        $this->assertEquals([Test1MultiEnum::A], $MultiEnum->get());

        $value = [Test1MultiEnum::A, Test1MultiEnum::B];
        $MultiEnum = new Test1MultiEnum($value);
        $this->assertEquals($value, $MultiEnum->get());
    }

    public function testInvalidValue(): void
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage(
            "Unknown enumeration value 'array (
  0 => 10,
)'. Possible values: '1','2','3'"
        );

        new Test1MultiEnum(self::INVALID_VALUE_1);
    }

    public function testInvalidValueArray(): void
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage(
            "Unknown enumeration value 'array (
  0 => 10,
  1 => 11,
)'. Possible values: '1','2','3'"
        );

        new Test1MultiEnum([self::INVALID_VALUE_1, self::INVALID_VALUE_2]);
    }

    public function testPartlyIvalidArray(): void
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage(
            "Unknown enumeration value 'array (
  0 => 1,
  1 => 10,
)'. Possible values: '1','2','3'"
        );

        new Test1MultiEnum([Test1MultiEnum::A, 10]);
    }

    public function testMatch(): void
    {
        $enum = new Test1MultiEnum([Test1MultiEnum::A, Test1MultiEnum::C]);

        $this->assertTrue($enum->match([Test1MultiEnum::A]));
        $this->assertTrue($enum->match([Test1MultiEnum::C]));
        $this->assertTrue($enum->match([Test1MultiEnum::C, Test1MultiEnum::A]));
        $this->assertTrue($enum->match($enum));
        $this->assertFalse($enum->match([Test1MultiEnum::B]));
    }

    public function testEquals(): void
    {
        $enum = new Test1MultiEnum([Test1MultiEnum::A, Test1MultiEnum::C]);

        $this->assertFalse($enum->equals([Test1MultiEnum::A]));
        $this->assertFalse($enum->equals([Test1MultiEnum::C]));
        $this->assertFalse($enum->equals([Test1MultiEnum::C, Test1MultiEnum::B]));
        $this->assertTrue($enum->equals([Test1MultiEnum::C, Test1MultiEnum::A]));
        $this->assertTrue($enum->equals([Test1MultiEnum::A, Test1MultiEnum::C]));
        $this->assertTrue($enum->equals($enum));
    }

    public function testIterator(): void
    {
        $enum = new Test1MultiEnum([Test1MultiEnum::A, Test1MultiEnum::B]);
        $expectedValues = [Test1MultiEnum::A, Test1MultiEnum::B];
        $expectedKeys = [0, 1];
        $values = $keys = [];

        foreach ($enum as $key => $value) {
            $values[] = $value;
            $keys[] = $key;
        }

        $this->assertEquals($expectedValues, $values);
        $this->assertEquals($expectedKeys, $keys);
    }

    public function testToString(): void
    {
        $enum = new Test1MultiEnum([Test1MultiEnum::A, Test1MultiEnum::B]);
        $this->assertEquals('1,2', (string)$enum);
    }
}
