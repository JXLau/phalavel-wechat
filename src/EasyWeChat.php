<?php

namespace Phalavel\Wechat;

use Illuminate\Support\Facades\Facade;

class EasyWeChat extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return app('wechat');
    }
}