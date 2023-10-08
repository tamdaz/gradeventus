<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Enumeration\CourseColor;

class CourseColorTest extends TestCase
{
    public function testRedIsRed(): void
    {
        self::assertEquals("red", CourseColor::RED->value);
    }

    public function testRedIsNotOrange(): void
    {
        self::assertNotEquals("red", CourseColor::ORANGE->value);
    }
}
