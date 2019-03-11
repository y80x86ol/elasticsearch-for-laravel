<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 2019/3/10
 * Time: 2:45 PM
 */

namespace Ken\Elasticsearch\Unit;

class TestData
{
    public static function studentData()
    {
        return [
            [
                'id' => 1,
                'name' => 'jack',
                'desc' => 'i like play football',
                'grade' => 1,
                'age' => 13,
                'score' => 87
            ],
            [
                'id' => 2,
                'name' => 'ken james',
                'desc' => 'ciro james is my brother',
                'grade' => 1,
                'age' => 14,
                'score' => 99
            ],
            [
                'id' => 3,
                'name' => 'tom',
                'desc' => 'i am from the united states',
                'grade' => 1,
                'age' => 13,
                'score' => 74
            ],
            [
                'id' => 4,
                'name' => 'luc',
                'desc' => 'i like play basketball',
                'grade' => 1,
                'age' => 15,
                'score' => 62
            ],
            [
                'id' => 5,
                'name' => 'zhang hong',
                'desc' => 'i like play volleyball',
                'grade' => 1,
                'age' => 13,
                'score' => 93
            ],
            [
                'id' => 6,
                'name' => 'liu ming',
                'desc' => 'i am from china',
                'grade' => 2,
                'age' => 12,
                'score' => 87
            ],
            [
                'id' => 7,
                'name' => 'nena',
                'desc' => 'i like chinese food',
                'grade' => 2,
                'age' => 14,
                'score' => 87
            ],
            [
                'id' => 8,
                'name' => 'gena jian',
                'desc' => 'shaw jian is my sister',
                'grade' => 2,
                'age' => 15,
                'score' => 83
            ],
            [
                'id' => 9,
                'name' => 'shaw jian',
                'desc' => 'gena jian is my sister',
                'grade' => 2,
                'age' => 14,
                'score' => 96
            ],
            [
                'id' => 10,
                'name' => 'ciro james',
                'desc' => 'i don\'t like anything',
                'grade' => 2,
                'age' => 15,
                'score' => 75
            ],
        ];
    }
}