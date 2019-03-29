<?php

namespace common\models\goods;

use Yii;

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
 */
class GoodsMaterialValue extends \yii\db\ActiveRecord
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
            [['material_id', 'is_selected', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['effect_url', 'source_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'material_id' => Yii::t('app', '属性id 关联goods_material表id字段'),
            'name' => Yii::t('app', '名称'),
            'effect_url' => Yii::t('app', '\'\''),
            'source_url' => Yii::t('app', '源图地址'),
            'is_selected' => Yii::t('app', '是否选择 0否 1是'),
            'is_del' => Yii::t('app', '是否已删除 1是 0否'),
        ];
    }
}
