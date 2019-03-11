<?php

namespace Ken\Elasticsearch\ResultHandler;

/**
 * 更新
 *
 * @package Ken\Elasticsearch\ResultHandler
 */
class UpdateResult extends ResultHandler
{
    /**
     * 获取结果
     *
     * @return array
     */
    public function get(): array
    {
        $source = $this->getSource();

        if (isset($source['result']) && $source['result'] == 'updated') {
            return $this->output->toArray();
        }

        $this->output->setCode(Constants::RESULT_ERROR);
        return $this->output->toArray();
    }
}