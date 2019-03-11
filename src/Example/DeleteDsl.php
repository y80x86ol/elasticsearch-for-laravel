<?php

namespace Ken\Elasticsearch\Example;


use Ken\Elasticsearch\Query;

/**
 * 删除文档
 *
 * @package Ken\Elasticsearch\Example
 */
class DeleteDsl extends Query
{
    public function query()
    {
        return [
            "id" => '3'
        ];
    }
}