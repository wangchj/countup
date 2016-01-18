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

    /**
     * Test a normal case where the reset date is today; in other words, $startDate < $resetDate = $today.
     * Note: Sqlite and Yii2 both uses UTC as default date time zone.
     */
    function testResetToday() {
        $counter = Counter::findOne(1);

        $startDate = Carbon::now()->subDays(5);
        $resetDate = Carbon::now($counter->getTimeZone());
        
        codecept_debug("Start Date: {$startDate->toAtomString()} {$startDate->getTimeZone()->getName()}");
        codecept_debug("Reset Date: {$resetDate->toAtomString()} {$resetDate->getTimeZone()->getName()}");

        //Test precondition
        $this->tester->seeInDatabase('History', ['counterId'=>1, 'startDate'=>$startDate->toDateString(),
            'endDate'=>null]);

        Counter::findOne(1)->reset($resetDate->toDateString());

        //Test the results after reset
        $this->tester->seeInDatabase('History', ['counterId'=>1, 'startDate'=>$startDate->toDateString(),
            'endDate'=>$resetDate->toDateString()]);
        $this->tester->seeInDatabase('History', ['counterId'=>1, 'startDate'=>$resetDate->toDateString(),
            'endDate'=>null]);
    }

    /**
     * Test a normal case where the reset date is legal but is before (less than) today.
     * In other words, $startDate < $resetDate < $today.
     */
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

    /**
     * Tests an illegal case where the reset date is before the start date.
     *
     * @expectedException InvalidArgumentException
     */
    function testResetDateBeforeStartDate() {
        Counter::findOne(1)->reset(Carbon::now()->subDays(6)->toDateString());
    }

    /**
     * Tests an illegal case where the reset date after today.
     *
     * @expectedException InvalidArgumentException
     */
    function testResetDateGreaterThanToday() {
        Counter::findOne(1)->reset(Carbon::now()->addDays(1)->toDateString());
    }

    /**
     * Tests a degenerate case where the reset date is the same as the start date. No exception is expected; however,
     * no record should be inserted into History and there should be no change to the database.
     */
    function testResetDateSameAsStartDate() {
        $startDate = Carbon::now()->subDays(5);
        
        //Test precondition
        $this->tester->seeInDatabase('History', ['counterId'=>1, 'startDate'=>$startDate->toDateString(),
            'endDate'=>null]);

        Counter::findOne(1)->reset($startDate->toDateString());

        $this->tester->seeInDatabase('History', ['counterId'=>1, 'startDate'=>$startDate->toDateString(),
            'endDate'=>null]);
        $this->tester->dontSeeInDatabase('History', ['counterId'=>1, 'startDate'=>$startDate->toDateString(),
            'endDate'=>$startDate->toDateString()]);        
    }
}
