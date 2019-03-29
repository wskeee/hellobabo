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
 * @property string $spec_img 图片
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
            [['spec_img', 'spec_des'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_id' => Yii::t('app', '商品ID,关联goods,id'),
            'goods_cost' => Yii::t('app', '成本'),
            'goods_price' => Yii::t('app', '价格'),
            'spec_key' => Yii::t('app', '所有规格id：s_s_s'),
            'spec_key_name' => Yii::t('app', '规格值:s_s_s'),
            'spec_img' => Yii::t('app', '图片'),
            'spec_des' => Yii::t('app', '描述'),
            'store_count' => Yii::t('app', '存货量'),
            'sort_order' => Yii::t('app', '排序'),
            'is_del' => Yii::t('app', '是否已删除：0否 1是'),
        ];
    }
}
