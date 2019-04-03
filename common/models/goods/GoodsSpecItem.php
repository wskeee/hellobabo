<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_spec_item}}".
 *
 * @property int $id
 * @property int $spec_id 规格ID,关联goods_spec,id
 * @property int $goods_id 商品ID,关联goods,id
 * @property string $value 规格
 * @property int $sort_order 排序
 * @property int $is_del 是否已删除：0否 1是
 */
class GoodsSpecItem extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_spec_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['spec_id', 'goods_id', 'sort_order', 'is_del'], 'integer'],
            [['value'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'spec_id' => Yii::t('app', 'Spec'),
            'goods_id' => Yii::t('app', 'Goods'),
            'value' => Yii::t('app', 'Value'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }

}
