## 单元测试的使用

请确保你的数据库连接是正确的

使用该单元测试之前应该先执行es的初始化命令

    php artisan es:init
   
然后执行以下命令

    ./vendor/bin/phpunit 'vendor/y80x86ol/elasticsearch/src/Unit/'