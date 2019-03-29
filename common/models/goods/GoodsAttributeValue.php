<?php

namespace common\models\goods;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%goods_attribute_value}}".
 *
 * @property int $id
 * @property int $attribute_id 属性id 关联goods_attribute表id字段
 * @property string $value ''
 * @property int $is_del 是否已删除 1是 0否
 *
 * @property GoodsAttValueRef[] $goodsAttValueRefs
 * @property GoodsAttribute $attribute0
 */
class GoodsAttributeValue extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_attribute_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_id'], 'required'],
            [['attribute_id', 'is_del'], 'integer'],
            [['value'], 'string'],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodsAttribute::className(), 'targetAttribute' => ['attribute_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'attribute_id' => Yii::t('app', 'Attribute'),
            'value' => Yii::t('app', 'Value'),
            'is_del' => Yii::t('app', 'Is Use'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsAttValueRefs()
    {
        return $this->hasMany(GoodsAttValueRef::className(), ['attribute_value_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsAttribute()
    {
        return $this->hasOne(GoodsAttribute::className(), ['id' => 'attribute_id']);
    }
}
