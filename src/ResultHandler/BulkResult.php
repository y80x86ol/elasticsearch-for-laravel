<?php

namespace Ken\Elasticsearch\ResultHandler;

use Ken\Elasticsearch\Helper\Constants;

/**
 * 批量索引一篇文档
 *
 * @package Ken\Elasticsearch\ResultHandler
 */
class BulkResult extends ResultHandler
{
    public function get(): array
    {
        $source = $this->getSource();

        if (isset($source['errors']) && $source['errors'] == false) {
            return $this->output->toArray();
        }

        $this->output->setCode(Constants::RESULT_ERROR);
        return $this->output->toArray();
    }
}