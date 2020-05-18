<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%user_coupon}}".
 *
 * @property int $id
 * @property int $user_id 用户ID 关联用户 user
 * @property int $coupon_id 优惠卷ID 关联 coupon id
 * @property int $valid_start_time 有效开始时间
 * @property int $valid_end_time 有效结束时间
 * @property int $used_time 使用时间
 * @property int $status 状态 1未使用 2已使用 3已过期
 * @property int $order_id 优惠卷使用到的订单ID 关联order id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class UserCoupon extends \yii\db\ActiveRecord
{
    const STATUS_UNUSED = 1;
    const STATUS_USED = 2;
    const STATUS_TIMEOUT = 3;

    public static $statusNames = [
        self::STATUS_UNUSED => '未使用',
        self::STATUS_USED => '已使用',
        self::STATUS_TIMEOUT => '已过期',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_coupon}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'user_id', 'coupon_id', 'valid_start_time', 'valid_end_time', 'used_time', 'status', 'order_id', 'created_at', 'updated_at'], 'integer'],
            [['id'], 'unique'],
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
            'coupon_id' => Yii::t('app', 'Coupon ID'),
            'valid_start_time' => Yii::t('app', 'Valid Start Time'),
            'valid_end_time' => Yii::t('app', 'Valid End Time'),
            'used_time' => Yii::t('app', 'Used Time'),
            'status' => Yii::t('app', 'Status'),
            'order_id' => Yii::t('app', 'Order ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
