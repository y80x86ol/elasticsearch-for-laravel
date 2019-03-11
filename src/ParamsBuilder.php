<?php

namespace Ken\Elasticsearch;

/**
 * 参数构造器
 *
 * @package Ken\Elasticsearch
 */
class ParamsBuilder
{
    /**
     * 索引前缀
     *
     * @var string
     */
    private $indexPre = '';

    /**
     * 索引
     *
     * @var string
     */
    private $index = '';

    /**
     * 类型
     *
     * @var string
     */
    private $type = '';

    /**
     * 模型搜索的索引
     *
     * @var string
     */
    private $searchIndexAs = '';

    /**
     * 模型搜索的类型
     *
     * @var string
     */
    private $searchTypeAs = '';

    /**
     * 查询数组
     *
     * @var
     */
    private $query;

    /**
     * 完整的参数
     *
     * @var array
     */
    private $fullParams = [];

    /**
     * 是否开启debug模式，默认不开启
     *
     * @var bool
     */
    private $debug = false;

    /**
     * ParamsBuilder初始化
     *
     * @param string $searchIndexAs
     * @param string $searchTypeAs
     * @param array $queryJson
     */
    public function __construct(string $searchIndexAs, string $searchTypeAs, array $queryJson)
    {
        $this->searchIndexAs = $searchIndexAs;
        $this->searchTypeAs = $searchTypeAs;
        $this->query = $queryJson;

        $this->indexPre = config('elasticsearch.index_pre');
    }

    /**
     * 设置为debug模式
     */
    public function setDebug()
    {
        $this->debug = true;
    }

    /**
     * 设置索引
     *
     * @param string $index
     */
    public function setIndex($index = '')
    {
        $this->index = $index;
    }

    /**
     * 设置类型
     *
     * @param string $type
     */
    public function setType($type = '')
    {
        $this->type = $type;
    }

    /**
     * 获得搜索的索引index
     *
     * @return string
     */
    private function getIndex(): string
    {
        return $this->index ? $this->index : ($this->indexPre . '_' . $this->searchIndexAs);
    }

    /**
     * 获得搜索的类型type
     *
     * @return string
     */
    private function getType(): string
    {
        return $this->type ? $this->type : $this->searchTypeAs;
    }

    /**
     * 获取完整的参数
     *
     * 用于debug
     *
     * @return array
     */
    public function getDebugParams(): array
    {
        return $this->fullParams;
    }

    /**
     * 获得查询参数
     *
     * @return array
     */
    public function getSearchParams(): array
    {
        $params = [
            'body' => $this->query['body'] ?? []
        ];

        return $this->getFullParams($params);
    }

    /**
     * 索引单个文档参数
     *
     * @return array
     */
    public function getIndexParams(): array
    {
        $params = [
            'body' => $this->query['body'] ?? []
        ];

        if (isset($this->query['id'])) {
            $params['id'] = $this->query['id'];
        }

        return $this->getFullParams($params);
    }

    /**
     * 批量索引文档参数
     *
     * 目前只支持批量索引一个指定的索引和类型
     *
     * 支持批量更新、批量删除、批量查询
     *
     * @return array
     */
    public function getBulkParams(): array
    {
        $params = [];
        foreach ($this->query as $query) {
            $params['body'][] = [
                "index" => [
                    '_index' => $this->getIndex(),
                    '_type' => $this->getType(),
                    '_id' => $query['id']
                ]
            ];
            $params['body'][] = $query['body'];
        }

        $this->fullParams = $params;

        return $params;
    }

    /**
     * 获得索引的一个文档参数
     *
     * @return array
     */
    public function getDocParams(): array
    {
        $params['id'] = $this->query['id'] ?? 0;

        return $this->getFullParams($params);
    }

    /**
     * 获得索引的多个文档参数
     *
     * @return array
     */
    public function getDocsParams(): array
    {
        $params = [
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'terms' => [
                                'id' => $this->query['ids'] ?? []
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $this->getFullParams($params);
    }

    /**
     * 获得索引的多个文档参数
     *
     * @return array
     */
    public function getMgetParams(): array
    {
        $params['body'] = $this->query['body'] ?? [];

        return $this->getFullParams($params);
    }

    /**
     * 获得更新文档参数
     *
     * @return array
     */
    public function getUpdateParams(): array
    {
        $params = [
            'body' => $this->query['body'] ?? []
        ];

        if (isset($this->query['id'])) {
            $params['id'] = $this->query['id'];
        }

        return $this->getFullParams($params);
    }

    /**
     * 删除一个文档参数
     *
     * @return array
     */
    public function getDeleteParams(): array
    {
        $params['id'] = $this->query['id'] ?? 0;

        return $this->getFullParams($params);
    }

    /**
     * 获取数据导入的参数
     *
     * @return array
     */
    public function getImportParams(): array
    {
        return $this->getFullParams();
    }

    /**
     * 获取模板参数
     *
     * @return array
     */
    public function getTemplateParams(): array
    {
        $params = [
            'body' => $this->query['body'] ?? []
        ];

        return $this->getFullParams($params);
    }

    /**
     * 获得完整的查询参数
     *
     * @param array $params
     * @return array
     */
    private function getFullParams(array $params = []): array
    {
        $fullParams = array_merge($this->getBaseParams(), $params);

        $this->fullParams = $fullParams;

        return $fullParams;
    }

    /**
     * 获取查询的索引和类型参数
     *
     * @return array
     */
    private function getBaseParams(): array
    {
        $baseParams = [
            'index' => $this->getIndex(),
            'type' => $this->getType()
        ];

        if (config('elasticsearch.timeout')) {
            $baseParams['client']['timeout'] = config('elasticsearch.timeout');
        }

        if (config('elasticsearch.connect_timeout')) {
            $baseParams['client']['connect_timeout'] = config('elasticsearch.connect_timeout');
        }

        return $baseParams;
    }

    /**
     * 获取原始的查询参数
     *
     * @return mixed
     */
    public function getSourceParams(): array
    {
        return $this->query;
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        if ($this->debug) {
            print_r("========== query ==========\r\n");
            print_r($this->query);
            print_r("========== params ==========\r\n");
            print_r($this->fullParams);
        }
    }
}