<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_goods_material}}".
 *
 * @property int $id
 * @property int $order_goods_id 订单商品id，关联order_goods表id字段
 * @property int $material_id 素材id，关联goods_material表id字段
 * @property int $material_value_id 素材值id，关联goods_material_value表id字段
 * @property int $is_del 是否已删除 0否 1是
 */
class OrderGoodsMaterial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_goods_material}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_goods_id', 'material_id', 'material_value_id'], 'required'],
            [['order_goods_id', 'material_id', 'material_value_id', 'is_del'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_goods_id' => Yii::t('app', '订单商品id，关联order_goods表id字段'),
            'material_id' => Yii::t('app', '素材id，关联goods_material表id字段'),
            'material_value_id' => Yii::t('app', '素材值id，关联goods_material_value表id字段'),
            'is_del' => Yii::t('app', '是否已删除 0否 1是'),
        ];
    }
}
