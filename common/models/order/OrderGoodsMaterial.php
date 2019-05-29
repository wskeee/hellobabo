<?php

namespace common\models\order;

use common\utils\I18NUitl;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%order_goods_material}}".
 *
 * @property int $id
 * @property int $order_id 订单ID，关联Order,id
 * @property int $material_id 素材id，关联goods_material表id字段
 * @property int $value_id 素材值id，关联goods_material_value表id字段
 * @property int $value_name 素材值名称
 * @property int $value_effect_url 素材值效果图路径
 * @property int $value_source_url 素材值原图路径
 * @property int $value_des 素材值描述
 * @property int $is_del 是否已删除 0否 1是
 */
class OrderGoodsMaterial extends ActiveRecord
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
            [['order_id', 'material_id', 'value_id', 'is_del'], 'integer'],
            [['material_id', 'value_id'], 'required'],
            [['value_name',], 'string', 'max' => 20],
            [['value_effect_url', 'value_source_url', 'value_des'], 'string', 'max' => 255],
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
            'material_id' => Yii::t('app', 'Material ID'),
            'value_id' => Yii::t('app', 'Material Value ID'),
            'value_name' => I18NUitl::t('app', '{Material}{Name}'),
            'value_effect_url' => Yii::t('app', 'Effect Url'),
            'value_source_url' => Yii::t('app', 'Source Url'),
            'value_des' => Yii::t('app', 'Des'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }

}
