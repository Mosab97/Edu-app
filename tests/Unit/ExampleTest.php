<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testBasicTest(){
        dd(collect([1,2,3,4,5,6])->random());
        $this->assertTrue(true);

    }
}
