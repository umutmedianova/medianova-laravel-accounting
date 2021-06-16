<?php
namespace Medianova\LaravelAccounting\Test;

use Medianova\LaravelAccounting\Facades\Accounting;
use Medianova\LaravelAccounting\LaravelAccountingServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return Medianova\LaravelAccounting\LaravelAccountingServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [LaravelAccountingServiceProvider::class];
    }
    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'accounting' => Accounting::class,
        ];
    }
}
