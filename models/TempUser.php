<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "TempUsers".
 *
 * Properties mirrors User model class
 *
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
            [['forename', 'surname', 'email', 'phash', 'joinDate', 'timeZone', 'code'], 'required'],
            [['userName', 'forename', 'surname', 'email', 'phash', 'joinDate', 'timeZone', 'code'], 'string'],
            [['userName'], 'string', 'max' => 30],
            [['userName'], 'unique'],
            ['email', 'email'],
            ['fbId', 'integer']
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
            'forename' => 'First Name',
            'surname'  => 'Last Name',
            'email' => 'Email',
            'phash' => 'Phash',
            'joinDate' => 'Join Date',
            'timeZone' => 'Time Zone',
            'code' => 'Code',
        ];
    }
}