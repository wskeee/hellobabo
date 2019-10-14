<?php

namespace common\models\goods;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%scene_group}}".
 *
 * @property int $id
 * @property string $relate_id 相关ID，关联goods,id
 * @property string $name
 * @property string $des 描述
 * @property string $cover_url 封面路径
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
            [['relate_id', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['des', 'cover_url'], 'string', 'max' => 255],
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
            'relate_id' => Yii::t('app', 'Goods'),
            'name' => Yii::t('app', 'Name'),
            'des' => Yii::t('app', 'Des'),
            'cover_url' => Yii::t('app', 'Cover'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }

    /**
     * 获取分组
     * @param bool $key_to_map      是否以键值格式返回
     * @return array [key => name]
     */
    public static function getGroup($relate_id = null, $key_to_map = true)
    {
        $query = self::find()->where(['is_del' => 0, 'relate_id' => $relate_id]);
        $models = $query->all();
        return $key_to_map ? ArrayHelper::map($models, 'id', 'name') : $models;
    }

}
