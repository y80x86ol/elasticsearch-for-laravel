<?php

namespace Ken\Elasticsearch;

use Illuminate\Support\ServiceProvider;
use Elasticsearch\ClientBuilder as ElasticBuilder;

/**
 * Elasticsearch 服务
 *
 * @package Ken\Elasticsearch
 */
class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * 服务提供者加是否延迟加载.
     *
     * @var bool
     */
    protected $defer = true; // 延迟加载服务

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/elasticsearch.php' => config_path('elasticsearch.php'), // 发布配置文件到 laravel 的config 下
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ElasticsearchEngine::class, function ($app) {
            $elasticClient = ElasticBuilder::create()
                ->setHosts(config('elasticsearch.hosts'));

            /**
             * 是否开启https
             */
            if (config('elasticsearch.ssl.enable')) {
                $certPath = config('elasticsearch.ssl.cert_path');
                $elasticClient = $elasticClient->setSSLVerification($certPath);
            }

            /**
             * 是否开启日志
             */
            if (config('elasticsearch.log.enable')) {
                $logPatch = base_path(config('elasticsearch.log.path') . '/elasticsearch-' . date("Y-m-d", time()) . '.log');
                $devel = config('elasticsearch.log.devel');
                $logger = ElasticBuilder::defaultLogger($logPatch, $devel);
                $elasticClient = $elasticClient->setLogger($logger);
            }

            /**
             * 重置连接次数
             */
            if (config('elasticsearch.retries')) {
                $elasticClient = $elasticClient->setRetries(config('elasticsearch.retries'));
            }

            /**
             * 注入命令
             */
            $this->commands([\Ken\Elasticsearch\Commands\EsInitCommand::class, \Ken\Elasticsearch\Commands\EsImportCommand::class]);

            $elasticClient = $elasticClient->build();

            return new ElasticsearchEngine($elasticClient);
        });
    }

    /**
     * 获取提供器提供的服务
     *
     * @return array
     */
    public function provides()
    {
        return [ElasticsearchEngine::class];
    }

    /**
     * 根据等级信息进行等级转换
     *
     * @param string $logDevel
     * @return int
     */
    private function getLoggerDevel(string $logDevel)
    {
        switch ($logDevel) {
            case 'DEBUG':
                return 100;
            case 'INFO':
                return 200;
            case 'NOTICE':
                return 250;
            case 'WARNING':
                return 300;
            case 'ERROR':
                return 400;
            case 'CRITICAL':
                return 500;
            default:
                return 300;
        }
    }
}
