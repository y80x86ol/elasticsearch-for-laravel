<?php

namespace Ken\Elasticsearch\Commands;

use Illuminate\Console\Command;
use Ken\Elasticsearch\ElasticsearchEngine;

/**
 * elasticsearch 数据导入命令
 *
 * @package Ken\Elasticsearch\Commands
 */
class EsInitCommand extends Command
{
    /**
     * 命令
     *
     * @var string
     */
    protected $signature = 'es:init {--template=} {--index=}';

    /**
     * 描述
     *
     * @var string
     */
    protected $description = 'elasticsearch init template or index
                                {--template= : init template by queryDsl}
                                {--index= : init index by queryDsl}';

    /**
     * elasticsearch 驱动
     *
     * @var ElasticsearchEngine|mixed
     */
    private $elasticEngine;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->elasticEngine = resolve(ElasticsearchEngine::class);
    }

    /**
     * 执行控制台命令.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('start init......');


        $template = $this->option('template');
        $indexTemplate = $this->option('index');

        //初始化模板
        if ($template) {
            $this->initTemplate($template);
        }

        //初始化index
        if ($indexTemplate) {
            $this->initIndex($indexTemplate);
        }

        if (!$template && !$indexTemplate) {
            //通过配置初始化模板
            $initTemplatesDsl = config("elasticsearch.init.templates");
            foreach ($initTemplatesDsl as $templateDsl) {
                $this->initTemplate($templateDsl);
            }

            //通过配置初始化索引
            $indexsDsl = config("elasticsearch.init.indexs");

            foreach ($indexsDsl as $indexDsl) {
                $this->initIndex($indexDsl);
            }
        }

        $this->info("");
        $this->info('done.');
    }

    /**
     * 创建模板
     *
     * @param string $templateName
     */
    private function initTemplate(string $templateName)
    {
        $this->info("");
        $this->info($templateName);

        $startTime = microtime(true);

        try {
            $templateParams = (new $templateName())->query();
            $this->elasticEngine->elastic->indices()->putTemplate($templateParams);
            $this->info("success");
        } catch (\Exception $exception) {
            $this->error("fail：" . $exception->getMessage());
        }

        $endTime = microtime(true);

        $totalTime = ($endTime - $startTime) . 'second';

        $this->info('time consum：' . $totalTime);
        $this->info("");
    }

    /**
     * 创建索引模板
     *
     * @param string $indexTemplateName
     */
    private function initIndex(string $indexTemplateName)
    {
        $this->info("");
        $this->info($indexTemplateName);

        $startTime = microtime(true);

        $indexParamsList = (new $indexTemplateName())->query();
        foreach ($indexParamsList as $indexParams) {
            $this->info($indexParams['index']);

            try {
                $this->elasticEngine->elastic->indices()->create($indexParams);
                $this->info('success');
            } catch (\Exception $exception) {
                $this->error("fail");
                $this->error($exception->getMessage());
            }
            $this->info("");
        }

        $endTime = microtime(true);

        $totalTime = ($endTime - $startTime) . 'second';

        $this->info('time consum：' . $totalTime);
    }
}
