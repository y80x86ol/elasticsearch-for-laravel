<?php

namespace Ken\Elasticsearch\Example;


use Ken\Elasticsearch\Query;

/**
 * 多个文档查询
 *
 * @package Ken\Elasticsearch\Example
 */
class MgetDsl extends Query
{
    /**
     * 有两种查询方法：
     *
     * 方法一：
     *
     * *return [
     *            "body" => [
     *                "ids" => [2, 3]
     *            ]
     *        ];
     *
     * 方法二：
     *return [
     *            "body" => [
     *                "docs" => [
     *                    [
     *                        "_id" => 2
     *                    ],
     *                    [
     *                        "_id" => 3
     *                    ]
     *                ]
     *            ]
     *        ];
     *
     * @return array|mixed
     */
    public function query()
    {
        return [
            "body" => [
                "ids" => [9, 10]
            ]
        ];
    }
}