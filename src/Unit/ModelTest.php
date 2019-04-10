<?php

namespace Ken\Elasticsearch\Unit;

use Ken\Elasticsearch\Example\ExampleModel;
use Ken\Elasticsearch\Helper\Constants;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Ken\Elasticsearch\Example\{GetDocDsl, MgetDsl, SearchDsl, DeleteDsl, UpdateDsl, IndexDsl, BulkDsl};

/**
 * 模型单元测试
 *
 * 测试基本功能访问是否正常
 *
 * @package Ken\Elasticsearch\Unit
 */
class ModelTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->initExampleData();

        //测试数据导入
        $result = (new ExampleModel())->index(1);
        $this->assertTrue($result['error'] == false);

        $result = (new ExampleModel())->import([2, 3]);
        $this->assertTrue($result['error'] == false);

        $result = (new ExampleModel())->import();
        $this->assertTrue($result['error'] == false);

        //两种不同的查询方式测试
        $result = (new ExampleModel())->queryArray(["id" => '1'])->get();
        $this->assertTrue($result['code'] == Constants::RESULT_SUCCESS);

        $result = ExampleModel::queryArray(["id" => '1'])->get();
        $this->assertTrue($result['code'] == Constants::RESULT_SUCCESS);

        //测试基础获取数据
        $result = ExampleModel::query(GetDocDsl::class)->get();
        $this->assertTrue($result['code'] == Constants::RESULT_SUCCESS);

        $result = ExampleModel::query(MgetDsl::class)->mget();
        $this->assertTrue($result['code'] == Constants::RESULT_SUCCESS);

        //测试获取全部数据
        $result = (new ExampleModel())->query(SearchDsl::class, ['name' => 'jack'])->search()->getAll();
        $this->assertTrue($result['code'] == Constants::RESULT_SUCCESS);

        //测试删除数据
        $result = ExampleModel::query(DeleteDsl::class)->delete();
        $this->assertTrue($result['code'] == Constants::RESULT_SUCCESS);

        $result = ExampleModel::queryArray(["id" => '3'])->get();
        $this->assertTrue($result['code'] == Constants::RESULT_ERROR);

        //测试更新数据
        $result = ExampleModel::query(UpdateDsl::class)->update();
        $this->assertTrue($result['code'] == Constants::RESULT_SUCCESS);

        //测试索引新数据
        $result = ExampleModel::query(IndexDsl::class)->index();
        $this->assertTrue($result['code'] == Constants::RESULT_SUCCESS);

        $result = ExampleModel::query(BulkDsl::class)->bulk();
        $this->assertTrue($result['code'] == Constants::RESULT_SUCCESS);
    }

    /**
     * 初始化测试数据
     */
    private function initExampleData()
    {
        $this->initStudentTable();

        $this->initStudentData();
    }

    /**
     * 初始化学生表
     */
    private function initStudentTable()
    {
        try {
            Schema::drop('unit_example_student');
        } catch (\Exception $exception) {

        }

        Schema::create('unit_example_student', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32);
            $table->string('desc', 255);
            $table->integer('grade');
            $table->integer('age');
            $table->integer('score');
            $table->timestamps();
        });
    }

    /**
     * 初始化学生数据
     */
    private function initStudentData()
    {
        $studentList = TestData::studentData();
        \DB::table('student')->insert($studentList);
    }
}
