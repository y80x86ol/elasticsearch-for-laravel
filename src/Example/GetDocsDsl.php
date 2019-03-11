<?php

namespace Ken\Elasticsearch\Example;


use Ken\Elasticsearch\Query;

/**
 * 多个文档查询
 *
 * @package Ken\Elasticsearch\Example
 */
class GetDocsDsl extends Query
{
    public function query()
    {
        return [
            "ids" => [2, 3]
        ];
    }
}