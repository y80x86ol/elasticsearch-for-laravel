<?php

namespace Ken\Elasticsearch\Commands;

use Illuminate\Console\Command;

/**
 * elasticsearch 数据导入命令
 *
 * @package Ken\Elasticsearch\Commands
 */
class EsImportCommand extends Command
{
    /**
     * 命令
     *
     * @var string
     */
    protected $signature = 'es:import {--modelName=}';

    /**
     * 描述
     *
     * @var string
     */
    protected $description = 'import model to elasticsearch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 执行控制台命令.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('start import......');

        $modelName = $this->option('modelName');

        if ($modelName) {
            $initImportModels = explode(',', $modelName);
        } else {
            $initImportModels = config("elasticsearch.import_models");
        }

        foreach ($initImportModels as $modelItemName) {
            $this->import($modelItemName);
        }

        $this->info("");
        $this->info('done.');
    }

    /**
     * 导入数据
     *
     * @param string $modelName
     */
    private function import(string $modelName)
    {
        $this->info("");
        $this->info($modelName);

        $startTime = microtime(true);

        try {
            //数据导入
            $result = (new $modelName)->import();
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            die;
        }

        $endTime = microtime(true);

        $totalTime = ($endTime - $startTime) . ' second';

        if ($result['error']) {
            $this->info('import fail：：' . $result['message']);
        } else {
            $this->info('total num：' . $result['total']);
            $this->info('success num：' . $result['success']);
            $this->info('max id num：' . $result['max_id']);
        }
        $this->info('time consum：' . $totalTime);
    }
}
