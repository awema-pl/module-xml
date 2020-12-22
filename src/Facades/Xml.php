<?php

namespace AwemaPL\Xml\Facades;

use AwemaPL\Xml\Contracts\Xml as XmlContract;
use Illuminate\Support\Facades\Facade;

class Xml extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return XmlContract::class;
    }
}
