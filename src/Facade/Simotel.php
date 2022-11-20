<?php

namespace Simotel\Laravel\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class Payment
 *
 * @package Shetabit\Payment\Facade
 */
class Simotel extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'simotel-laravel-connect';
    }
}
