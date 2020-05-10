<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_scene_material}}".
 *
 * @property int $id
 * @property int $goods_id 商品id，关联goods表id字段
 * @property int $material_value_id 素材值ID，关联goods_material_value id
 * @property int $scene_id 场景ID，关联goods_scene id
 * @property int $is_del 是否删除 0否 1是
 */
class GoodsSceneMaterial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_scene_material}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'material_value_id', 'scene_id', 'is_del'], 'integer'],
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
            'material_value_id' => Yii::t('app', 'Material'),
            'scene_id' => Yii::t('app', 'Scene'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }
}
