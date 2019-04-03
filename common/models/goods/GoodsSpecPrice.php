<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_spec_price}}".
 *
 * @property int $id
 * @property int $goods_id 商品ID,关联goods,id
 * @property string $goods_cost 成本
 * @property string $goods_price 价格
 * @property string $spec_key 所有规格id：s_s_s
 * @property string $spec_key_name 规格值:s_s_s
 * @property string $spec_img_url 图片
 * @property string $spec_des 描述
 * @property int $store_count 存货量
 * @property int $sort_order 排序
 * @property int $is_del 是否已删除：0否 1是
 */
class GoodsSpecPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_spec_price}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'store_count', 'sort_order', 'is_del'], 'integer'],
            [['goods_cost', 'goods_price'], 'number'],
            [['spec_key', 'spec_key_name'], 'string', 'max' => 100],
            [['spec_img_url', 'spec_des'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_id' => Yii::t('app', 'Goods'),
            'goods_cost' => Yii::t('app', 'Cost'),
            'goods_price' => Yii::t('app', 'Price'),
            'spec_key' => Yii::t('app', 'Spec Key'),
            'spec_key_name' => Yii::t('app', 'Spec Key Name'),
            'spec_img_url' => Yii::t('app', 'Image'),
            'spec_des' => Yii::t('app', 'Des'),
            'store_count' => Yii::t('app', 'Store Count'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }
}
