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
 * @property int $status 状态 0未提现，1已提现
 * @property string $award 奖励
 * @property int $recommend_by 推荐人id,关联admin_user表id字段
 * @property int $created_by 创建人（购买人）ID,关联user,id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property Order $order
 * @property WorkflowDesign[] $ids
 * @property OrderGoods[] $ids0
 * @property WorkflowPrint[] $ids1
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
            [['order_id', 'status', 'recommend_by', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['order_amount', 'award'], 'number'],
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
            'order_id' => Yii::t('app', '订单id，关联order表id字段'),
            'order_sn' => Yii::t('app', '订单编号,关联order,order_sn'),
            'order_amount' => Yii::t('app', '订单总额'),
            'goods_name' => Yii::t('app', '商品名称'),
            'status' => Yii::t('app', '状态 0未提现，1已提现'),
            'award' => Yii::t('app', '奖励'),
            'recommend_by' => Yii::t('app', '推荐人id,关联admin_user表id字段'),
            'created_by' => Yii::t('app', '创建人（购买人）ID,关联user,id'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds()
    {
        return $this->hasMany(WorkflowDesign::className(), ['order_id' => 'id'])->viaTable('{{%order}}', ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds0()
    {
        return $this->hasMany(OrderGoods::className(), ['order_id' => 'id'])->viaTable('{{%order}}', ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds1()
    {
        return $this->hasMany(WorkflowPrint::className(), ['order_id' => 'id'])->viaTable('{{%order}}', ['id' => 'id']);
    }
}
