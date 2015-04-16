<?php

namespace app\models;

use \DateTime;
use \DateTimeZone;

use Yii;

/**
 * This is the model class for table "Counters".
 *
 * @property integer $counterId
 * @property integer $userId
 * @property string $label
 * @property string $startDate
 * @property string $timeZone
 * @property string $summary
 * @property boolean $public
 * @property boolean $active
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
            [['userId', 'label', 'startDate'], 'required'],
            [['userId'], 'integer'],
            [['public', 'active'], 'boolean'],
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
            'active' => 'Active',
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
     * Get the DateInterval object between start date and now, using user's time zone.
     * @return DateInterval object that contains days, months, and years.
     */
    public function getDateInterval()
    {
        $user = $this->user;
        $timeZone = $this->timeZone ? new DateTimeZone($this->timeZone) : new DateTimeZone($user->timeZone);
        $start = new DateTime($this->startDate, $timeZone);
        $end   = new DateTime('now', $timeZone);
        return $end->diff($start);
    }

    public function getDays() {
        return $this->getDateInterval()->days;
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
        $timeZone = new \DateTimeZone($user->timeZone);
        return self::computeDateIntervalBase(new \DateTime($counter->startDate, $timeZone), new \DateTime('now', $timeZone));
    }
}