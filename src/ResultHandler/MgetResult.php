<?php

namespace Ken\Elasticsearch\ResultHandler;

/**
 * 批量获取
 *
 * @package Ken\Elasticsearch\ResultHandler
 */
class MgetResult extends ResultHandler
{
    public function get()
    {
        $source = $this->getSource();

        $result = [];
        if (isset($source['docs'])) {
            foreach ($source['docs'] as $item) {
                if (isset($item['found']) && $item['found'] == true) {
                    $result[] = $item['_source'];
                }
            }
        }

        $this->output->setData($result);
        return $this->output->toArray();
    }
}