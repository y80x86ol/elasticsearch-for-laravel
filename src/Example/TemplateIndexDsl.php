<?php

namespace Ken\Elasticsearch\Example;


use Ken\Elasticsearch\Query;

class TemplateIndexDsl extends Query
{
    public function query()
    {
        $index_pre = config('elasticsearch.index_pre');
        $index_list = config('elasticsearch.index_list');

        $list = [];
        foreach ($index_list as $index) {
            $list[] = [
                'index' => $index_pre . '_' . $index,
                'body' => [
                    'settings' => [
                        'refresh_interval' => '30s', //索引了数据支持多少时间能搜索到
                        'number_of_shards' => 5, //数据分片数，使用默认
                        'number_of_replicas' => 1, //数据备份数，使用默认
                    ],
                    'mappings' => [
                        '_default_' => [
                            '_all' => [
                                'enabled' => false
                            ]
                        ]
                    ]
                ]
            ];
        }

        return $list;
    }
}