<?php

namespace Ken\Elasticsearch\Example;

use Ken\Elasticsearch\Query;

/**
 * 全文检索查询
 *
 * @package Ken\Elasticsearch\Example
 */
class SearchDsl extends Query
{
    public function query()
    {
        return [
            "body" => [
                "query" => [
                    "match" => [
                        "name" => $this->params['name']
                    ]
                ]
            ]
        ];
    }
}