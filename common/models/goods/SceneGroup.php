<?php

namespace common\models\goods;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%scene_group}}".
 *
 * @property int $id
 * @property string $name
 * @property string $des 描述
 * @property int $is_del 是否删除 0否 1是
 */
class SceneGroup extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%scene_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_del'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['des'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'des' => Yii::t('app', 'Des'),
            'is_del' => Yii::t('app', '是否删除'),
        ];
    }
    /**
     * 获取分组
     * @param bool $key_to_map      是否以键值格式返回
     * @return array [key => name]
     */
    public static function getGroup($key_to_map = true){
        $models = self::findAll(['is_del' => 0]);
        return $key_to_map ? ArrayHelper::map($models, 'id', 'name') : $models;
    }
}
