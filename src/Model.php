<?php

namespace Ken\Elasticsearch;

/**
 * Elasticsearch Model
 *
 * @package Ken\Elasticsearch
 */
abstract class Model
{
    /**
     * 查询参数
     *
     * @var ParamsBuilder
     */
    public $queryJson = [];

    /**
     * 关联的laravel模型
     *
     * @var
     */
    public $model;

    /**
     * 当前模型的索引index名字
     *
     * @var string
     */
    public $searchIndexAs = '';

    /**
     * 当前模型的类型type名字
     *
     * @var string
     */
    public $searchTypeAs = '';

    /**
     * 关联的laravel模型
     *
     * 在批量导入数据的时候需要做数据填充
     *
     * @var string
     */
    public $relationModel = '';

    /**
     * 设置当前模型的搜索数据
     *
     * @return array
     */
    public abstract function toSearchableArray();

    /**
     * 动态魔术方法
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->$method(...$parameters);
    }

    /**
     * 静态魔术方法
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    /**
     * 设置查询语句
     *
     * @param string $queryClass
     * @param mixed $queryParams
     * @return QueryBuilder
     */
    public static function query(string $queryClass = '', ...$queryParams): QueryBuilder
    {
        $query = new static();
        if ($queryClass) {
            $queryDsl = new $queryClass(...$queryParams);
            $queryJson = $queryDsl->apply();

            $query->queryJson = $queryJson;
        }

        return new QueryBuilder($query);
    }

    /**
     * 设置查询数组
     *
     * 和query功能一样，只是为了方便查询
     *
     * @param array $queryArray
     * @return QueryBuilder
     */
    public static function queryArray(array $queryArray = []): QueryBuilder
    {
        $query = new static();

        $query->queryJson = $queryArray;

        return new QueryBuilder($query);
    }

    /*
     * 批量导入数据
     *
     * 数据量太大的话不建议直接使用该方法导入
     *
     * @param array $ids
     * @return array
     */
    public static function import(array $ids = []): array
    {
        return (new QueryBuilder(new static()))->import($ids);
    }

    /**
     * 导入单个数据
     *
     * @param int $id
     * @return array
     */
    public static function index(int $id = 0): array
    {
        return (new QueryBuilder(new static()))->importOne($id);
    }
}