<?php

namespace common\models\activity;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%vote_activity_hand_apply}}".
 *
 * @property int $id
 * @property int $activity_id 活动ID,关联vote_activity,id
 * @property int $target_user_id 用户ID,关联User
 * @property string $target_name 名称
 * @property int $target_age 年龄
 * @property string $target_img 图片
 * @property int $check_status 审核状态 0待审 1审核失败 2审核成功
 * @property string $check_feedback 审核原因
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * 
 * @property VoteActivity $activity 活动
 */
class VoteActivityHandApply extends ActiveRecord
{

    const CHECK_STATUS_WAITING = 0;
    const CHECK_STATUS_FAILED = 1;
    const CHECK_STATUC_SUCCESS = 2;

    public static $checkStatusNameMap = [
        self::CHECK_STATUS_WAITING => '待审核',
        self::CHECK_STATUS_FAILED => '审核失败',
        self::CHECK_STATUC_SUCCESS => '审核成功',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%vote_activity_hand_apply}}';
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
            [['activity_id', 'target_user_id', 'target_age', 'check_status', 'created_at', 'updated_at'], 'integer'],
            [['target_name'], 'string', 'max' => 50],
            [['target_img', 'check_feedback'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'activity_id' => Yii::t('app', 'Activity'),
            'target_user_id' => Yii::t('app', 'User'),
            'target_name' => Yii::t('app', 'Name'),
            'target_age' => Yii::t('app', 'Age'),
            'target_img' => Yii::t('app', 'Img'),
            'check_status' => Yii::t('app', 'Check Status'),
            'check_feedback' => Yii::t('app', 'Check Feedback'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return VoteActivity 
     */
    public function getActivity()
    {
        return $this->hasOne(VoteActivity::class, ['id' => 'activity_id']);
    }

}
