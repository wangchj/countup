<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "History".
 *
 * @property integer $counterId
 * @property string  $startDate
 * @property string  $endDate
 *
 * @property Counter $counter
 */
class History extends \yii\db\ActiveRecord
{
    public static $dateFormat = 'Y-m-d';

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
            [['counterId', 'startDate'], 'required'],
            [['startDate'], 'string'],
            [['counterId'], 'integer'],
            //[['counterId', 'startDate'], 'unique',
            //    'targetAttribute' => ['counterId', 'startDate'],
            //    'message' => 'The combination of Counter ID, Start Date has already been taken.']
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
            'endDate' => 'End Date'
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
