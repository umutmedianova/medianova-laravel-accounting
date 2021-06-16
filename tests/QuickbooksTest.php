<?php

namespace Medianova\LaravelAccounting\Test;
use Medianova\LaravelAccounting\Facades\Accounting;

class QuickbooksTest extends TestCase
{
    /**
     * Create
     * @return void
     */
    public function testCreate()
    {
        $response = Accounting::create([]);
        $this->assertFalse($response);
    }
}
