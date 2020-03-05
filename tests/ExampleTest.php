<?php

namespace Remember\LaravelPhpSpreadsheet\Tests;

use Orchestra\Testbench\TestCase;
use Remember\LaravelPhpSpreadsheet\LaravelPhpSpreadsheetServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelPhpSpreadsheetServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
