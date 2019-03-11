<?php

namespace Ken\Elasticsearch\Example;

use Ken\Elasticsearch\Query;

/**
 * 聚合查询
 *
 * @package Ken\Elasticsearch\Example
 */
class AggregationDsl extends Query
{
    public function query()
    {
        return [
            "body" => [
                "size" => 0,
                "aggs" => [
                    "order_by_grade" => [
                        "terms" => [
                            "field" => "grade"
                        ]
                    ]
                ]
            ]
        ];
    }
}