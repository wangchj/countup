<?php

namespace tests\codeception\unit\models;

use yii\codeception\TestCase;
use Carbon\Carbon;
use app\models\Counter;

class CounterTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    function testBestDataType() {
        $best = Counter::findOne(1)->getBest();
        $this->assertTrue(is_int($best['count']));
        $this->assertTrue($best['startDate'] instanceof \DateTime);
        $this->assertTrue($best['endDate'] instanceof \DateTime);
    }

    function testBestCount() {
        $counter = Counter::findOne(1);
        $best = $counter->getBest();
        $this->assertEquals(8, $best['count']);

        $counter = Counter::findOne(2);
        $best = $counter->getBest();
        $this->assertEquals(11, $best['count']);        
    }

    function testBestDateRange() {
        $expectedStart = (new Carbon())->subMonth(1)->startOfMonth()->addDays(18);
        $expectedEnd = (new Carbon())->subMonth(1)->startOfMonth()->addDays(26);
        $actual = Counter::findOne(1)->getBest();
        $this->assertEquals($expectedStart->toDateString(), $actual['startDate']->format('Y-m-d'));
        $this->assertEquals($expectedEnd->toDateString(), $actual['endDate']->format('Y-m-d'));

        $expectedStart = (new Carbon())->subMonth(1)->startOfMonth();
        $expectedEnd = (new Carbon())->subMonth(1)->startOfMonth()->addDays(11);
        $actual = Counter::findOne(2)->getBest();
        $this->assertEquals($expectedStart->toDateString(), $actual['startDate']->format('Y-m-d'));
        $this->assertEquals($expectedEnd->toDateString(), $actual['endDate']->format('Y-m-d'));
    }

    function testResetToday() {
        $startDate = Carbon::now()->subDays(5)->toDateString();
        $resetDate = Carbon::now()->toDateString();
        
        //Test precondition
        $this->tester->seeInDatabase('History', ['counterId'=>1, 'startDate'=>$startDate, 'endDate'=>null]);

        Counter::findOne(1)->reset($resetDate);

        //Test the results after reset
        $this->tester->seeInDatabase('History', ['counterId'=>1, 'startDate'=>$startDate, 'endDate'=>$resetDate]);
        $this->tester->seeInDatabase('History', ['counterId'=>1, 'startDate'=>$resetDate, 'endDate'=>null]);
    }

    function testResetPast() {
        $startDate = Carbon::now()->subDays(3)->toDateString();
        $resetDate = Carbon::now()->subDays(1)->toDateString();
        
        //Test precondition
        $this->tester->seeInDatabase('History', ['counterId'=>2, 'startDate'=>$startDate, 'endDate'=>null]);

        Counter::findOne(2)->reset($resetDate);

        //Test the results after reset
        $this->tester->seeInDatabase('History', ['counterId'=>2, 'startDate'=>$startDate, 'endDate'=>$resetDate]);
        $this->tester->seeInDatabase('History', ['counterId'=>2, 'startDate'=>$resetDate, 'endDate'=>null]);
    }
}
