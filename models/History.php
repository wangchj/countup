<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "History".
 *
 * @property integer $counterId
 * @property string  $date
 * @property boolean $miss
 *
 * @property Counters $counter
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
            [['counterId', 'date'], 'required'],
            [['date'], 'string'],
            [['counterId'], 'integer'],
            [['counterId', 'date'], 'unique', 'targetAttribute' => ['counterId', 'date'], 'message' => 'The combination of Counter ID, Date has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'counterId' => 'Counter ID',
            'date' => 'Date',
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
