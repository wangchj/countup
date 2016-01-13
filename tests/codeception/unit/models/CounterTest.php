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

    function testBest() {
        $counter = Counter::findOne(1);
        $best = $counter->getBest();
        $this->assertEquals(8, $best['count']);

        $start = (new Carbon())->subMonth(1)->startOfMonth()->addDays(18);
        $end = (new Carbon())->subMonth(1)->startOfMonth()->addDays(26);
        $this->assertEquals($start->toDateString(), $best['startDate']);
        $this->assertEquals($end->toDateString(), $best['endDate']);

        
        $counter = Counter::findOne(2);
        $best = $counter->getBest();
        $this->assertEquals(11, $best['count']);

        $start = (new Carbon())->subMonth(1)->startOfMonth();
        $end = (new Carbon())->subMonth(1)->startOfMonth()->addDays(11);
        $this->assertEquals($start->toDateString(), $best['startDate']);
        $this->assertEquals($end->toDateString(), $best['endDate']);        
    }
}
