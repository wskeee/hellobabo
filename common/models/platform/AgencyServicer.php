<?php

namespace common\models\platform;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%agency_servicer}}".
 *
 * @property int $id
 * @property int $user_id 用户ID，关联user.id
 * @property int $agency_id 代理ID，关联agency.id
 * @property int $is_del 是否删除 0否 1是
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property User user 用户
 * @property Agency agency 代理
 */
class AgencyServicer extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agency_servicer}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'agency_id', 'is_del', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'agency_id' => Yii::t('app', 'Agency ID'),
            'is_del' => Yii::t('app', 'Is Del'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAgency()
    {
        return $this->hasOne(Agency::class, ['id' => 'agency_id']);
    }
}
