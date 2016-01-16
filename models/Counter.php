<?php

namespace app\models;

use \DateTime;
use \DateTimeZone;
use \DateInterval;
use \Exception;
use Carbon\Carbon;
use Yii;

/**
 * This is the model class for table "Counters".
 *
 * @property integer $counterId
 * @property integer $userId
 * @property string  $label
 * @property string  $summary
 * @property string  $timeZone Use $this->getTimeZone() instead
 * @property boolean $public
 * @property integer $dispOrder
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
            [['userId', 'label', 'dispOrder'], 'required'],
            [['userId', 'dispOrder'], 'integer'],
            [['public', 'active'], 'boolean'],
            [['summary', 'timeZone'], 'string'],
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
            'summary' => 'Summary',
            'public' => 'Public',
            'dispOrder' => 'Display Order'
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
     *
     * @return DateInterval object that contains days, months, and years.
     */
    public function getDateInterval()
    {
        $start = $this->getCurrentStartDate();
        $end   = new DateTime('now', $this->getTimeZone());
        return $end->diff($start);
    }

    /**
     * Get start date DateTime object of current running count of this counter.
     *
     * @return DateTime null if this counter is not actively counting; DateTime object otherwise.
     */
    public function getCurrentStartDate() {
        $running = $this->getHistory()->where(['endDate'=>null])->one();
        return $running ? new DateTime($running->startDate, $this->getTimeZone()) : null;
    }

    /**
     * Checks if this counter is actively counting. This is determined by the presence of an entry
     * in the History table with the end date of null.
     *
     * @return boolean true if this counter is actively counting; false otherwise.
     */
    public function isActive() {
        $running = $this->getHistory()->where(['endDate'=>null])->one();
        if($running)
            return true;
        else
            return false;
    }

    /**
     * Get the current span in days.
     *
     * @return integer The current count in the number of days.
     */
    public function getDays() {
        if($this->isActive())
            return $this->getDateInterval()->days;
        return 0;
    }

    /**
     * Get the longest span in days.
     *
     * @return array an array with the format
     * ['startDate'=>DateTime, 'endDate'=>DateTime, 'count'=>integer]
     */
    public function getBest() {
        $res = Yii::$app->db->createCommand(
            "select startDate, endDate, julianday(endDate) - julianday(startDate) as count from (
                select * from history where counterId={$this->counterId} and endDate is not null
                union
                select counterId, startDate, date() as endDate from History
                where counterId={$this->counterId} and endDate is null
            ) group by counterId having max(count);"
        )->queryOne();
        
        $timezone = $this->getTimeZone();

        $res['count'] = (int)$res['count'];
        $res['startDate'] = new DateTime($res['startDate'], $timezone);
        $res['endDate'] = new DateTime($res['endDate'], $timezone);
        
        return $res;
    }

    /**
     * Resets the count of this counter to 0.
     *
     * If the counter is not active, this method has no effect.
     *
     * @param $resetDate string A date string in a format in http://php.net/manual/en/datetime.formats.php
     */
    public function reset($resetDate) {
        if(!$this->isActive())
            return;

        $resetDate = new \DateTime($resetDate);

        $history = History::findOne(['counterId'=>$this->counterId, 'endDate'=>null]);
        $history->endDate = $resetDate->format('Y-m-d');
        $history->save();

        $history = new History();
        $history->counterId = $this->counterId;
        $history->startDate = $resetDate->format('Y-m-d');
        $history->save();
    }

    /**
     * Gets the next counter display order number of an user.
     * If userId is invalid or error occurred, 1 is returned.
     */
    public static function getNextOrderNum($userId) {
        if(!$userId)
            return 1;

        if($max = Counter::find()->where(['userId'=>$userId])->max('dispOrder'))
            return $max + 1;
        else
            return 1;
    }
}