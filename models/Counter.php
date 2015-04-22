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
    public function getDateInterval()
    {
        $start = $this->getCurrentStartDate();
        $end   = new DateTime('now', $this->getTimeZone());
        return $end->diff($start);
    }

    /**
     * Get start date of current running count of this counter.
     * Exception is thrown if this counter does not have a running count (is inactive).
     */
    public function getCurrentStartDate() {
        $running = $this->getHistory()->where(['counterId'=>$this->counterId, 'endDate' => null])->one();
        if(!$running)
            throw new Exception('There is no current start date because counter is not active.');
        return new DateTime($running->startDate, $this->getTimeZone());
    }

    /**
     * Checks if this counter is active (has a running count).
     */
    public function isActive() {
        $running = $this->getHistory()->where(['counterId'=>$this->counterId, 'endDate' => null])->one();
        if($running)
            return true;
        else
            return false;
    }

    public function getDays() {
        if($this->isActive())
            return $this->getDateInterval()->days;
        return 0;
    }

    public function getBest() {
        $max = (int)Yii::$app->db->createCommand("select max(julianday(endDate) - julianday(startDate)) from History where counterId={$this->counterId}")->queryScalar();
        return max($max, $this->getDays());
    }

    /**
     * Compute the DateInterval object between start and end date. 
     * The date interval always starts with 0 day not 1; e.g. start date = end date -> 0 day.
     * @param $startDate start date string.
     * @param $endDate   end date string.
     * @return DateInterval object that contains days, months, and years.
     */
    public static function computeDateIntervalBase($start, $end)
    {
        if(is_string($start))
            $start = new \DateTime($start);
        if(is_string($end))
            $end = new \DateTime($end);

        //Add 1 day so result interval start with 1 not 0 day.
        //$end->add(new \DateInterval('P1D'));

        $res = $end->diff($start);

        return $res;
    }

    /**
     * Compute the DateInterval object between start and end date. 
     * The date interval always starts with 0 day not 1; e.g. start date = end date -> 0 day.
     * @param $counter a Counter model object.
     * @return DateInterval object that contains days, months, and years.
     */
    public static function computeDateInterval($counter)
    {
        $user = $counter->user;
        $timeZone = $this->getTimeZone();
        return self::computeDateIntervalBase(new \DateTime($counter->startDate, $timeZone), new \DateTime('now', $timeZone));
    }
}