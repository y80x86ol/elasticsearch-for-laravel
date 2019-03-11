<?php

namespace Ken\Elasticsearch;

use Elasticsearch\Client;

/**
 * Elasticsearch 驱动
 *
 * @package Ken\Elasticsearch
 */
class ElasticsearchEngine
{
    /**
     * Elasticsearch 客户端
     *
     * @var \Elasticsearch\Client
     */
    public $elastic;

    /**
     * 执行参数
     *
     * @var
     */
    public $params = [];

    /**
     * 初始化Elasticsearch客户端
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->elastic = $client;
    }

    /**
     * 设置参数
     *
     * @param $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * 获取参数
     *
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * 返回elastic对象
     *
     * @return Client
     */
    public function elastic()
    {
        return $this->elastic;
    }
}
