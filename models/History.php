<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "History".
 *
 * @property string $startDate
 * @property string $endDate
 * @property integer $counterId
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
            [['startDate', 'endDate', 'counterId'], 'required'],
            [['startDate', 'endDate'], 'string'],
            [['counterId'], 'integer'],
            [['startDate', 'endDate'], 'unique', 'targetAttribute' => ['startDate', 'endDate'], 'message' => 'The combination of Start Date and End Date has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'counterId' => 'Counter ID',
        ];
    }
}