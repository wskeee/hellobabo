<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_scene_element}}".
 *
 * @property int $id
 * @property int $scene_id 场景ID,关联goods_scene,id
 * @property int $element_id 元素ID，关联goods_element,id
 * @property string $config 配置
 * @property int $is_del 是否删除 0否1是
 */
class GoodsSceneElement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_scene_element}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scene_id', 'element_id'], 'required'],
            [['scene_id', 'element_id', 'is_del'], 'integer'],
            [['config'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'scene_id' => Yii::t('app', 'Scene ID'),
            'element_id' => Yii::t('app', 'Element ID'),
            'config' => Yii::t('app', 'Config'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }
}
