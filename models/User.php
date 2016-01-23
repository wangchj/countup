<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "Users".
 *
 * @property integer $userId
 * @property string $userName
 * @property string $forename
 * @property string $surname
 * @property string $gender
 * @property string $email
 * @property string $phash
 * @property string $joinDate
 * @property string $location
 * @property string $timeZone
 * @property string $authKey
 * @property string $fbId
 * @property string $picture
 *
 * @property Counter[] $counters
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
            [['forename', 'surname', 'email', 'joinDate', 'timeZone', 'gender'], 'required'],
            [['userName', 'forename', 'surname', 'gender', 'email', 'phash', 'joinDate', 'location', 'timeZone', 'picture'], 'string'],
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
            'gender' => 'Gender',
            'email' => 'Email',
            'phash' => 'Password',
            'joinDate' => 'Join Date',
            'timeZone' => 'Time Zone',
        ];
    }

    /**
     * Returns user's user name if exist, else user's user id number.
     */
    public function getIdentifier() {
        return $this->userName ? $this->userName : $this->userId;
    }

    public function getPicture() {
        if($this->picture)
            return Url::isRelative($this->picture) ? Yii::getAlias("@web/pictures/{$this->picture}") : $this->picture;
        else
            return $this->gender == 'female' ?
                Yii::getAlias('@web/images/no-user-girl.jpg') :
                Yii::getAlias('@web/images/no-user.jpg');
    }

    /**
     * If this user follows user specified by $userId.
     */
    public function isFollowerOf($userId) {
        return Follow::findOne(['followerId'=>$this->userId, 'followeeId'=>$userId]) != null;
    }

    /**
     * Get a random list of users who this user follows.
     */
    public function getRandomFollows($limit) {
        $followCount = Follow::find()->where(['followerId'=>$this->userId])->count();
        
        if($followCount <= $limit) {
            $res = User::find()->innerJoin(Follow::tableName(), 'userId=followeeId')
                ->where(['followerId'=>$this->userId])->all();
        }
        else {
            $offset = rand(0, $followCount - $limit);
            $res = User::find()->innerJoin(Follow::tableName(), 'userId=followeeId')
                ->where(['followerId'=>$this->userId])
                ->offset($offset)->limit($limit)->all();
        }

        shuffle($res);
        return $res;
    }

    /**
     * Gets a random list of users who follow this user.
     */
    public function getRandomFollowers($limit) {
        $followerCount = Follow::find()->where(['followeeId'=>$this->userId])->count();
        
        if($followerCount <= $limit) {
            $res = User::find()->innerJoin(Follow::tableName(), 'userId=followerId')
                ->where(['followeeId'=>$this->userId])->all();
        }
        else {
            $offset = rand(0, $followCount - $limit);
            $res = User::find()->innerJoin(Follow::tableName(), 'userId=followerId')
                ->where(['followeeId'=>$this->userId])
                ->offset($offset)->limit($limit)->all();
        }

        shuffle($res);
        return $res;
    }

    /**
     * Gets an array of genders for drop-down menus.
     */
    public static function getGenders() {
        return [''=>'', 'female'=>'Female', 'male'=>'Male'];
    }

    /**
     * Produces an hash for $password.
     */
    public static function hashPassword($password){
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verify that $password matches current user's password.
     */
    public function verifyPassword($password) {
        return password_verify($password, $this->phash);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCounters()
    {
        return $this->hasMany(Counter::className(), ['userId' => 'userId']);
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