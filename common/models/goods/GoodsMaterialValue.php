<?php

namespace common\models\goods;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%goods_material_value}}".
 *
 * @property int $id
 * @property int $material_id 属性id 关联goods_material表id字段
 * @property string $name 名称
 * @property string $effect_url ''
 * @property string $source_url 源图地址
 * @property int $is_selected 是否选择 0否 1是
 * @property int $is_del 是否已删除 1是 0否
 * @property int $sort_order 排序
 * @property string $des 备注
 */
class GoodsMaterialValue extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_material_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['material_id'], 'required'],
            [['material_id', 'is_selected', 'is_del', 'sort_order'], 'integer'],
            [['name'], 'string', 'max' => 20],
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
            'material_id' => Yii::t('app', 'Material'),
            'name' => Yii::t('app', 'Name'),
            'effect_url' => Yii::t('app', 'Effect Url'),
            'source_url' => Yii::t('app', 'Source Url'),
            'is_selected' => Yii::t('app', 'Is Selected'),
            'is_del' => Yii::t('app', 'Is Del'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'des' => Yii::t('app', 'Des'),
        ];
    }

}
