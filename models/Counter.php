<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Counters".
 *
 * @property integer $counterId
 * @property integer $userId
 * @property string $label
 * @property string $startDate
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
            [['startDate','summary'], 'string'],
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
        $timeZone = new \DateTimeZone($user->timeZone);
        $start = new \DateTime($this->startDate, $timeZone);
        $end   = new \DateTime('now', $timeZone);
        return $end->diff($start);
    }
}