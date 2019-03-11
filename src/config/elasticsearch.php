<?php

/**
 * elasticsearch配置文件
 */
return [
    /**
     * 索引前缀
     */
    'index_pre' => env('ELASTICSEARCH_INDEX', 'elasticsearch'),

    /**
     * 索引列表
     *
     * 构造格式为index_pre + index_list
     *
     * 支持多个索引，使用,号隔开
     */
    'index_list' => explode(',', env('ELASTICSEARCH_INDEX_LIST', 'example')),

    /**
     * 主机
     *
     * 支持多个主机，使用,号隔开
     *
     * 默认方式：
     * http://localhost:9200
     *
     * 认证方式：
     * http://user:pass@localhost:9200
     */
    'hosts' => explode(',', env('ELASTICSEARCH_HOST', 'http://127.0.0.1:9200')),

    /**
     * 是否开启https，默认未开启
     */
    'ssl' => [
        'enable' => env('ELASTICSEARCH_SSL_ENABLE', false),
        'cert_path' => env('ELASTICSEARCH_SSL_CERT_PATH', '')
    ],

    /**
     * 日志配置，默认为关闭
     *
     * devel等级使用logger函数的等级
     */
    'log' => [
        'enable' => env('ELASTICSEARCH_LOG_ENABLE', false),
        'path' => env('ELASTICSEARCH_LOG_PATH', 'storage/logs'),
        'devel' => env('ELASTICSEARCH_LOG_DEVEL', 'NOTICE')
    ],

    /**
     * 失败重置连接次数，默认为5次
     */
    'retries' => env('ELASTICSEARCH_RETRIES', 5),

    /**
     * 获取数据超时设置，默认不超时，单位：秒
     */
    'timeout' => env('ELASTICSEARCH_TIMEOUT', 0),

    /**
     * 连接es超时设置，默认不超时，单位：秒
     */
    'connect_timeout' => env('ELASTICSEARCH_CONNECT_TIMEOUT', 0),

    /**
     * 批量导入的模型列表
     */
    'import_models' => [
        'Ken\Elasticsearch\Example\ExampleModel'
    ],

    /**
     * 初始化的template和index
     */
    'init' => [
        'templates' => [
            'Ken\Elasticsearch\Example\TemplateDsl'
        ],
        'indexs' => [
            'Ken\Elasticsearch\Example\TemplateIndexDsl'
        ]
    ],
];
