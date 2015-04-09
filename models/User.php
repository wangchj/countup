<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Users".
 *
 * @property integer $userId
 * @property string $userName
 * @property string $forename
 * @property string $surname
 * @property string $email
 * @property string $phash
 * @property string $joinDate
 * @property string $location
 * @property string $timeZone
 * @property string $authKey
 *
 * @property Counters[] $counters
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['forename', 'surname', 'email', 'joinDate', 'timeZone'], 'required'],
            [['userName', 'forename', 'surname', 'email', 'phash', 'joinDate', 'location', 'timeZone'], 'string'],
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
            'phash' => 'Password',
            'joinDate' => 'Join Date',
            'timeZone' => 'Time Zone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCounters()
    {
        return $this->hasMany(Counters::className(), ['userId' => 'userId']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::findOne(['userId'=>$id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->userId;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
}