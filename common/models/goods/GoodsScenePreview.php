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
 * @property string $name 名称
 * @property string $effect_url 图片路径
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
            [['name'], 'string', 'max' => 50],
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
            'name' => Yii::t('app', 'Name'),
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

    /**
     * 获取商品对应场景预览
     *
     * @param int $goods_id
     * @param int $material_value_id
     */
    public static function getGoodsPreview($goods_id, $material_value_id)
    {
        $result = self::find()->alias('preview')
            ->innerJoin(['scene' => GoodsScene::tableName()], 'scene.id = preview.scene_id AND scene.is_del = 0')
            ->where([
                'preview.goods_id' => $goods_id,
                'preview.material_value_id' => $material_value_id,
                'preview.is_del' => 0,
                'scene.immutable' => 0,
            ])
            ->orderBy('scene.sort_order asc')
            ->asArray()->all();
        return $result;
    }
}
