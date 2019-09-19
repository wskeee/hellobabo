<?php

namespace common\models\activity;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%vote_record}}".
 *
 * @property int $id
 * @property int $vote_user_id 投票用户id,关联User
 * @property int $activity_hand_id 参赛ID，关联vote_activity_hand,id
 * @property string $add_date 添加时间
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class VoteRecord extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%vote_record}}';
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
            [['vote_user_id', 'activity_hand_id', 'created_at', 'updated_at'], 'integer'],
            [['add_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'vote_user_id' => Yii::t('app', 'Vote User ID'),
            'activity_hand_id' => Yii::t('app', 'Activity Hand ID'),
            'add_date' => Yii::t('app', 'Add Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
