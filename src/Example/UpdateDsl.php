<?php

namespace Ken\Elasticsearch\Example;


use Ken\Elasticsearch\Query;

/**
 * 更新文档
 *
 * @package Ken\Elasticsearch\Example
 */
class UpdateDsl extends Query
{
    /**
     * 支持局部更新，脚本更新
     *
     * 局部更新：
     *
     * return [
     *       'id' => '5',
     *       'body' => [
     *           'doc' => [
     *               'name' => '我是部分更新内容'
     *           ]
     *       ]
     *    ];
     *
     * 脚本更新：
     *return [
     *    'id' => '5',
     *        'body' => [
     *            'script' => 'ctx._source.counter += count',
     *            'params' => [
     *                'count' => 4
     *            ]
     *        ]
     *    ];
     *
     *
     * @return array|mixed
     */
    public function query()
    {
        return [
            'id' => '5',
            'body' => [
                'doc' => [
                    'desc' => 'i am live up now'
                ]
            ]
        ];
    }
}