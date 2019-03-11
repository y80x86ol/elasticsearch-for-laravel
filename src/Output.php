<?php

namespace Ken\Elasticsearch;

use Ken\Elasticsearch\Helper\Constants;

/**
 * 输出处理类
 *
 * @package Ken\Elasticsearch
 */
final class Output
{
    /**
     * 状态码
     *
     * @var int
     */
    private $code = Constants::RESULT_SUCCESS;

    /**
     * 内容
     *
     * @var array
     */
    private $data = [];

    /**
     * 消息
     *
     * @var string
     */
    private $message = 'success';

    /**
     * 初始化
     *
     * @param int $code
     * @param array $data
     * @param string $message
     */
    public function __construct(int $code = Constants::RESULT_SUCCESS, array $data = [], string $message = 'success')
    {
        $this->code = $code;
        $this->data = $data;
        $this->message = $message;
    }

    /**
     * 设置状态码
     *
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->code = $code;
    }

    /**
     * 设置数据
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * 设置错误消息
     *
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * 输出为json
     *
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->getAllData());
    }

    /**
     * 输出为数组
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getAllData();
    }

    /**
     * 获取所有数据
     *
     * @return array
     */
    private function getAllData()
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}