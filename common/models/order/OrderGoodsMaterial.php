<?php

namespace common\models\order;

use common\models\goods\GoodsMaterial;
use common\models\goods\GoodsMaterialValue;
use common\utils\I18NUitl;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "{{%order_goods_material}}".
 *
 * @property int $id
 * @property int $order_goods_id 订单ID，关联OrderGoods,id
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
            [['order_goods_id', 'material_id', 'value_id', 'is_del'], 'integer'],
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
            'order_goods_id' => I18NUitl::t('app', '{Order}{Goods}'),
            'material_id' => Yii::t('app', 'Material ID'),
            'value_id' => Yii::t('app', 'Material Value ID'),
            'value_name' => I18NUitl::t('app', '{Material}{Name}'),
            'value_effect_url' => Yii::t('app', 'Effect Url'),
            'value_source_url' => Yii::t('app', 'Source Url'),
            'value_des' => Yii::t('app', 'Des'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }

    /**
     * 初始默认素材
     *
     * @param OrderGoods $order_goods 订单商品
     * @param array $material_value_ids 素材值ID
     *
     * @return int
     */
    public static function initDefaultMaterial($order_goods, $material_value_ids)
    {
        $material_values = GoodsMaterialValue::find()->where([
            'id' => $material_value_ids,
            'is_del' => 0
        ])->all();

        // 批量插入素材与订单关联
        $rows = [];
        /* @var $material_value GoodsMaterialValue */
        foreach ($material_values as $material_value) {
            $rows [] = [
                $order_goods->id,
                $material_value->material_id,
                $material_value->id,
                $material_value->name,
                $material_value->effect_url,
                $material_value->source_url,
                $material_value->des,
            ];
        }

        $result = Yii::$app->db->createCommand()->batchInsert(OrderGoodsMaterial::tableName(), [
            'order_goods_id',
            'material_id',
            'value_id',
            'value_name',
            'value_effect_url',
            'value_source_url',
            'value_des',
        ], $rows)->execute();

        return $result;
    }

}
