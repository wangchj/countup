<?php

namespace app\models;

use \DateTime;
use \DateTimeZone;
use \Exception;

use Yii;

/**
 * This is the model class for table "Counters".
 *
 * @property integer $counterId
 * @property integer $userId
 * @property string $label
 * @property string $timeZone Use $this->getTimeZone() instead
 * @property string $summary
 * @property boolean $public
 *
 * @property Users $user
 * @property History[] $histories
 */
class Counter extends \yii\db\ActiveRecord
{
    public $startDate;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Counters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'label', 'startDate'], 'required'],
            [['userId'], 'integer'],
            [['public'], 'boolean'],
            [['startDate','summary', 'timeZone'], 'string'],
            [['label'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'counterId' => 'Counter ID',
            'userId' => 'User ID',
            'label' => 'Label',
            'startDate' => 'Start Date',
            'summary' => 'Summary',
            'public' => 'Public',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['userId' => 'userId']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getHistory()
    {
       return $this->hasMany(History::className(), ['counterId' => 'counterId']);
    }

    /**
     * Gets the timezone of this counter returned as PHP DateTimeZone object.
     * If this counter does not have timezone, then timezone of the owner of this counter is returned.
     */
    public function getTimeZone() {
        return $this->timeZone ? 
            new DateTimeZone($this->timeZone) : 
            new DateTimeZone($this->user->timeZone);
    }

    /**
     * Get the DateInterval object between start date and now, using user's time zone.
     * @return DateInterval object that contains days, months, and years.
     */
    /*public function getDateInterval()
    {
        $start = $this->getCurrentStartDate();
        $end   = new DateTime('now', $this->getTimeZone());
        return $end->diff($start);
    }*/

    /**
     * Get start date of current running count of this counter.
     * Exception is thrown if this counter does not have a running count (is inactive).
     */
    public function getCurrentStartDate() {
        //Get the most recent miss date string
        $miss = $this->getHistory()->where(['type'=>'miss'])->max('startDate');

        if($miss)
            $startDate = $this->getHistory()->where("startDate > '$miss' and type != 'miss'")->min('startDate');
        else
            $startDate = $this->getHistory()->where("type != 'miss'")->min('startDate');

        return new DateTime($startDate, $this->getTimeZone());
    }

    /**
     * Checks if this counter is active (has a running count).
     */
    public function isActive() {
        $miss = $this->getHistory()->where(['type'=>'miss'])->max('startDate');
        if(!$miss)
            return true;
        return $this->getHistory()->where("startDate > '$miss'")->count() > 0;
    }

    public function getDays() {
        if(!$this->isActive())
            return 0;

        $start = $this->getCurrentStartDate();
        $end   = new DateTime('now', $this->getTimeZone());
        return $end->diff($start)->days;
    }

    /**
     * Return an array with the format ['startDate'=>DateTime, 'endDate'=>DateTime, 'count'=>integer]
     */
    public function getBest() {
        $timezone = $this->getTimeZone();

        $max = $this->getDays();
        $maxStartDate = $this->getCurrentStartDate();
        $maxEndDate = new DateTime('now', $timezone);
        $misses = $this->getHistory()->where(['type'=>'miss'])->orderBy('startDate')->all();

        for($i = 0; $i < count($misses); $i++) {
            if($i == 0) {
                $start = $this->getHistory()->where("type != 'miss'")->min('startDate');
                $end   = $this->getHistory()->where("endDate < '{$misses[$i]->startDate}' and type != 'miss'")->max('endDate');
            }
            else {
                $start = $this->getHistory()->where("startDate > '{$misses[$i - 1]->startDate}' and type != 'miss'")->min('startDate');
                $end   = $this->getHistory()->where("endDate < '{$misses[$i]->startDate}' and type != 'miss'")->max('endDate');
            }

            //Yii::error($this->getHistory()->where("endDate < {$misses[$i]->startDate} and type != 'miss'")->createCommand()->sql);

            if(!$start || !$end)
                continue;
            
            $startDate = new DateTime($start, $timezone);
            $endDate = new DateTime($end, $timezone);

            $diff = $endDate->diff($startDate)->days + 1;
            //Yii::error($diff);
            
            if($diff > $max) {
                $max = $diff;
                $maxStartDate = $startDate;
                $maxEndDate = $endDate;
            }
        }

        //$max = (int)Yii::$app->db->createCommand("select max(julianday(endDate) - julianday(startDate)) from History where counterId={$this->counterId}")->queryScalar();
        return ['startDate'=>$maxStartDate, 'endDate'=>$maxEndDate, 'count'=>$max];
    }

    /**
     * Compute the DateInterval object between start and end date. 
     * The date interval always starts with 0 day not 1; e.g. start date = end date -> 0 day.
     * @param $startDate start date string.
     * @param $endDate   end date string.
     * @return DateInterval object that contains days, months, and years.
     */
    /*public static function computeDateIntervalBase($start, $end)
    {
        if(is_string($start))
            $start = new \DateTime($start);
        if(is_string($end))
            $end = new \DateTime($end);

        //Add 1 day so result interval start with 1 not 0 day.
        //$end->add(new \DateInterval('P1D'));

        $res = $end->diff($start);

        return $res;
    }*/

    /**
     * Compute the DateInterval object between start and end date. 
     * The date interval always starts with 0 day not 1; e.g. start date = end date -> 0 day.
     * @param $counter a Counter model object.
     * @return DateInterval object that contains days, months, and years.
     */
    /*public static function computeDateInterval($counter)
    {
        $user = $counter->user;
        $timeZone = $this->getTimeZone();
        return self::computeDateIntervalBase(new \DateTime($counter->startDate, $timeZone), new \DateTime('now', $timeZone));
    }*/
}