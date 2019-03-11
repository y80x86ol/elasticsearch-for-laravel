<?php

namespace Ken\Elasticsearch\Facades;

use \Illuminate\Support\Facades\Facade;

class Elasticsearch extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'elasticsearch';
    }
}