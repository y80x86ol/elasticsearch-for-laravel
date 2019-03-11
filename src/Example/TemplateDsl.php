<?php

namespace Ken\Elasticsearch\Example;


use Ken\Elasticsearch\Query;

class TemplateDsl extends Query
{
    public function query()
    {
        return [
            'name' => 'app_template',
            'body' => [
                'index_patterns' => config('elasticsearch.index_pre') . '*',
                'mappings' => [
                    '_default_' => [
                        'dynamic_templates' => [
                            [
                                'strings' => [
                                    'match_mapping_type' => 'string',
                                    'mapping' => [
                                        'type' => 'text',
                                        'analyzer' => 'ik_max_word',
                                        'fields' => [
                                            'keyword' => [
                                                'type' => 'keyword'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        ];
    }
}