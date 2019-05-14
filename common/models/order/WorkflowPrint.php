<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%workflow_print}}".
 *
 * @property int $id
 * @property int $order_id 订单id，关联order表id字段
 * @property string $order_sn 订单编号，关联order,sn
 * @property int $order_goods_id 订单商品ID,关联order_goods,id
 * @property int $status 状态 0未完成，1已完成
 * @property int $start_at 开始时间
 * @property int $end_at 结束时间
 * @property int $created_by 操作人id,关联admin_user表id字段
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class WorkflowPrint extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%workflow_print}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'order_goods_id', 'status', 'start_at', 'end_at', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['order_sn'], 'string', 'max' => 20],
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
            'order_goods_id' => Yii::t('app', 'Order Goods ID'),
            'status' => Yii::t('app', 'Status'),
            'start_at' => Yii::t('app', 'Start At'),
            'end_at' => Yii::t('app', 'End At'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
