<?php

namespace Ken\Elasticsearch\ResultHandler;

use Ken\Elasticsearch\Helper\Constants;

/**
 * 获取一篇文档
 *
 * @package Ken\Elasticsearch\ResultHandler
 */
class GetResult extends ResultHandler
{
    public function get(): array
    {
        $source = $this->getSource();

        if ($this->status == Constants::RESULT_SUCCESS && isset($source['found']) && $source['found'] == true) {
            $this->output->setData($source['_source']);
        }

        return $this->output->toArray();
    }
}