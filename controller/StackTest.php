<?php


namespace app\member\controller;

use app\common\controller\AdminController;
use PHPUnit\Framework\TestCase;

class StackTest extends TestCase
{
    //测试用例运行前初始化
    public function setUp():void
    {
    }

//测试用例运行后执行
    public function tearDown():void
    {
    }

    /**
     * @test
     */
    public function testArrayIsEmpty()
    {
        $fixture = array();

// 断言数组$fixture中元素的数目是0。
        $this->assertEquals(0, sizeof($fixture));
    }

    /**
     * @test
     */
    public function testarrayHasKey()
    {
        $arr = array(
            'name' => 'zhangsan',
        );
//断言$arr是一个数组
        $this->assertTrue(is_array($arr));
//断言数组$arr中含有索引id
        $this->assertArrayHasKey('id', $arr);
//断言数组$arr中含有索引name
        $this->assertArrayHasKey('name', $arr);
    }

    public function testPushAndPop()
    {
        $stack = [];
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack) - 1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }
}
