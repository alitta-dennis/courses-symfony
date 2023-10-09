<?php

namespace App\tests\Unit\oneTest;

use PHPUnit\Framework\TestCase;

class oneTest extends TestCase
{
    public function testWorks():void
    {
        self::assertEquals(expected:42,actual:42);
    }
}