<?php

namespace common\models\goods;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%goods_category}}".
 *
 * @property int $id
 * @property string $name 分类名称
 * @property int $level 等级：0顶级 1~3
 * @property string $path 继承路径
 * @property int $parent_id 父级id
 * @property int $sort_order 排序
 * @property string $image 图标路径
 * @property int $created_by 创建者ID,关联admin_user,id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $is_show 是否显示
 * @property string $des 描述
 */
class GoodsCategory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'parent_id', 'sort_order', 'created_by', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['path', 'image', 'des'], 'string', 'max' => 255],
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
            'level' => Yii::t('app', 'Level'),
            'path' => Yii::t('app', 'Path'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'image' => Yii::t('app', 'Image'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'is_del' => Yii::t('app', 'Is Del'),
            'des' => Yii::t('app', 'Des'),
        ];
    }
    
    public static function getCategory($key_to_map = true){
        $users = self::findAll(['is_del' => 0]);
        return $key_to_map ? ArrayHelper::map($users, 'id', 'name') : $users;
    }
}