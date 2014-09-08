<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "TempUsers".
 *
 * @property integer $userId
 * @property string $userName
 * @property string $email
 * @property string $phash
 * @property string $joinDate
 * @property string $timeZone
 * @property string $code
 */
class TempUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'TempUsers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userName', 'email', 'phash', 'joinDate', 'timeZone', 'code'], 'required'],
            [['email', 'phash', 'joinDate', 'timeZone', 'code'], 'string'],
            [['userName'], 'string', 'max' => 30],
            [['userName'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'userName' => 'User Name',
            'email' => 'Email',
            'phash' => 'Phash',
            'joinDate' => 'Join Date',
            'timeZone' => 'Time Zone',
            'code' => 'Code',
        ];
    }
}