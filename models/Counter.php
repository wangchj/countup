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
 * @property integer $shown
 *
 * @property Users $user
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
            [['userId', 'shown'], 'integer'],
            [['startDate'], 'string'],
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
            'shown' => 'Shown',
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