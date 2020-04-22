<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property int $id ID
 * @property string $title 优惠券标题（有图片则显示图片）：无门槛50元优惠券 | 单品最高减2000元
 * @property string $icon_url 图片路径
 * @property string $des 描述
 * @property int $used 可用于：1新人平台券 2平台优惠券 3类目优惠券 4商品优惠券 10 优惠码卷(平台看不到)
 * @property int $type 1满减券 2叠加满减券 3无门槛券（需要限制大小）
 * @property int $with_special 1可用于特价商品 2不能  默认不能(商品优惠卷除外)
 * @property int $with_id 指定使用的类目、商品ID
 * @property int $with_amount 满多少金额可用
 * @property int $used_amount 用券金额
 * @property int $quota 配额：发券数量
 * @property int $take_count 已领取的优惠券数量
 * @property int $used_count 已使用的优惠券数量
 * @property int $start_time 发放开始时间
 * @property int $end_time 发放结束时间
 * @property int $valid_type 时效:1绝对时效（领取后XXX-XXX时间段有效）? 2相对时效（领取后N天有效）
 * @property int $valid_start_time 使用开始时间
 * @property int $valid_end_time 使用结束时间
 * @property int $valid_days 自领取之日起有效天数
 * @property int $status 0未发布 1生效 2失效 3已结束
 * @property int $created_by 创建用户 关联admin_user id
 * @property int $updated_by 更新用户ID 关联 admin_user id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%coupon}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['used', 'type', 'with_special', 'with_id', 'with_amount', 'used_amount', 'quota', 'take_count', 'used_count', 'start_time', 'end_time', 'valid_type', 'valid_start_time', 'valid_end_time', 'valid_days', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 64],
            [['icon_url', 'des'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'icon_url' => Yii::t('app', 'Icon Url'),
            'des' => Yii::t('app', 'Des'),
            'used' => Yii::t('app', 'Used'),
            'type' => Yii::t('app', 'Type'),
            'with_special' => Yii::t('app', 'With Special'),
            'with_id' => Yii::t('app', 'With ID'),
            'with_amount' => Yii::t('app', 'With Amount'),
            'used_amount' => Yii::t('app', 'Used Amount'),
            'quota' => Yii::t('app', 'Quota'),
            'take_count' => Yii::t('app', 'Take Count'),
            'used_count' => Yii::t('app', 'Used Count'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'valid_type' => Yii::t('app', 'Valid Type'),
            'valid_start_time' => Yii::t('app', 'Valid Start Time'),
            'valid_end_time' => Yii::t('app', 'Valid End Time'),
            'valid_days' => Yii::t('app', 'Valid Days'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
