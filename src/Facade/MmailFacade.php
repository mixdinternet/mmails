<?php

namespace Mixdinternet\Mmails\Facade;

use Illuminate\Support\Facades\Facade;

class MmailFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mmail'; #registrado no service provider
    }
}