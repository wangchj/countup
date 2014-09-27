<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "History".
 *
 * @property integer $counterId
 * @property string $startDate
 * @property string $endDate
 *
 * @property Counters $counter
 */
class History extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'History';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['counterId', 'startDate', 'endDate'], 'required'],
            [['startDate', 'endDate'], 'string'],
            [['counterId'], 'integer'],
            [['counterId', 'startDate', 'endDate'], 'unique', 'targetAttribute' => ['counterId', 'startDate', 'endDate'], 'message' => 'The combination of Counter ID, Start Date and End Date has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'counterId' => 'Counter ID',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCounter()
    {
        return $this->hasOne(Counter::className(), ['counterId' => 'counterId']);
    }
}
