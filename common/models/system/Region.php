<?php

namespace common\models\system;

use Yii;

/**
 * This is the model class for table "{{%region}}".
 *
 * @property int $id 表id
 * @property string $name 地区名称
 * @property int $level 地区等级 分省市县区
 * @property int $parent_id 父id
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%region}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'parent_id'], 'integer'],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '表id'),
            'name' => Yii::t('app', '地区名称'),
            'level' => Yii::t('app', '地区等级 分省市县区'),
            'parent_id' => Yii::t('app', '父id'),
        ];
    }
}
