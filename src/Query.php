<?php

namespace Ken\Elasticsearch;

/**
 * Elasticsearch Query
 *
 * 使用 Query DSL方式，这里简单的封装了查询构造器，只能满足基础的使用，下一个版本将会加强这一块的功能
 *
 * @package Ken\Elasticsearch
 */
abstract class Query
{
    /**
     * 每页查询多少
     */
    const QUERY_SIZE = 10;

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
     * 分页
     *
     * @var int
     */
    protected $page = 0;

    /**
     * 从哪条开始查询
     *
     * @var int
     */
    protected $from = 10;

    /**
     * 每页展示多少条
     *
     * @var int
     */
    protected $size = 10;

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
     * 分页
     *
     * @param int $page 当前页码
     * @param int $size 每页展示多少条
     * @return $this
     */
    public function paginate(int $page, int $size = self::QUERY_SIZE)
    {
        $this->size = $size;

        if ($page == 0 || $page == 1) {
            $this->from = 0;
        } else {
            $this->from = ($page - 1) * $this->size;
        }

        return $this;
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