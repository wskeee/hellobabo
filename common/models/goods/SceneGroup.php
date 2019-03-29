<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%scene_group}}".
 *
 * @property int $id
 * @property string $name
 * @property string $des 描述
 * @property int $is_del 是否删除 0否 1是
 */
class SceneGroup extends \yii\db\ActiveRecord
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
}
