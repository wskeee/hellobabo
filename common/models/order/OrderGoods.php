<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_goods}}".
 *
 * @property int $id
 * @property int $order_id 订单ID，关联order表id字段
 * @property string $order_sn 订单编号
 * @property int $goods_id  商品（媒体）ID，关联media表id字段
 * @property int $goods_num 购买数
 * @property string $goods_price 商品价格
 * @property string $goods_cost 商品成本
 * @property int $spec_id 规格id
 * @property string $spec_key 商品规格key
 * @property string $spec_key_name 规格对应的中文名字
 * @property string $amount 总价
 * @property int $work_status 制作状态：0未准备5待初始10待上图11待设计12设计中15待印刷16印刷中20已完成
 * @property int $init_at 初始时间
 * @property int $upload_finish_at 上图时间
 * @property int $design_at 设计时间
 * @property int $print_at 印刷时间
 * @property int $is_del 是否已删除 0否 1是
 * @property int $created_by 创建人（购买人），关联user表id字段
 * @property int $created_at 创建时间（购买时间）
 * @property int $updated_at 更新时间
 *
 * @property Order $order
 * @property WorkflowDesign[] $ids
 * @property WorkflowPrint[] $ids0
 * @property OrderRecommend[] $ids1
 */
class OrderGoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_goods}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'goods_id', 'created_by'], 'required'],
            [['order_id', 'goods_id', 'goods_num', 'spec_id', 'work_status', 'init_at', 'upload_finish_at', 'design_at', 'print_at', 'is_del', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['goods_price', 'goods_cost', 'amount'], 'number'],
            [['order_sn'], 'string', 'max' => 20],
            [['spec_key', 'spec_key_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', '订单ID，关联order表id字段'),
            'order_sn' => Yii::t('app', '订单编号'),
            'goods_id' => Yii::t('app', ' 商品（媒体）ID，关联media表id字段'),
            'goods_num' => Yii::t('app', '购买数'),
            'goods_price' => Yii::t('app', '商品价格'),
            'goods_cost' => Yii::t('app', '商品成本'),
            'spec_id' => Yii::t('app', '规格id'),
            'spec_key' => Yii::t('app', '商品规格key'),
            'spec_key_name' => Yii::t('app', '规格对应的中文名字'),
            'amount' => Yii::t('app', '总价'),
            'work_status' => Yii::t('app', '制作状态：0未准备5待初始10待上图11待设计12设计中15待印刷16印刷中20已完成'),
            'init_at' => Yii::t('app', '初始时间'),
            'upload_finish_at' => Yii::t('app', '上图时间'),
            'design_at' => Yii::t('app', '设计时间'),
            'print_at' => Yii::t('app', '印刷时间'),
            'is_del' => Yii::t('app', '是否已删除 0否 1是'),
            'created_by' => Yii::t('app', '创建人（购买人），关联user表id字段'),
            'created_at' => Yii::t('app', '创建时间（购买时间）'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds()
    {
        return $this->hasMany(WorkflowDesign::className(), ['order_id' => 'id'])->viaTable('{{%order}}', ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds0()
    {
        return $this->hasMany(WorkflowPrint::className(), ['order_id' => 'id'])->viaTable('{{%order}}', ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds1()
    {
        return $this->hasMany(OrderRecommend::className(), ['id' => 'id'])->viaTable('{{%order}}', ['id' => 'order_id']);
    }
}
