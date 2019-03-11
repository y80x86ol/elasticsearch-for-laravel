<?php

namespace Ken\Elasticsearch\Example;


use Ken\Elasticsearch\Model;

/**
 * 测试模型
 *
 * @package Ken\Elasticsearch
 */
class ExampleModel extends Model
{
    /**
     * 搜索的索引
     *
     * @var string
     */
    public $searchIndexAs = 'example';

    /**
     * 搜索的类型
     *
     * @var string
     */
    public $searchTypeAs = 'example';

    /**
     * 关联模型
     *
     * @var string
     */
    public $relationModel = StudentModel::class;

    /**
     * 定义有哪些字段需要搜索
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = [
            'id' => $this->model->id,
            'name' => $this->model->name,
            'desc' => $this->model->desc,
            'grade' => $this->model->grade,
            'age' => $this->model->age,
            'score' => $this->model->score,
        ];

        return $array;
    }
}