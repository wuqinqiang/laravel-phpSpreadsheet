<?php

namespace Remember\LaravelPhpSpreadsheet;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Remember\LaravelPhpSpreadsheet\Skeleton\SkeletonClass
 */
class LaravelPhpSpreadsheetFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-phpSpreadsheet';
    }
}
