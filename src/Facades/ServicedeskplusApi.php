<?php

namespace Onbalt\ServicedeskplusApi\Facades;

use Illuminate\Support\Facades\Facade;

class ServicedeskplusApi extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'servicedeskplus-api';
    }
}
