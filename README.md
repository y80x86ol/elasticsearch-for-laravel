# elasticsearch-for-laravel

使用Elasticsearch Query DSL查询

## 当前版本

当前版本为1.0

目前暂未对ES7.0+进行测试

## 支持的版本

Elasticsearch

    v5.0+
    V6.0+
    
Laravel

    V5.0+
    
## 安装及配置

使用composer进行安装

    composer require y80x86ol\elasticsearch
    
在config/app.php中的providers添加
    
    Ken\Elasticsearch\ElasticsearchserviceProvider::class,
    
在Console/Kernel中添加

    \Ken\Elasticsearch\Commands\EsInitCommand::class
    
    \Ken\Elasticsearch\Commands\EsImportCommand::class
    
发布配置文件

    php artisan vendor:publish --provider="Ken\Elasticsearch\ElasticsearchServiceProvider"

## 如何使用

### 数据导入

1. 首先我们来写一个model

我们使用laravel框架默认的Users.php模型，

在app下面建立一个example文件夹，在文件夹中建立ExampleModel.php文件，并输入如下内容

        <?php
        
        namespace Ken\Elasticsearch\Example;
        
        use Ken\Elasticsearch\Model;
        use App\Users;
        
        /**
         * 测试模型
         *
         * @package Ken\Elasticsearch
         */
        class ExampleModel extends Model
        {
            /**
             * 搜索的索引
             *
             * @var string
             */
            public $searchIndexAs = 'example_test';
        
            /**
             * 搜索的类型
             *
             * @var string
             */
            public $searchTypeAs = 'example_test';
        
            /**
             * 关联模型
             *
             * @var string
             */
            public $relationModel = Users::class;
        
            /**
             * 定义有哪些字段需要搜索
             *
             * @return array
             */
            public function toSearchableArray()
            {
                $array = [
                    'id' => $this->model->id,                  //主键ID
                    'username' => $this->model->username,      //名称
                    'created_at' => $this->model->created_at,  //开始时间
                    'updated_at' => $this->model->updated_at,  //结束时间
                ];
        
                return $array;
            }
        }

2. 修改配置文件

你需要准备两个初始化文件，一个是基础的mapping模板，一个是索引创建模板，你可以参考example文件夹目录下的TemplateDsl.php和TemplateIndexDsl.php文件

        /**
         * 批量导入的模型列表
         */
        'import_models' => [
            'Ken\Elasticsearch\Example\ExampleModel'
        ],
    
        /**
         * 初始化的template和index
         */
        'init' => [
            'templates' => [
                'Ken\Elasticsearch\Example\TemplateDsl'  //如果没有模板可以去掉该值，模板请参考'Ken\Elasticsearch\Example\TemplateDsl'
            ],
            'indexs' => [
                'Ken\Elasticsearch\Example\TemplateIndexDsl' //如果没有模板可以去掉该值，模板请参考'Ken\Elasticsearch\Example\TemplateIndexDsl'
            ]
        ],


3. 初始化es

        php artisan es:init
        
或者导入指定的模板
        
        php artisan es:init --template='Ken\Elasticsearch\Example\TemplateDsl'
        
        php artisan es:init --index='Ken\Elasticsearch\Example\TemplateIndexDsl'
        
也可以合并在一起执行

        php artisan es:init --template='Ken\Elasticsearch\Example\TemplateDsl' --index='Ken\Elasticsearch\Example\TemplateIndexDsl'


4. 使用命令导入数据库指定表数据

        php artisan es:import
        
或者导入指定的模型
        
        php artisan es:import --modelName='Ken\Elasticsearch\Example\ExampleModel'


**使用代码导入**

导入全部数据

        $result = (new ExampleModel())->import();
        或
        $result = ExampleModel::import();
        
导入指定数据

        $result = (new ExampleModel())->index(1);
        或
        $result = ExampleModel::index(1);


### 文档操作

1. 索引一篇文档

在query中填写dsl查询

        return [
            'id' => '500',
            'body' => [
                'name' => '单独索引文档'
            ]
        ];
        
在controller中使用

        $result = ExampleModel::query(IndexDsl::class)->index();


2. 更新一篇文档

在query中填写dsl查询

        return [
            'id' => '2',
            'body' => [
                'doc' => [
                    'name' => '我是部分更新内容'
                ]
            ]
        ];
        
在controller中使用

        $result = ExampleModel::query(ExampleDsl::class)->update();

3. 删除一篇文档

在query中填写dsl查询

        return [
            "id" => '3'
        ];
        
在controller中使用

        $result = ExampleModel::query(ExampleDsl::class)->delete();

4. 批量索引文档

在query中填写dsl查询

        return [
            [
                'id' => '5000',
                'body' => [
                    'id' => 5000,
                    'name' => '批量索引文档1'
                ]
            ],
            [
                'id' => '5001',
                'body' => [
                    'id' => 5000,
                    'name' => '批量索引文档2'
                ]
            ]
        ];
        
在controller中使用

        $result = ExampleModel::query(ExampleDsl::class)->bulk();

### 基础查询

1. 获取一篇文档

在query中填写dsl查询

        public function query()
        {
            return [
                "id" => '2'
            ];
        }
        
在controller中使用

        $result = ExampleModel::query(ExampleDsl::class)->get();
        
2.获取多篇文档

在query中填写dsl查询

        public function query()
        {
            return [
                "ids" => [2, 3]
            ];
        }
        
在controller中使用

        $result = ExampleModel::query(ExampleDsl::class)->gets();
        
3.获取多篇文档（mget方法,推荐使用这种方法获取）

在query中填写dsl查询

        return [
                "body" => [
                    "ids" => [2, 3]
                ]
            ];
            
在controller中使用

        $result = ExampleModel::query(ExampleDsl::class)->mget();
        
4.普通搜索

在query中填写dsl查询

        return [
            "body" => [
                "query" => [
                    "match" => [
                        "name" => $this->params['name']
                    ]
                ]
            ]
        ];

在controller中使用

        $result = ExampleModel::query(ExampleDsl::class, ['name' => '测试'])->search()->get();
        
5.带过滤的查询

在query中填写dsl查询

        return [
            "body" => [
                "query" => [
                    "bool" => [
                        "must" => [
                            "match" => [
                                "name" => "测试"
                            ]
                        ],
                        "filter" => [
                            "term" => [
                                "name" => "编程"
                            ]
                        ]
                    ]
                ]
            ]
        ];

在controller中使用

        $result = ExampleModel::query(ExampleDsl::class, ['name' => '测试'])->search()->get();
        
6.聚合查询

在query中填写dsl查询

        return [
            "body" => [
                "size" => 0,
                "aggs" => [
                    "order_by_course_type" => [
                        "terms" => [
                            "field" => "course_type"
                        ]
                    ]
                ]
            ]
        ];

在controller中使用

    $result = ExampleModel::query(ExampleDsl::class, ['name' => '测试'])->search()->getAggregations();
    
### 简化查询

有时候我们的查询非常简单，并不想使用一个文件来存储query，那么可以使用queryArray()方法

    $result = ExampleModel::queryArray(["id" => '8'])->get();    
    
### 查询结果处理

当我们使用search进行搜索的时候，针对结果我们可以使用以下方法来获得更加具体的数据

- get() 获取完整的数据结果

- getHits() 获取命中hits结果

- getAggregations() 获取聚合搜索结果

- getHitsTotal() 获取命中总数

- getHitsMaxScore() 获取命中的最大分数

以上方法并不是所有search搜索通用的，比如mget查询的时候只能使用get()方法，比如聚合搜索的时候只有getAggregations()才能获得最终处理过的结果，所以你需要知道elasticsearch的具体查询会返回什么样的结果，然后选择具体的返回结果，不然调用了其他方法可能只会给你返回空。

也比如，你使用了filter过滤器，这个使用评分就无效了，可能结果直接就是1.0，所以调用getHitsMaxScore()就不会存在什么意义了。


### 版本

V1.0 2019-03-11

- 基础配置
- 命令初始化与数据导入
- 单个数据导入
- 批量数据导入
- 普通DSL查询搜索
- 普通DSL查询搜索结果处理
- 直接使用原生的Elasticsearch php sdk调用
- 实例代码