<?php

namespace common\models\goods;

use common\utils\I18NUitl;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%goods_scene_preview}}".
 *
 * @property int $id
 * @property int $goods_id 商品id，关联goods表id字段
 * @property int $material_id 素材ID，关联goods_material id
 * @property int $material_value_id 素材值ID，关联goods_material_value id
 * @property int $scene_id 场景ID，关联goods_scene id
 * @property string $effect_url
 * @property int $is_del 是否删除 0否 1是
 *
 * @property GoodsMaterialValue $materialValue 素材值
 * @property Goods $goods 商品
 * @property GoodsScene $scene 场景
 */
class GoodsScenePreview extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_scene_preview}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'material_id', 'material_value_id', 'scene_id', 'is_del'], 'integer'],
            [['effect_url'], 'required'],
            [['effect_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_id' => Yii::t('app', 'Goods ID'),
            'material_id' => Yii::t('app', 'Material ID'),
            'material_value_id' => I18NUitl::t('app', '{Material}{Value}'),
            'scene_id' => Yii::t('app', 'Scene'),
            'effect_url' => Yii::t('app', 'Effect Url'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getMaterialValue()
    {
        return $this->hasOne(GoodsMaterialValue::class, ['id' => 'material_value_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getScene()
    {
        return $this->hasOne(GoodsScene::class, ['id' => 'scene_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::class, ['id' => 'goods_id']);
    }
}
