<?php

namespace Appel\Honeypot\Facades;

use Illuminate\Support\Facades\Facade;

class Honeypot extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'honeypot';
    }
}