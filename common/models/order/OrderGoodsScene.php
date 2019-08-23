<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_goods_scene}}".
 *
 * @property int $id
 * @property int $order_id 订单ID，关联Order,id
 * @property int $scene_id 场景ID
 * @property string $name 场景名
 * @property string $effect_url 效果图路径
 * @property string $demo_url 参考路径
 * @property string $source_url 源图路径
 * @property int $sort_order 排序
 * @property int $immutable 不可更改 0否 1是
 * @property int $is_required 是否必选 0否 1是
 * @property int $is_del 是否删除
 * @property string $des 备注
 */
class OrderGoodsScene extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_goods_scene}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'scene_id', 'sort_order', 'immutable', 'is_required', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['effect_url', 'demo_url', 'source_url', 'des'], 'string', 'max' => 255],
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
            'scene_id' => Yii::t('app', 'Scene'),
            'name' => Yii::t('app', 'Name'),
            'effect_url' => Yii::t('app', 'Effect Url'),
            'demo_url' => Yii::t('app', 'Demo Url'),
            'source_url' => Yii::t('app', 'Source Url'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'immutable' => Yii::t('app', 'Immutable'),
            'is_required' => Yii::t('app', 'Is Required'),
            'is_del' => Yii::t('app', 'Is Del'),
            'des' => Yii::t('app', 'Des'),
        ];
    }

}
