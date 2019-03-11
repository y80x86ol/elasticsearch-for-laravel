<?php

namespace Ken\Elasticsearch\Example;


use Ken\Elasticsearch\Query;

/**
 * 搜索过滤查询
 *
 * @package Ken\Elasticsearch\Example
 */
class SearchFilterDsl extends Query
{
    public function query()
    {
        return [
            "body" => [
                "query" => [
                    "bool" => [
                        "must" => [
                            "match" => [
                                "name" => "jack"
                            ]
                        ],
                        "filter" => [
                            "term" => [
                                "grade" => 1
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}