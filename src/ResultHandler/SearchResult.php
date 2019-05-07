<?php

namespace Ken\Elasticsearch\ResultHandler;

/**
 * 搜索
 *
 * @package Ken\Elasticsearch\ResultHandler
 */
class SearchResult extends ResultHandler
{
    /**
     * 获取结果
     *
     * @return array
     */
    public function get()
    {
        $source = $this->getSource();

        $data = $source['_source'] ?? [];

        $this->output->setData($data);

        return $this->output->toArray();
    }

    /**
     * 获取搜索结果列表
     *
     * @return array
     */
    public function getHits(): array
    {
        $searchResult = $this->getSource();

        $newList = [];
        $list = $searchResult['hits']['hits'] ?? [];

        foreach ($list as $item) {
            $newList[] = $item['_source'];
        }

        $this->output->setData($newList);

        return $this->output->toArray();
    }

    /**
     * 获取聚合操作结果
     *
     * @return array
     */
    public function getAggregations(): array
    {
        $searchResult = $this->getSource();

        $newList = [];
        if (isset($searchResult['aggregations'])) {
            foreach ($searchResult['aggregations'] as $key => $item) {
                $newList[$key] = $item['buckets'];
            }
        }

        $this->output->setData($newList);

        return $this->output->toArray();
    }

    /**
     * 获取命中总数
     *
     * @return array
     */
    public function getHitsTotal(): array
    {
        $searchResult = $this->getSource();

        $hitsTotal = $searchResult['hits']['total'] ?? 0;

        $this->output->setData($hitsTotal);

        return $this->output->toArray();
    }

    /**
     * 获取命中最大评分
     *
     * @return array
     */
    public function getHitsMaxScore(): array
    {
        $searchResult = $this->getSource();

        $hitsTotal = $searchResult['hits']['max_score'] ?? 0.00;

        $this->output->setData($hitsTotal);

        return $this->output->toArray();
    }

    /**
     * 获取分页数据
     *
     * @return array
     */
    public function paginate(int $page, int $size): array
    {
        $searchResult = $this->getSource();

        $newList = [];
        $list = $searchResult['hits']['hits'] ?? [];

        foreach ($list as $item) {
            $newList[] = $item['_source'] ?? [];
        }

        $hitsTotal = $searchResult['hits']['total'] ?? 0;

        $data = [
            "page" => $this->getPageInfo($page, $size, $hitsTotal),
            "list" => $newList
        ];

        $this->output->setData($data);

        return $this->output->toArray();
    }

    /**
     * 获取分页信息
     *
     * @param int $page 当前页面
     * @param int $size 每页多少条
     * @param int $hitsTotal 命中总数据
     * @return array
     */
    private function getPageInfo(int $page, int $size, int $hitsTotal): array
    {
        $totalPage = (int)ceil($hitsTotal / $size);//获取总页码
        $pageInfo = [
            'current_page' => $page, //当前多少页
            'last_page' => ($totalPage > $page) ? $page + 1 : $page, //下一页
            'per_page' => $size, // 一页展示多少条
            'total' => $hitsTotal, // 总共有多少条
            'total_page' => $totalPage, // 总共多少页
        ];

        return $pageInfo;
    }
}