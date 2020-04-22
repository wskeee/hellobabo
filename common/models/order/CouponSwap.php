<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%coupon_swap}}".
 *
 * @property int $id
 * @property int $coupon_id 优惠卷ID 关联coupon id
 * @property string $code 兑换码
 * @property string $condition 附件条件
 * @property int $is_swap 是否兑换 0否 1是
 * @property int $swap_id 兑换ID 关联user_coupon id
 * @property int $swap_at 兑换时间
 * @property int $is_del 是否已删除 0否 1是
 * @property int $created_by 创建人 关联admin_user id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class CouponSwap extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%coupon_swap}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['coupon_id', 'is_swap', 'swap_id', 'swap_at', 'is_del', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 50],
            [['condition'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'coupon_id' => Yii::t('app', 'Coupon ID'),
            'code' => Yii::t('app', 'Code'),
            'condition' => Yii::t('app', 'Condition'),
            'is_swap' => Yii::t('app', 'Is Swap'),
            'swap_id' => Yii::t('app', 'Swap ID'),
            'swap_at' => Yii::t('app', 'Swap At'),
            'is_del' => Yii::t('app', 'Is Del'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
