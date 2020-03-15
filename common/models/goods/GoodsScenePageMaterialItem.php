<?php

namespace common\models\goods;

use common\utils\I18NUitl;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * This is the model class for table "{{%goods_scene_page_material_item}}".
 *
 * @property int $id
 * @property int $scene_page_id 场景页ID，关联goods_scene_page id
 * @property int $material_value_id 素材值ID，关联goods_material_value id
 * @property int $material_value_item_id 素材场景值ID，关联goods_material_value_item id
 * @property int $is_del 是否删除 0否 1是
 *
 * @property GoodsScenePage $scenePage 场景页
 * @property GoodsMaterialValue $materialValue 素材值
 * @property GoodsMaterialValueItem $materialValueItem 素材项
 */
class GoodsScenePageMaterialItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_scene_page_material_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scene_page_id', 'material_value_id', 'material_value_item_id', 'is_del'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'scene_page_id' => I18NUitl::t('app', 'Scene Page ID'),
            'material_value_id' => I18NUitl::t('app', '{Material}{Value}'),
            'material_value_item_id' => I18NUitl::t('app', '{Material}{Item}'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getScenePage()
    {
        return $this->hasOne(GoodsScenePage::class, ['id' => 'scene_page_id']);
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
    public function getMaterialValueItem()
    {
        return $this->hasOne(GoodsMaterialValueItem::class, ['id' => 'material_value_item_id']);
    }

    /**
     * 返回场景已关联的素材项
     * @param int $scene_page_id 场景页ID
     * @return array
     */
    public static function getPageMaterialItems($scene_page_id)
    {
        $query = (new Query())->select(['MaterialItem.*'])
            ->from(['PageItem' => self::tableName()])
            ->where(['PageItem.scene_page_id' => $scene_page_id, 'PageItem.is_del' => 0])
            ->leftJoin(['MaterialItem' => GoodsMaterialValueItem::tableName()], 'PageItem.material_value_item_id = MaterialItem.id');

        return $query->all();
    }

    /**
     * 返回和场景页的关联
     * @param int $material_item_id 素材项ID
     * @return array
     */
    public static function getScenePages($material_item_id)
    {
        $query = (new Query())->select(['Page.*'])
            ->from(['PageItem' => self::tableName()])
            ->where(['PageItem.material_value_item_id' => $material_item_id, 'PageItem.is_del' => 0])
            ->leftJoin(['Page' => GoodsScenePage::tableName()], 'Page.id = PageItem.scene_page_id');

        return $query->all();
    }
}
