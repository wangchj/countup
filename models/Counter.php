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
 * @property string  $label
 * @property string  $summary
 * @property string  $timeZone Use $this->getTimeZone() instead
 * @property string  $startDate
 * @property string  $type
 * @property integer $every
 * @property string  $on
 * @property boolean $active
 * @property boolean $public
 *
 * @property Users $user
 * @property History[] $histories
 */
class Counter extends \yii\db\ActiveRecord
{   
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
            [['userId', 'label', 'startDate', 'type'], 'required'],
            [['userId', 'every'], 'integer'],
            [['public', 'active'], 'boolean'],
            [['startDate','summary', 'timeZone', 'type', 'on'], 'string'],
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
            'type' => 'Type',
            'on' => 'On'
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
     * If this counter does not have a start date, null is returned. This happens on the first day
     * of the counter when there is no history entry (either mark or miss) for this counter.
     */
    public function getCurrentStartDate() {
        //Get the most recent miss date string
        $miss = $this->getHistory()->where(['miss'=>true])->max('date');

        if($miss)
            $date = $this->getHistory()->where("date > '$miss' and miss = 0")->min('date');
        else
            $date = $this->getHistory()->where(['miss'=>false])->min('date');

        return $date ? new DateTime($date, $this->getTimeZone()) : null;
    }

    /**
     * Get the date of most recent mark.
     * Return null if this counter has no start date.
     */
    public function getCurrentEndDate() {
        $miss = $this->getHistory()->where(['miss'=>true])->max('date');

        if($miss)
            $date = $this->getHistory()->where("date > '$miss' and miss = 0")->max('date');
        else
            $date = $this->getHistory()->where(['miss'=>false])->max('date');

        return $date ? new DateTime($date, $this->getTimeZone()) : null;
    }

    /**
     * Checks if this counter is active.
     */
    public function isActive() {
        return $this->active;
    }

    /**
     * Get the current span in days.
     */
    public function getDays() {
        if(!$this->isActive())
            return 0;

        if(!$start = $this->getCurrentStartDate())
            return 0;
        if(!$end = $this->getCurrentEndDate())
            return 0;

        return $end->diff($start)->days + 1;
    }

    /**
     * Get the longest span in days.
     *
     * Return an array with the format ['startDate'=>DateTime, 'endDate'=>DateTime, 'count'=>integer]
     * where 'startDate' could be null.
     */
    public function getBest() {
        $timezone = $this->getTimeZone();

        $max = $this->getDays();
        $maxStartDate = $this->getCurrentStartDate();
        $maxEndDate = new DateTime('now', $timezone);
        $misses = $this->getHistory()->where(['miss'=>true])->orderBy('date')->all();

        for($i = 0; $i < count($misses); $i++) {
            if($i == 0) {
                $start = $this->getHistory()->min('date');
                $end   = $this->getHistory()->where("date < '{$misses[$i]->date}'")->max('date');
            }
            else {
                $start = $this->getHistory()->where("date > '{$misses[$i - 1]->date}'")->min('date');
                $end   = $this->getHistory()->where("date < '{$misses[$i]->date}'")->max('date');
            }

            //Yii::error($this->getHistory()->where("endDate < {$misses[$i]->date} and type != 'miss'")->createCommand()->sql);

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