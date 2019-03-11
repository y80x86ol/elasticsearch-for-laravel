<?php

namespace Ken\Elasticsearch;

use Elasticsearch\Client;
use Ken\Elasticsearch\Helper\Constants;
use Ken\Elasticsearch\ResultHandler\BulkResult;
use Ken\Elasticsearch\ResultHandler\DeleteResult;
use Ken\Elasticsearch\ResultHandler\GetResult;
use Ken\Elasticsearch\ResultHandler\IndexResult;
use Ken\Elasticsearch\ResultHandler\MgetResult;
use Ken\Elasticsearch\ResultHandler\SearchResult;
use Ken\Elasticsearch\ResultHandler\UpdateResult;

/**
 * 查询构造器
 *
 * @package Ken\Elasticsearch
 */
class QueryBuilder
{
    /**
     * elasticsearch engine
     *
     * @var ElasticsearchEngine
     */
    private $engine;

    /**
     * 参数构造
     *
     * @var ParamsBuilder
     */
    private $params;

    /**
     * 当前使用的model
     *
     * @var
     */
    protected $model;

    /**
     * debug模式，默认为关闭
     *
     * @var bool
     */
    private $debug = false;

    /**
     * 请求结果状态，1=成功，0=失败
     *
     * @var int
     */
    private $status = Constants::RESULT_SUCCESS;

    /**
     * 模型初始化属性
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->params = new ParamsBuilder($model->searchIndexAs, $model->searchTypeAs, $model->queryJson);

        $this->engine = resolve(ElasticsearchEngine::class);
    }

    /**
     * 设置为debug模式
     *
     * @return $this
     */
    public function debug(): self
    {
        $this->params->setDebug();
        $this->debug = true;

        return $this;
    }

    /**
     * 设置索引
     *
     * @param string $index
     * @return $this
     */
    public function setIndex(string $index): self
    {
        $this->params->setType($index);

        return $this;
    }

    /**
     * 设置类型
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->params->setType($type);

        return $this;
    }

    /**
     * 获取原始的es驱动，用于特殊情况直接调用程序
     *
     * @return \Elasticsearch\Client
     */
    public function engine(): Client
    {
        try {
            $params = $this->params->getSourceParams();

            $this->engine->setParams($params);

            return $this->engine;
        } catch (\Exception $exception) {
            return json_decode($exception->getMessage(), true);
        }
    }

    /**
     * 搜索
     *
     * @return SearchResult
     */
    public function search(): SearchResult
    {
        try {
            $params = $this->params->getSearchParams();

            $result = $this->engine->elastic->search($params);
        } catch (\Exception $exception) {
            $result = $this->handleErrorMessage($exception->getMessage());
        }

        return new SearchResult($result, $this->status, $this->debug);
    }

    /**
     * 索引一篇文档
     *
     * @return bool
     */
    public function index(): array
    {
        try {
            $params = $this->params->getIndexParams();

            $result = $this->engine->elastic->index($params);
        } catch (\Exception $exception) {
            $result = $this->handleErrorMessage($exception->getMessage());
        }

        return (new IndexResult($result, $this->status, $this->debug))->get();
    }

    /**
     * 批量索引文档
     *
     * @return bool
     */
    public function bulk(): array
    {
        try {
            $params = $this->params->getBulkParams();

            $result = $this->engine->elastic->bulk($params);
        } catch (\Exception $exception) {
            $result = $this->handleErrorMessage($exception->getMessage());
        }

        return (new BulkResult($result, $this->status, $this->debug))->get();
    }

    /**
     * 获得一篇文档
     *
     * @return array
     */
    public function get(): array
    {
        try {
            $params = $this->params->getDocParams();

            $result = $this->engine->elastic->get($params);
        } catch (\Exception $exception) {
            $result = $this->handleErrorMessage($exception->getMessage());
        }

        return (new GetResult($result, $this->status, $this->debug))->get();
    }

    /**
     * 获得多篇文档：search
     *
     * @return array
     */
    public function gets(): array
    {
        try {
            $params = $this->params->getDocsParams();

            $result = $this->engine->elastic->search($params);
        } catch (\Exception $exception) {
            $result = $this->handleErrorMessage($exception->getMessage());
        }

        return (new SearchResult($result, $this->status, $this->debug))->getHits();
    }

    /**
     * 获得多篇文档：mget
     *
     * 推荐使用mget方式，这种方式获取效率高
     *
     * @return array
     */
    public function mget(): array
    {
        try {
            $params = $this->params->getMgetParams();

            $result = $this->engine->elastic->mget($params);
        } catch (\Exception $exception) {
            $result = $this->handleErrorMessage($exception->getMessage());
        }

        return (new MgetResult($result, $this->status, $this->debug))->get();
    }

    /**
     * 更新一篇文档
     *
     * @return bool
     */
    public function update(): array
    {
        try {
            $params = $this->params->getUpdateParams();

            $result = $this->engine->elastic->update($params);
        } catch (\Exception $exception) {
            $result = $this->handleErrorMessage($exception->getMessage());
        }

        return (new UpdateResult($result, $this->status, $this->debug))->get();
    }

    /**
     * 删除一篇文档
     *
     * @return bool
     */
    public function delete(): array
    {
        try {
            $params = $this->params->getDeleteParams();

            $result = $this->engine->elastic->delete($params);
        } catch (\Exception $exception) {
            $result = $this->handleErrorMessage($exception->getMessage());
        }

        return (new DeleteResult($result, $this->status, $this->debug))->get();
    }

    /**
     * 处理错误返回的结果
     *
     * @param string $message
     * @return array
     */
    private function handleErrorMessage(string $message): array
    {
        $result = json_decode($message, true);
        if (!is_array($result)) {
            $result = [$message];
        }
        $this->status = Constants::RESULT_ERROR;

        return $result;
    }

    /**
     * 批量导入数据
     *
     * @param array $ids
     * @return array
     */
    public function import(array $ids = []): array
    {
        $relationModel = $this->model->relationModel;

        //获取需要导入的数据对象
        if (count($ids) > 0) {
            $modelList = (new $relationModel)->whereIn('id', $ids)->get();
        } else {
            $modelList = (new $relationModel)->get();
        }

        //相关总数和最大值统计
        $maxId = 0;
        $count = $modelList ? count($modelList->toArray()) : 0;
        if ($count > 0) {
            $lastModel = $modelList[$count - 1];
            $maxId = $lastModel->id;
        }

        $result = [
            'total' => $count,
            'max_id' => $maxId,
            'error' => false,
            'message' => 'import success'
        ];

        try {
            //单个循环执行导入
            foreach ($modelList as $model) {
                $this->model->model = $model;

                $params = $this->params->getImportParams();
                $params['body'] = $this->model->toSearchableArray();

                $params['id'] = $params['body']['id'];

                $this->engine->elastic->index($params);
            }
        } catch (\Exception $exception) {
            $result['error'] = true;
            $result['message'] = $exception->getMessage();
        }

        return $result;
    }

    /**
     * 索引一篇文档
     *
     * @param int $id
     * @return array
     */
    public function importOne(int $id = 0): array
    {
        $result = [
            'total' => 1,
            'max_id' => $id,
            'error' => false,
            'message' => 'index fail'
        ];

        $relationModel = $this->model->relationModel;

        $model = (new $relationModel)->where('id', $id)->first();

        if (!$model) {
            $result['error'] = true;
            $result['message'] = 'it is not exist：' . $id;
            return $result;
        }

        try {
            $this->model->model = $model;

            $params = $this->params->getImportParams();
            $params['body'] = $this->model->toSearchableArray();

            $params['id'] = $params['body']['id'];

            $this->engine->elastic->index($params);
        } catch (\Exception $exception) {
            $result['error'] = true;
            $result['message'] = $exception->getMessage();
        }

        return $result;
    }
}