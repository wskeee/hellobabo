<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_att_value_ref}}".
 *
 * @property int $id
 * @property int $goods_id 商品id，关联goods表id字段
 * @property int $attribute_id 属性id，关联goods_attribute表id字段
 * @property int $attribute_value_id 属性值id，关联goods_attribute_value表id字段
 * @property int $is_del 是否已删除 0否 1是
 *
 * @property Goods $goods
 * @property GoodsAttribute $attribute0
 * @property GoodsAttributeValue $attributeValue
 */
class GoodsAttValueRef extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_att_value_ref}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'attribute_id', 'attribute_value_id'], 'required'],
            [['goods_id', 'attribute_id', 'attribute_value_id', 'is_del'], 'integer'],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodsAttribute::className(), 'targetAttribute' => ['attribute_id' => 'id']],
            [['attribute_value_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodsAttributeValue::className(), 'targetAttribute' => ['attribute_value_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_id' => Yii::t('app', '商品id，关联goods表id字段'),
            'attribute_id' => Yii::t('app', '属性id，关联goods_attribute表id字段'),
            'attribute_value_id' => Yii::t('app', '属性值id，关联goods_attribute_value表id字段'),
            'is_del' => Yii::t('app', '是否已删除 0否 1是'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttribute0()
    {
        return $this->hasOne(GoodsAttribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeValue()
    {
        return $this->hasOne(GoodsAttributeValue::className(), ['id' => 'attribute_value_id']);
    }
}
