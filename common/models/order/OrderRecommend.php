<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_recommend}}".
 *
 * @property int $id
 * @property int $order_id 订单id，关联order表id字段
 * @property string $order_sn 订单编号,关联order,order_sn
 * @property string $order_amount 订单总额
 * @property string $goods_name 商品名称
 * @property string $amount 参与奖励金额
 * @property int $recommend_by 推荐人id,关联admin_user表id字段
 * @property int $created_by 创建人（购买人）ID,关联user,id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class OrderRecommend extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_recommend}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'created_by'], 'required'],
            [['order_id', 'recommend_by', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['order_amount', 'amount'], 'number'],
            [['order_sn'], 'string', 'max' => 20],
            [['goods_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'order_sn' => Yii::t('app', 'Order Sn'),
            'order_amount' => Yii::t('app', 'Order Amount'),
            'goods_name' => Yii::t('app', 'Goods Name'),
            'amount' => Yii::t('app', 'Amount'),
            'recommend_by' => Yii::t('app', 'Recommend By'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
