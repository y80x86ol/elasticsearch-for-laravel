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
}