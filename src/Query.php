<?php

namespace Ken\Elasticsearch;

/**
 * Elasticsearch Query
 *
 * 使用 Query DSL方式
 *
 * @package Ken\Elasticsearch
 */
abstract class Query
{
    /**
     * 查询参数
     *
     * @var array
     */
    protected $params = [];

    /**
     * 设置参数
     *
     * @param array $params
     */
    public function setParams(array $params = []): void
    {
        $this->params = $params;
    }

    /**
     * 外部执行调用方法
     *
     * @return mixed
     */
    public function apply(): array
    {
        return $this->query();
    }

    /**
     * 必须的查询方法
     *
     * @return mixed
     */
    abstract public function query();
}