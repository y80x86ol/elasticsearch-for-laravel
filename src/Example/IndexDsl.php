<?php

namespace Ken\Elasticsearch\Example;


use Ken\Elasticsearch\Query;

/**
 * 索引一篇文档
 *
 * @package Ken\Elasticsearch\Example
 */
class IndexDsl extends Query
{
    public function query()
    {
        return [
            'id' => '11',
            'body' => [
                'id' => 11,
                'name' => 'li zhe ming',
                'desc' => 'i am li zhe ming,i come from chengdu',
                'grade' => 3,
                'age' => 12,
                'score' => 88
            ]
        ];
    }
}