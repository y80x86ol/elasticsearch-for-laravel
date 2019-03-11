<?php

namespace Ken\Elasticsearch\ResultHandler;

use Ken\Elasticsearch\Helper\Constants;
use Ken\Elasticsearch\Output;

/**
 * 操作结果
 *
 * @package Ken\Elasticsearch\ResultHandler
 */
class ResultHandler
{
    /**
     * 查询结果
     *
     * @var array
     */
    protected $result = [];

    /**
     * 结果状态 200=成功，400=失败
     *
     * @var int
     */
    protected $status = Constants::RESULT_SUCCESS;

    /**
     * 是否开启debug模式，默认不开启
     *
     * @var bool
     */
    private $debug = false;

    /**
     * 输出
     *
     * @var Output|null
     */
    protected $output = null;

    /**
     * 构造函数
     *
     * @param array $result
     * @param int $status
     * @param bool $debug
     */
    public function __construct(array $result, int $status = 1, bool $debug = false)
    {
        $this->result = $result;
        $this->status = $status;
        $this->debug = $debug;

        $this->output = new Output();
    }

    /**
     * 返回所有结果
     *
     * @return array
     */
    public function getAll(): array
    {
        $this->output->setData($this->getSource());

        return $this->output->toArray();
    }

    /**
     * 获取Elasticsearch原始查询结果
     *
     * @return array
     */
    protected function getSource(): array
    {
        $source = $this->checkResult($this->result);

        return $source;
    }

    /**
     * 检查结果的正确与否
     *
     * @param array $source
     * @return array $source
     */
    protected function checkResult(array $source): array
    {
        if ($this->status == Constants::RESULT_SUCCESS) {
            if (isset($source['status']) && $source['status'] == 404) {
                $this->output->setCode(Constants::RESULT_SUCCESS);
                $this->output->setMessage(json_encode($source));
            } else {
                $this->output->setData($source);
            }
        } else {
            if (is_array($source)) {
                $this->output->setCode(Constants::RESULT_ERROR);
                $this->output->setMessage(json_encode($source));
            } else {
                $this->output->setCode(Constants::RESULT_ERROR);
                $this->output->setMessage($source[0] ? $source[0] : '');
            }
        }

        return $source;
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        if ($this->debug) {
            print_r("========== result ==========\r\n");
            print_r($this->getSource());
        }
    }
}