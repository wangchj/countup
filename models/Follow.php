<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Follows".
 *
 * @property integer $followerId
 * @property integer $followeeId
 *
 * @property Users $followee
 * @property Users $follower
 */
class Follow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Follows';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['followerId', 'followeeId'], 'required'],
            [['followerId', 'followeeId'], 'integer'],
            [['followerId', 'followeeId'], 'unique', 'targetAttribute' => ['followerId', 'followeeId'], 'message' => 'The combination of Follower ID and Followee ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'followerId' => 'Follower ID',
            'followeeId' => 'Followee ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowee()
    {
        return $this->hasOne(Users::className(), ['userId' => 'followeeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollower()
    {
        return $this->hasOne(Users::className(), ['userId' => 'followerId']);
    }
}