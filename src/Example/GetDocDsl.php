<?php

namespace Ken\Elasticsearch\Example;


use Ken\Elasticsearch\Query;

/**
 * 单个文档查询
 *
 * @package Ken\Elasticsearch\Example
 */
class GetDocDsl extends Query
{
    public function query()
    {
        return [
            "id" => '8'
        ];
    }
}