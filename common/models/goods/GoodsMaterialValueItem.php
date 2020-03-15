<?php

namespace common\models\goods;

use common\utils\I18NUitl;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * This is the model class for table "{{%goods_material_value_item}}".
 *
 * @property int $id
 * @property int $material_value_id 素材值ID，关联goods_material_value,id
 * @property string $source_id 源始ID，adobeID
 * @property string $name 名称
 * @property string $effect_url 效果图路径
 * @property string $source_url 源始路径
 * @property int $is_del 是否删除
 * @property string $des 备注
 *
 * @property GoodsMaterialValue $materialValue 所属素材值
 * @property GoodsScenePage $scenePages 已关联的所有场景页
 */
class GoodsMaterialValueItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_material_value_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['material_value_id', 'is_del'], 'integer'],
            [['source_id'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 50],
            [['effect_url', 'source_url', 'des'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'material_value_id' => I18NUitl::t('app', '{Material}{Value}'),
            'source_id' => Yii::t('app', 'Source ID'),
            'name' => Yii::t('app', 'Name'),
            'effect_url' => Yii::t('app', 'Effect Url'),
            'source_url' => Yii::t('app', 'Source Url'),
            'is_del' => Yii::t('app', 'Is Del'),
            'des' => Yii::t('app', 'Des'),
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
     * 返回和场景页的关联
     * @return array [GoodsScenePage]
     */
    public function getScenePages()
    {
        return GoodsScenePageMaterialItem::getScenePages($this->id);
    }
}
