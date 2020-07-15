<?php

declare(strict_types=1);

namespace bohyn\Enum;

use bohyn\Enum\fixtures\Test1Enum;
use bohyn\Enum\fixtures\Test2Enum;
use PHPStan\Testing\TestCase;

class EnumTest extends TestCase
{

    private const INVALID_VALUE = 10;

    public function testGet(): void
    {
        $test1 = new Test1Enum(Test1Enum::A);
        $this->assertEquals(Test1Enum::A, $test1->get());

        $test2 = new Test2Enum(Test2Enum::Z);
        $this->assertEquals(Test2Enum::Z, $test2->get());
    }

    public function testInvalidValue(): void
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage(
            "bohyn\\Enum\\fixtures\\Test1Enum: Unknown enumeration value '10'. Possible values: '1','2','3'"
        );

        new Test1Enum(self::INVALID_VALUE);
    }

    public function testIsValid(): void
    {
        $this->assertTrue(Test1Enum::isValid(Test1Enum::A));
        $this->assertTrue(Test1Enum::isValid(Test1Enum::B));
        $this->assertTrue(Test1Enum::isValid(Test1Enum::C));
        $this->assertFalse(Test1Enum::isValid(self::INVALID_VALUE));
    }

    public function testGetValidValues(): void
    {
        $this->assertEquals(Test1Enum::getValidValues(), [Test1Enum::A, Test1Enum::B, Test1Enum::C]);
    }

    public function testEquals(): void
    {
        $enum = new Test1Enum(Test1Enum::A);
        $this->assertTrue($enum->equals(Test1Enum::A));
        $this->assertTrue($enum->equals($enum));
        $this->assertFalse($enum->equals(Test1Enum::C));
        $this->assertFalse($enum->equals(self::INVALID_VALUE));
    }

    public function testEqualsAny(): void
    {
        $enum1 = new Test1Enum(Test1Enum::A);
        $enum2 = new Test1Enum(Test1Enum::A);
        $enum3 = new Test1Enum(Test1Enum::B);

        $result = $enum1->equalsAny([self::INVALID_VALUE, Test1Enum::A, Test1Enum::B]);
        $this->assertTrue($result);

        $result = $enum1->equalsAny([self::INVALID_VALUE, Test1Enum::B, Test1Enum::C]);
        $this->assertFalse($result);

        $result = $enum1->equalsAny([self::INVALID_VALUE, $enum2, Test1Enum::C]);
        $this->assertTrue($result);

        $result = $enum1->equalsAny([self::INVALID_VALUE, $enum3, Test1Enum::C]);
        $this->assertFalse($result);
    }

    public static function setUpBeforeClass(): void
    {
        require __DIR__ . '/fixtures/Test1Enum.php';
        require __DIR__ . '/fixtures/Test2Enum.php';
    }
}
