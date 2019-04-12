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
     * 过滤
     *
     * @var array
     */
    protected $filter = [];

    /**
     * 排序
     *
     * @var array
     */
    protected $sort = [];

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
     * 条件
     *
     * @param string $field 条件字段
     * @param string|array $value 条件值
     * @return $this
     */
    public function where(string $field, $value)
    {
        if (is_array($value)) {
            $this->filter[] = [
                "terms" => [
                    $field => $value
                ]
            ];
        } else {
            $this->filter[] = [
                "term" => [
                    $field => $value
                ]
            ];
        }

        return $this;
    }

    /**
     * 范围
     *
     * @param string $field 条件字段
     * @param string $flag 操作
     * @param string $value 条件值
     * @return $this
     */
    public function range(string $field, string $flag, $value)
    {
        $this->filter[] = [
            "range" => [
                $field => [
                    $flag => $value
                ]
            ]
        ];

        return $this;
    }

    /**
     * 排序
     *
     * @param string $column 排序的字段
     * @param string $direction 排序规则
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'asc')
    {
        $this->sort[] = [
            $column => [
                "order" => $direction
            ]
        ];

        return $this;
    }

    /**
     * 必须的查询方法
     *
     * @return mixed
     */
    abstract public function query();
}