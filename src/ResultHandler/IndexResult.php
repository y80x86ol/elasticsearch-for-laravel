<?php

namespace Ken\Elasticsearch\ResultHandler;

/**
 * 单独索引一篇文档
 *
 * @package Ken\Elasticsearch\ResultHandler
 */
class IndexResult extends ResultHandler
{
    public function get(): array
    {
        $source = $this->getSource();

        if (isset($source['result']) && in_array($source['result'], ['created', 'updated'])) {
            return $this->output->toArray();
        }

        $this->output->setCode(Constants::RESULT_ERROR);
        return $this->output->toArray();
    }
}