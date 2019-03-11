<?php

namespace Ken\Elasticsearch\Example;


use Ken\Elasticsearch\Query;

/**
 * 批量索引文档
 *
 * @package Ken\Elasticsearch\Example
 */
class BulkDsl extends Query
{
    public function query()
    {
        return [
            [
                'id' => '12',
                'body' => [
                    'id' => 12,
                    'name' => 'wu di',
                    'desc' => 'i am wu di,i come from china',
                    'grade' => 3,
                    'age' => 11,
                    'score' => 80
                ]
            ],
            [
                'id' => '13',
                'body' => [
                    'id' => 13,
                    'name' => 'wu qiang',
                    'desc' => 'i am wu qiang,i come from china',
                    'grade' => 3,
                    'age' => 12,
                    'score' => 87
                ]
            ]
        ];
    }
}